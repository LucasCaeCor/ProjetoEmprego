<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega os dados do formulário
    $comentario = $_POST['comentario'];
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Prepara a query para inserir o comentário no banco de dados
    $sql = "INSERT INTO comments (comentario, id_post, id_usuario) VALUES (:comentario, :id_post, :id_usuario)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':comentario' => $comentario, ':id_post' => $post_id, ':id_usuario' => $user_id]);

    // Redireciona de volta para a página do feed após inserir o comentário
    header("Location: feed.php");
    exit;
}
?>
