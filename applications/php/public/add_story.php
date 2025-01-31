<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $id_usuario = $_SESSION['user_id'];

    // Processar a imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];
        $foto_nome = time() . "_" . $foto['name'];
        $foto_tmp = $foto['tmp_name'];
        $foto_destino = 'uploads/' . $foto_nome;

        move_uploaded_file($foto_tmp, $foto_destino);
    }

    $sql = "INSERT INTO stories (descricao, foto, id_usuario, data_postagem) VALUES (:descricao, :foto, :id_usuario, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':descricao' => $descricao,
        ':foto' => $foto_nome,
        ':id_usuario' => $id_usuario
    ]);

    header("Location: feed.php");
    exit;
}
