<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id_comentario']) && isset($_GET['id_post'])) {
    $id_comentario = $_GET['id_comentario'];
    $id_post = $_GET['id_post'];

    // Verifica se o comentário existe e pertence ao usuário
    $sql_check = "SELECT id_usuario FROM comments WHERE id = :id_comentario AND id_post = :id_post";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':id_comentario' => $id_comentario, ':id_post' => $id_post]);
    $comment = $stmt_check->fetch();



    if (!$comment) {
        echo "Comentário não encontrado ou não autorizado.";
        exit;
    }

    // Verifica se o usuário logado é o dono do comentário
    if ($_SESSION['user_id'] != $comment['id_usuario']) {
        echo "Você não tem permissão para editar este comentário.";
        exit;
    }
    

    // Se o usuário for o dono, vamos buscar o comentário para edição
    $sql = "SELECT comentario FROM comments WHERE id = :id_comentario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_comentario' => $id_comentario]);
    $comentario = $stmt->fetch();

    // Verifica se o comentário foi encontrado
    if (!$comentario) {
        echo "Comentário não encontrado.";
        exit;
    }
} else {
    echo "Comentário ou publicação não encontrado.";
    exit;
}

// Editar comentário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_comentario = $_POST['comentario'];

    // Atualiza o comentário no banco
    $sql_update = "UPDATE comments SET comentario = :comentario WHERE id = :id_comentario";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([':comentario' => $novo_comentario, ':id_comentario' => $id_comentario]);

    // Redireciona para o feed após editar
    header("Location: feed.php");
    exit;
}
?>

<!-- Formulário de Edição -->
<form action="editar_comentario.php?id_comentario=<?php echo $id_comentario; ?>&id_post=<?php echo $id_post; ?>" method="POST">
    <textarea name="comentario" required><?php echo htmlspecialchars($comentario['comentario']); ?></textarea>
    <button type="submit">Salvar Alterações</button>
</form>
