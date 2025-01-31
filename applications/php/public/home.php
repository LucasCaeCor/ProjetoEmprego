<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Busca informações do usuário logado
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Buscar usuários que enviaram mensagens
$sql = "SELECT DISTINCT from_user_id FROM messages WHERE to_user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$senders = $stmt->fetchAll();


// Função para buscar mensagens de um usuário específico
function getMessages($from_user_id, $to_user_id, $pdo) {
    $sql = "SELECT messages.*, users.nome, users.foto, messages.timestamp 
            FROM messages 
            JOIN users ON messages.from_user_id = users.id 
            WHERE (from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?) 
            ORDER BY messages.timestamp ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$from_user_id, $to_user_id, $to_user_id, $from_user_id]);
    return $stmt->fetchAll();
}


// Atualizar seguidores e seguindo
$sql_followers = "SELECT COUNT(*) FROM seguidores WHERE id_seguido = ?";
$stmt_followers = $pdo->prepare($sql_followers);
$stmt_followers->execute([$user_id]);
$followers_count = $stmt_followers->fetchColumn();

$sql_following = "SELECT COUNT(*) FROM seguidores WHERE id_seguidor = ?";
$stmt_following = $pdo->prepare($sql_following);
$stmt_following->execute([$user_id]);
$following_count = $stmt_following->fetchColumn();


// Enviar mensagem
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $to_user_id = $_POST['to_user_id'];
    $from_user_id = $user_id;
    if (!empty($message)) {
        $sql = "INSERT INTO messages (from_user_id, to_user_id, message, timestamp) VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
    $stmt->execute([$from_user_id, $to_user_id, $message]);

    header("Location: home.php");
    }
}


// Pesquisa de usuários
$search_query = $_GET['search'] ?? '';
$users = [];
if (!empty($search_query)) {
    $sql = "SELECT * FROM users WHERE nome LIKE ? AND id != ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search_query%", $user_id]);
    $users = $stmt->fetchAll();
}



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Work Easy</title>

    <link rel="stylesheet" href="css/home.css">

</head>
<body>
    <header>
        <nav>
            <a href="home.php">Home</a>
            <a href="feed.php">Feed</a>
            <a href="map.php">Mapa</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <main>

    <h1>Bem-vindo, <?php echo $user['nome']; ?>!</h1>
    <section class="user-profile">
    <div class="profile-container">
        <!-- Foto de Perfil -->
        <div class="profile-photo-container">
            <img src="uploads/<?php echo $user['foto'] ?: 'default.jpg'; ?>" alt="Foto de Perfil" class="profile-photo">
            <a href="edit_profile.php" class="edit-profile-btn">Editar Perfil</a>
        </div>
        <!-- Informações do Usuário -->
        <div class="profile-info">
            <h2 class="profile-name"><?php echo $user['nome']; ?></h2>
            <p class="profile-type">
                <?php 
                    if ($user['tipo'] === 'empregador') {
                        echo 'Empregador';
                    } elseif ($user['tipo'] === 'empregado') {
                        echo 'Empregado';
                    } else {
                        echo 'Procurando Emprego';
                    }
                ?>
            </p>
            <p><strong>Idade:</strong> <?php echo $user['idade']; ?></p>
            <p><strong>Cargo:</strong> <?php echo $user['cargo']; ?></p>
            <p><strong>Contato:</strong> <?php echo $user['contato']; ?></p>
        </div>
        <!-- Seguidores e Seguindo -->
        <div class="profile-stats">
        <p>
            
            <p><strong>Seguidores:</strong><a href="following_followers.php?id=<?php echo $user['id']; ?>"> <?php echo $followers_count; ?></a></p>
            <p><strong>Seguindo:</strong><a href="following_followers.php?id=<?php echo $user['id']; ?>"><?php echo $following_count; ?></a></p>
        </p>
        <p>
        </div>
    </div>
</section>

<!-- Botão de Pesquisa com Expansão -->
<div class="search-container">
    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Pesquisar usuários..." class="search-input">
        <button class="btn-busca" type="submit">🔍</button>
    </form>
</div>
<div class="users">
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $u): ?>
            <div class="user-card">
                <img src="uploads/<?php echo $u['foto'] ?: 'default.jpg'; ?>" alt="Foto de Perfil" class="user-photo">
                <div class="user-info">
                    <p class="user-name"><?php echo $u['nome']; ?></p>
                    <p class="user-type">
                        <?php 
                            if ($u['tipo'] === 'empregador') {
                                echo 'Empregador';
                            } elseif ($u['tipo'] === 'empregado') {
                                echo 'Empregado';
                            } else {
                                echo 'Procurando Emprego';
                            }
                        ?>
                    </p>
                    <a href="profile.php?id=<?php echo $u['id']; ?>" class="view-more-btn">Ver Mais</a>
                    <!-- Botão de seguir/desseguir -->
                    <?php
                    $query = $pdo->prepare("SELECT * FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?");
                    $query->execute([$_SESSION['user_id'], $u['id']]);

                    if ($query->rowCount() > 0): ?>
                        <form method="POST" action="follow_action.php">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <button type="submit" class="btn-unfollow">Seguindo</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="follow_action.php">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <button type="submit" class="btn-follow">Seguir</button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-users-found">Nenhum usuário encontrado.</p>
    <?php endif; ?>
</div>

<!-- Janela de Chat -->
<div class="chat-container" id="chat-container">
    <button id="minimize-button" onclick="toggleChat()">-</button>
    <h3>Mensagens</h3>
    <div class="user-list">
        <?php foreach ($senders as $sender): ?>
            <?php
                $sender_id = $sender['from_user_id'];
                $sender_sql = "SELECT nome, foto FROM users WHERE id = ?";
                $stmt = $pdo->prepare($sender_sql);
                $stmt->execute([$sender_id]);
                $sender_data = $stmt->fetch();
                $sender_name = $sender_data['nome'];
                $sender_photo = $sender_data['foto'] ?: 'default.jpg'; // Foto padrão caso não tenha
            ?>
            <div class="user-item" onclick="openMessageWindow(<?php echo $sender_id; ?>)">
                <img src="uploads/<?php echo $sender_photo; ?>" alt="Foto do usuário" class="user-photo">
                <span class="user-name"><?php echo $sender_name; ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Janela de Envio de Mensagem -->
<div class="message-window" id="message-window">
    <button class="close-btn" onclick="closeMessageWindow()">X</button>
    <div class="message-list" id="message-list"></div>
    <form method="POST" class="message-input">
        <input type="text" name="message" placeholder="Digite sua mensagem" required>
        <input type="hidden" name="to_user_id" id="to_user_id">
        <button type="submit">Enviar</button>
    </form>
    
</div>


<footer>
    <p>&copy; 2025 Trabalhe Fácil. Todos os direitos reservados.</p>
</footer>

<script src="js/home.js"></script>




</body>
</html>
