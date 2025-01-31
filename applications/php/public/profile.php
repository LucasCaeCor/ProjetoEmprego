<?php
include('config/db.php');
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$profile_id = $_GET['id'] ?? null;

// Verifica se o ID do perfil foi fornecido
if (!$profile_id) {
    header("Location: home.php");
    exit;
}

// Busca informações do usuário
$sql = "SELECT nome, idade, cargo, contato, foto, descricao FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$profile_id]);
$profile = $stmt->fetch();

if (!$profile) {
    header("Location: home.php");
    exit;
}

// Conta o número de seguidores
$sql_followers = "SELECT COUNT(*) FROM seguidores WHERE id_seguido = ?";
$stmt_followers = $pdo->prepare($sql_followers);
$stmt_followers->execute([$profile_id]);
$followers_count = $stmt_followers->fetchColumn();

// Conta o número de seguidos
$sql_following = "SELECT COUNT(*) FROM seguidores WHERE id_seguidor = ?";
$stmt_following = $pdo->prepare($sql_following);
$stmt_following->execute([$profile_id]);
$following_count = $stmt_following->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($profile['nome']); ?></title>
    <link rel="stylesheet" href="css/style.css">
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
        <div class="profile-container">
            <div class="profile-header">
                <img class="profile-photo" src="uploads/<?php echo htmlspecialchars($profile['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($profile['nome']); ?>">
                <h1 class="profile-name"><?php echo htmlspecialchars($profile['nome']); ?></h1>
            </div>
            <div class="profile-info">
                <p><strong>Idade:</strong> <?php echo htmlspecialchars($profile['idade']); ?></p>
                <p><strong>Cargo:</strong> <?php echo htmlspecialchars($profile['cargo']); ?></p>
                <p><strong>Contato:</strong> <?php echo htmlspecialchars($profile['contato']); ?></p>
                <p><strong>Seguidores:</strong> <?php echo htmlspecialchars($followers_count); ?></p>
                <p><strong>Seguindo:</strong> <?php echo htmlspecialchars($following_count); ?></p>
                <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($profile['descricao'])); ?></p>
            </div>
            <a href="direct.php?to=<?php echo $profile_id; ?>" class="button">Enviar Mensagem</a>
            <a href="home.php" class="back-button">Voltar</a>
        </div>
    </main>
</body>
</html>
