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

    // Depuração para verificar valores
    var_dump($id_comentario, $id_post);

    // Verifica se o comentário existe e pertence ao usuário
    $sql_check = "SELECT id_usuario FROM comments WHERE id = :id_comentario AND id_post = :id_post";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':id_comentario' => $id_comentario, ':id_post' => $id_post]);
    $comment = $stmt_check->fetch();

    if (!$comment) {
        echo "Comentário não encontrado ou não autorizado.";
        exit;
    }

    // Depuração para verificar o retorno da consulta
    var_dump($comment);

    // Verifica se o usuário logado é o dono do comentário ou dono do post
    if ($_SESSION['user_id'] != $comment['id_usuario']) {
        echo "Você não tem permissão para excluir este comentário.";
        exit;
    }

    // Excluir comentário
    $sql_delete = "DELETE FROM comments WHERE id = :id_comentario";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':id_comentario' => $id_comentario]);

    // Redireciona após excluir
    header("Location: feed.php");
    exit;
} else {
    echo "Comentário ou publicação não encontrado.";
    exit;
}
