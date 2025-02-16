<?php
include('config/db.php');
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuário não logado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comentario'], $_POST['comentario_id'], $_POST['post_id'])) {
    $novo_comentario = $_POST['comentario'];
    $comentario_id = $_POST['comentario_id'];
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Verifica se o comentário pertence ao usuário
    $sql_check = "SELECT id_usuario FROM comments WHERE id = :id_comentario";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':id_comentario' => $comentario_id]);
    $comment = $stmt_check->fetch();

    if ($comment['id_usuario'] != $user_id) {
        echo json_encode(['error' => 'Você não tem permissão para editar este comentário']);
        exit;
    }

    // Atualiza o comentário no banco de dados
    $sql_update = "UPDATE comments SET comentario = :comentario WHERE id = :id_comentario";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([':comentario' => $novo_comentario, ':id_comentario' => $comentario_id]);

    // Retorna o novo comentário em formato JSON
    echo json_encode(['success' => true, 'comentario' => $novo_comentario, 'comentario_id' => $comentario_id]);
    exit;
} else {
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}
?>
