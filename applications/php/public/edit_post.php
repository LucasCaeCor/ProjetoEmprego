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

// Busca a postagem no banco de dados
$sql = "SELECT * FROM posts WHERE id = ? AND id_usuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id, $_SESSION['user_id']]);
$post = $stmt->fetch();

if (!$post) {
    echo "Post não encontrado ou você não tem permissão para editá-lo.";
    exit;
}

// Atualizar post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conteudo = $_POST['conteudo'];

    if (!empty($conteudo)) {
        $sql_update = "UPDATE posts SET conteudo = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        if ($stmt_update->execute([$conteudo, $post_id])) {
            header("Location: feed.php");
            exit;
        } else {
            echo "Erro ao atualizar a postagem.";
        }
    } else {
        echo "O conteúdo não pode estar vazio.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Postagem</title>
</head>
<body>
    <h2>Editar Postagem</h2>
    <form action="" method="POST">
        <textarea name="conteudo" rows="4" required><?php echo htmlspecialchars($post['conteudo']); ?></textarea>
        <button type="submit">Atualizar</button>
    </form>
    <a href="feed.php">Voltar</a>
</body>
</html>
