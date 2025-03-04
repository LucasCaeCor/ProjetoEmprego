<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validando as entradas
    if (empty($_POST['id_post']) || empty($_POST['tipo'])) {
        // Redireciona em caso de erro
        header("Location: feed.php?error=1");
        exit;
    }

    $id_post = $_POST['id_post'];
    $id_usuario = $_SESSION['user_id'];
    $tipo_reacao = $_POST['tipo'];

    try {
        // Verifica se o usuário já reagiu a essa postagem
        $sql_check = "SELECT * FROM reactions WHERE id_post = :id_post AND id_usuario = :id_usuario";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':id_post' => $id_post, ':id_usuario' => $id_usuario]);
        $reaction = $stmt_check->fetch();

        if ($reaction) {
            // Se já houver uma reação, atualiza a reação
            $sql_update = "UPDATE reactions SET tipo = :tipo WHERE id_post = :id_post AND id_usuario = :id_usuario";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([':tipo' => $tipo_reacao, ':id_post' => $id_post, ':id_usuario' => $id_usuario]);
        } else {
            // Se não houver reação, insere uma nova
            $sql_insert = "INSERT INTO reactions (id_post, id_usuario, tipo) VALUES (:id_post, :id_usuario, :tipo)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([':id_post' => $id_post, ':id_usuario' => $id_usuario, ':tipo' => $tipo_reacao]);
        }

        // Redireciona após o sucesso
        header("Location: feed.php?success=1");
        exit;

    } catch (PDOException $e) {
        // Redireciona em caso de erro de banco de dados
        header("Location: feed.php?error=2");
        exit;
    }
}
?>
