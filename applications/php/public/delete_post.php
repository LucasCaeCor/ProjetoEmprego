<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: feed.php");
    exit;
}

$post_id = $_GET['id'];

// Verifica se o post pertence ao usuário logado
$sql = "SELECT * FROM posts WHERE id = ? AND id_usuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id, $_SESSION['user_id']]);
$post = $stmt->fetch();

if (!$post) {
    echo "Post não encontrado ou você não tem permissão para excluí-lo.";
    exit;
}

// Exclui as reações associadas ao post
$sql_reactions = "DELETE FROM reactions WHERE id_post = ?";
$stmt_reactions = $pdo->prepare($sql_reactions);
$stmt_reactions->execute([$post_id]);

// Exclui os comentários relacionados ao post
$sql_comments = "DELETE FROM comments WHERE id_post = :post_id";
$stmt_comments = $pdo->prepare($sql_comments);
$stmt_comments->execute([':post_id' => $post_id]);

// Agora, exclui o post
$sql_post = "DELETE FROM posts WHERE id = :post_id";
$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute([':post_id' => $post_id]);

header("Location: feed.php");
exit;
?>
