<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Buscar usuários que o usuário está seguindo
$sql_following = "SELECT users.id, users.nome, users.foto FROM seguidores 
                  JOIN users ON seguidores.id_seguido = users.id 
                  WHERE seguidores.id_seguidor = ?";
$stmt_following = $pdo->prepare($sql_following);
$stmt_following->execute([$user_id]);
$following = $stmt_following->fetchAll();

// Buscar seguidores do usuário
$sql_followers = "SELECT users.id, users.nome, users.foto FROM seguidores 
                  JOIN users ON seguidores.id_seguidor = users.id 
                  WHERE seguidores.id_seguido = ?";
$stmt_followers = $pdo->prepare($sql_followers);
$stmt_followers->execute([$user_id]);
$followers = $stmt_followers->fetchAll();

// Verificar se a ação de "deixar de seguir" foi chamada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'unfollow') {
    $follower_id = $_POST['follower_id'];

    // Remover a relação de seguir
    $sql_unfollow = "DELETE FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?";
    $stmt_unfollow = $pdo->prepare($sql_unfollow);
    $stmt_unfollow->execute([$user_id, $follower_id]);

    // Atualizar o número de seguidores/seguindo após a remoção
    $sql_update_count = "UPDATE users SET seguidores = (SELECT COUNT(*) FROM seguidores WHERE id_seguido = ?) WHERE id = ?";
    $stmt_update_count = $pdo->prepare($sql_update_count);
    $stmt_update_count->execute([$follower_id, $follower_id]);

    // Redirecionar de volta para a página
    header("Location: following_followers.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguindo e Seguidores - Work Easy</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .tabs {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .tabs button {
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .tabs button:hover {
            background-color: #45a049;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .user-card {
            display: flex;
            align-items: center;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .user-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #4CAF50;
        }

        .user-info {
            flex-grow: 1;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .view-more-btn {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .view-more-btn:hover {
            color: #45a049;
        }

        .btn-unfollow {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-unfollow:hover {
            background-color: #d32f2f;
        }
    </style>
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
        <h1>Seguindo e Seguidores</h1>

        <div class="tabs">
            <button id="tab-following" onclick="switchTab('following')">Seguindo</button>
            <button id="tab-followers" onclick="switchTab('followers')">Seguidores</button>
        </div>

        <div id="following" class="tab-content">
            <h2>Seguindo</h2>
            <div class="following">
                <?php if (!empty($following)): ?>
                    <?php foreach ($following as $f): ?>
                        <div class="user-card">
                            <img src="uploads/<?php echo $f['foto'] ?: 'default.jpg'; ?>" alt="Foto de Perfil" class="user-photo">
                            <div class="user-info">
                                <p class="user-name"><?php echo $f['nome']; ?></p>
                                <a href="profile.php?id=<?php echo $f['id']; ?>" class="view-more-btn">Ver Perfil</a>
                                <form method="POST" action="following_followers.php" class="unfollow-form">
                                    <input type="hidden" name="follower_id" value="<?php echo $f['id']; ?>">
                                    <button type="submit" name="action" value="unfollow" class="btn-unfollow">Deixar de Seguir</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-users-found">Você não está seguindo ninguém ainda.</p>
                <?php endif; ?>
            </div>
        </div>

        <div id="followers" class="tab-content">
            <h2>Seguidores</h2>
            <div class="followers">
                <?php if (!empty($followers)): ?>
                    <?php foreach ($followers as $f): ?>
                        <div class="user-card">
                            <img src="uploads/<?php echo $f['foto'] ?: 'default.jpg'; ?>" alt="Foto de Perfil" class="user-photo">
                            <div class="user-info">
                                <p class="user-name"><?php echo $f['nome']; ?></p>
                                <a href="profile.php?id=<?php echo $f['id']; ?>" class="view-more-btn">Ver Perfil</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-users-found">Você não tem seguidores ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        // Função para alternar entre as abas
        function switchTab(tab) {
            document.getElementById('following').classList.remove('active');
            document.getElementById('followers').classList.remove('active');
            document.getElementById('tab-following').style.backgroundColor = '#4CAF50';
            document.getElementById('tab-followers').style.backgroundColor = '#4CAF50';

            if (tab === 'following') {
                document.getElementById('following').classList.add('active');
                document.getElementById('tab-following').style.backgroundColor = '#45a049';
            } else if (tab === 'followers') {
                document.getElementById('followers').classList.add('active');
                document.getElementById('tab-followers').style.backgroundColor = '#45a049';
            }
        }

        // Inicializar a primeira aba como visível
        switchTab('following');
    </script>
</body>
</html>
