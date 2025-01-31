<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conteudo = $_POST['conteudo'];
    $id_usuario = $_SESSION['user_id'];

    $sql = "INSERT INTO posts (conteudo, id_usuario, data_postagem) VALUES (:conteudo, :id_usuario, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':conteudo' => $conteudo,
        ':id_usuario' => $id_usuario
    ]);

    header("Location: feed.php");
    exit;
}
