<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Define o número de posts por página
$posts_por_pagina = 5;  // Exibe 5 posts por vez

// Obtém o número da página atual
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_atual - 1) * $posts_por_pagina;

// Se você está mantendo os posts carregados em uma variável no JavaScript, você pode passá-los para o PHP
// Para buscar os posts mais antigos, você pode verificar a data de postagem

// Verifica se há um ID de post inicial para pegar os mais antigos
$ultimo_post_id = isset($_GET['ultimo_post_id']) ? (int)$_GET['ultimo_post_id'] : 0;

// Consulta para pegar as publicações mais antigas, com base no último post exibido
$sql_posts = "SELECT p.id, p.conteudo, p.data_postagem, p.id_usuario, u.nome, u.foto 
FROM posts p 
JOIN users u ON p.id_usuario = u.id 
WHERE p.id < :ultimo_post_id
ORDER BY p.data_postagem DESC
LIMIT :inicio, :posts_por_pagina";

$stmt_posts = $pdo->prepare($sql_posts);
$stmt_posts->bindParam(':ultimo_post_id', $ultimo_post_id, PDO::PARAM_INT);
$stmt_posts->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt_posts->bindParam(':posts_por_pagina', $posts_por_pagina, PDO::PARAM_INT);
$stmt_posts->execute();
$posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);

// Retorna os posts em formato JSON
echo json_encode($posts);
?>
