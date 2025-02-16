<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['user_id'])) {
    $other_user_id = $_GET['user_id'];

    // Excluir apenas as mensagens enviadas pelo usuário logado para o outro usuário
    $sql = "DELETE FROM messages WHERE from_user_id = ? AND to_user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $other_user_id]);

    // Redirecionar de volta para a página inicial ou para a janela de chat
    header("Location: home.php"); // ou qualquer outra página relevante
    exit;
} else {
    // Se o ID do usuário não for fornecido
    echo "Erro: Nenhum usuário especificado.";
    exit;
}
?>
