<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$followed_user_id = $_POST['user_id'];

// Verificar se o usuário está seguindo o outro
$query = $pdo->prepare("SELECT * FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?");
$query->execute([$user_id, $followed_user_id]);

if ($query->rowCount() > 0) {
    
    $deleteQuery = $pdo->prepare("DELETE FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?");
    $deleteQuery->execute([$user_id, $followed_user_id]);

    // Atualizar o contador de seguidores e seguindo
    $updateFollowerCount = $pdo->prepare("UPDATE users SET seguidores = seguidores - 1 WHERE id = ?");
    $updateFollowerCount->execute([$followed_user_id]);

    $updateFollowingCount = $pdo->prepare("UPDATE users SET seguindo = seguindo - 1 WHERE id = ?");
    $updateFollowingCount->execute([$user_id]);
} else {
    // O usuário não está seguindo, então vamos seguir
    $insertQuery = $pdo->prepare("INSERT INTO seguidores (id_seguidor, id_seguido) VALUES (?, ?)");
    $insertQuery->execute([$user_id, $followed_user_id]);

    // Atualizar o contador de seguidores e seguindo
    $updateFollowerCount = $pdo->prepare("UPDATE users SET seguidores = seguidores + 1 WHERE id = ?");
    $updateFollowerCount->execute([$followed_user_id]);

    $updateFollowingCount = $pdo->prepare("UPDATE users SET seguindo = seguindo + 1 WHERE id = ?");
    $updateFollowingCount->execute([$user_id]);
}

header("Location: home.php");
exit;
?>
