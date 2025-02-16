<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

// Verifique se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['conteudo'], $_POST['post_id'])) {
    $conteudo = $_POST['conteudo'];
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Atualizar no banco de dados
    $sql = "UPDATE posts SET conteudo = :conteudo WHERE id = :post_id AND id_usuario = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':conteudo' => $conteudo,
        ':post_id' => $post_id,
        ':user_id' => $user_id
    ]);

    echo json_encode(['success' => 'Post atualizado com sucesso']);
} else {
    echo json_encode(['error' => 'Dados não enviados corretamente']);
}
?>