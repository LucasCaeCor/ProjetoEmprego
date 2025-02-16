<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Busca informações do usuário logado
$sql = "SELECT  foto FROM users  WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Busca histórias
$sql = "SELECT stories.id, stories.descricao, stories.foto, users.id AS id_usuario, users.nome 
        FROM stories 
        JOIN users ON stories.id_usuario = users.id 
        ORDER BY stories.data_postagem DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca publicações
$sql_posts = "SELECT p.id, p.conteudo, p.data_postagem, p.id_usuario, u.nome, u.foto 
FROM posts p 
JOIN users u ON p.id_usuario = u.id 
ORDER BY p.data_postagem DESC";

$stmt_posts = $pdo->prepare($sql_posts);
$stmt_posts->execute();
$posts = $stmt_posts->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/feed.css">
</head>

<body>
    <header>
        <nav>
            <a href="home.php">Home</a>
            <a href="map.php">Mapa</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Feed</h1>
        
        <!-- Histórias -->
        <section class="stories">
            <h2>Histórias</h2>
            <!-- Container de histórias -->
            <div class="stories-container">
                <!-- Formulário para adicionar história -->
                <div class="story add-story">
                    <div class="add">
                        <img src="uploads/<?php echo $user['foto'] ?: 'default.jpg'; ?>" alt="Foto de Perfil" class="profile-photo">
                        <h3>Adicionar História</h3>
                        <form action="add_story.php" method="POST" enctype="multipart/form-data">
                            <label for="upload" class="upload-button">
                                <span>+</span> Selecionar Mídia
                            </label>
                            <input type="file" id="upload" name="foto" accept="image/*" required hidden>
                            <button type="submit">Postar História</button>
                        </form>
                    </div>
                </div>
                <!-- Exibindo as histórias -->
                <?php if (!empty($stories)): ?>
                    <?php foreach ($stories as $story): ?>
                        <div class="story">
                            <img src="uploads/<?php echo htmlspecialchars($story['foto']); ?>" alt="História de <?php echo htmlspecialchars($story['nome']); ?>">
                            <p><strong><?php echo htmlspecialchars($story['nome']); ?></strong></p>
                            <p><?php echo nl2br(htmlspecialchars($story['descricao'])); ?></p>

                            <!-- Verificar se o usuário logado é o dono da história -->
                            <?php if ($story['id_usuario'] == $_SESSION['user_id']): ?>
                                <!-- Botão de excluir história -->
                                <form action="delete_story.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta história?');">
                                    <input type="hidden" name="story_id" value="<?php echo $story['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Sem histórias ainda.</p>
                <?php endif; ?>
            </div>
        </section>
        <!-- Publicações -->
        <section class="feed">
    <h2>Publicações</h2>

    <!-- Formulário para adicionar publicação -->
    <section class="add-post">
        <h2>Adicionar Publicação</h2>
        <form action="add_post.php" method="POST">
            <textarea name="conteudo" rows="4" placeholder="O que você está pensando?" required></textarea>
            <button type="submit">Publicar</button>
        </form>
    </section>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
            <div class="post-header">
                    <img src="uploads/<?php echo htmlspecialchars($post['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($post['nome']); ?>" class="profile-photo">
                    <h3><?php echo htmlspecialchars($post['nome']); ?></h3>

                    <!-- Dropdown de opções (somente para o dono da publicação) -->
                    <?php if ($post['id_usuario'] == $_SESSION['user_id']): ?>
                        <div class="dropdown">
                            <button class="dropbtn">&#x22EE;</button>
                            <div class="dropdown-content">
                                <a href="#" onclick="openEditModal(<?php echo $post['id']; ?>, '<?php echo addslashes($post['conteudo']); ?>')">Editar</a>
                                <a href="delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta publicação?');">Excluir</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <p><?php echo nl2br(htmlspecialchars($post['conteudo'])); ?></p>
                <p><small><?php echo date("d/m/Y H:i", strtotime($post['data_postagem'])); ?></small></p>

                <!-- DIV Reações -->
                <div class="reactions">
                <?php
                // Contabiliza as reações de cada tipo
                $sql_reactions_count = "SELECT tipo, COUNT(*) AS total FROM reactions WHERE id_post = :id_post GROUP BY tipo";
                $stmt_reactions_count = $pdo->prepare($sql_reactions_count);
                $stmt_reactions_count->execute([':id_post' => $post['id']]);
                $reactions_count = $stmt_reactions_count->fetchAll(PDO::FETCH_ASSOC);

                // Inicializa um array para contar as reações
                $reaction_counts = [
                    'like' => 0,
                    'love' => 0,
                    'haha' => 0
                ];

                // Preenche o array com as contagens de reações
                foreach ($reactions_count as $reaction) {
                    $reaction_counts[$reaction['tipo']] = $reaction['total'];
                }

                // Verifica a reação do usuário
                $sql_user_reaction = "SELECT tipo FROM reactions WHERE id_post = :id_post AND id_usuario = :id_usuario";
                $stmt_user_reaction = $pdo->prepare($sql_user_reaction);
                $stmt_user_reaction->execute([':id_post' => $post['id'], ':id_usuario' => $_SESSION['user_id']]);
                $user_reaction = $stmt_user_reaction->fetch();

                // Variáveis para controle de estado dos botões
                $like_disabled = (isset($user_reaction['tipo']) && $user_reaction['tipo'] === 'like') ? 'disabled' : '';
                $love_disabled = (isset($user_reaction['tipo']) && $user_reaction['tipo'] === 'love') ? 'disabled' : '';
                $haha_disabled = (isset($user_reaction['tipo']) && $user_reaction['tipo'] === 'haha') ? 'disabled' : '';
                ?>
                
                <!-- Exibe as figurinhas de reações -->
                <button class="reaction-button"  data-post-id="<?= $post['id']; ?>" data-reaction="like" <?= $like_disabled; ?>>
                <svg xmlns="http://www.w3.org/2000/svg" width="10%" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M313.4 32.9c26 5.2 42.9 30.5 37.7 56.5l-2.3 11.4c-5.3 26.7-15.1 52.1-28.8 75.2l144 0c26.5 0 48 21.5 48 48c0 18.5-10.5 34.6-25.9 42.6C497 275.4 504 288.9 504 304c0 23.4-16.8 42.9-38.9 47.1c4.4 7.3 6.9 15.8 6.9 24.9c0 21.3-13.9 39.4-33.1 45.6c.7 3.3 1.1 6.8 1.1 10.4c0 26.5-21.5 48-48 48l-97.5 0c-19 0-37.5-5.6-53.3-16.1l-38.5-25.7C176 420.4 160 390.4 160 358.3l0-38.3 0-48 0-24.9c0-29.2 13.3-56.7 36-75l7.4-5.9c26.5-21.2 44.6-51 51.2-84.2l2.3-11.4c5.2-26 30.5-42.9 56.5-37.7zM32 192l64 0c17.7 0 32 14.3 32 32l0 224c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32L0 224c0-17.7 14.3-32 32-32z"/></svg><?= $reaction_counts['like']; ?>
                </button>
                <button class="reaction-button"   data-post-id="<?= $post['id']; ?>" data-reaction="love" <?= $love_disabled; ?>>
                <svg xmlns="http://www.w3.org/2000/svg" width="10%" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/></svg> <?= $reaction_counts['love']; ?>
    
                </button>
                <button class="reaction-button"  data-post-id="<?= $post['id']; ?>" data-reaction="haha" <?= $haha_disabled; ?>>
                <svg xmlns="http://www.w3.org/2000/svg" width="10%" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM96.8 314.1c-3.8-13.7 7.4-26.1 21.6-26.1l275.2 0c14.2 0 25.5 12.4 21.6 26.1C396.2 382 332.1 432 256 432s-140.2-50-159.2-117.9zm36.7-199.4l89.9 47.9c10.7 5.7 10.7 21.1 0 26.8l-89.9 47.9c-7.9 4.2-17.5-1.5-17.5-10.5c0-2.8 1-5.5 2.8-7.6l36-43.2-36-43.2c-1.8-2.1-2.8-4.8-2.8-7.6c0-9 9.6-14.7 17.5-10.5zM396 125.1c0 2.8-1 5.5-2.8 7.6l-36 43.2 36 43.2c1.8 2.1 2.8 4.8 2.8 7.6c0 9-9.6 14.7-17.5 10.5l-89.9-47.9c-10.7-5.7-10.7-21.1 0-26.8l89.9-47.9c7.9-4.2 17.5 1.5 17.5 10.5z"/></svg><?= $reaction_counts['haha']; ?>
                </button>
            </div>



                <!-- Comentários -->
                <div class="comments">
                    <h4>Comentários</h4>
                    <!-- Exibindo os comentários -->
                    <?php 
                    $sql_comments = "SELECT c.id, c.comentario, c.id_usuario AS id_comment_owner, u.nome 
                                    FROM comments c 
                                    JOIN users u ON c.id_usuario = u.id 
                                    WHERE c.id_post = :id_post";
                    $stmt_comments = $pdo->prepare($sql_comments);
                    $stmt_comments->execute([':id_post' => $post['id']]);
                    $comments = $stmt_comments->fetchAll();

                    if (!empty($comments)): 
                        foreach ($comments as $comment): ?>
                            <div class="comment" id="comment-<?php echo $comment['id']; ?>">
                                <strong><?php echo htmlspecialchars($comment['nome']); ?>:</strong>
                                <p><?php echo nl2br(htmlspecialchars($comment['comentario'])); ?></p>

                                <!-- Dropdown para editar ou excluir o comentário -->
                                <?php
                                // Verifica se o usuário logado é o dono do comentário ou da publicação
                                $is_owner = ($_SESSION['user_id'] == $comment['id_comment_owner']);
                                if ($is_owner): ?>
                                    <div class="dropdown">
                                        <button class="dropdown-btn">Opções</button>
                                        <div class="dropdown-content">
                                            <!-- Editar Comentário -->
                                            <a href="#" onclick="openEditCommentModal(<?php echo $comment['id']; ?>, '<?php echo addslashes(htmlspecialchars($comment['comentario'])); ?>')">Editar</a>

                                            <!-- Excluir Comentário -->
                                            <a href="excluir_comentario.php?id_comentario=<?php echo $comment['id']; ?>&id_post=<?php echo $post['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">Excluir</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; 
                    else: ?>
                        <p>Sem comentários ainda.</p>
                    <?php endif; ?>

                    <!-- Formulário para novo comentário -->
                    <form action="comment.php" method="POST" class="comment-form">
                        <input type="text" name="comentario" placeholder="Adicionar comentário..." required>
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <button type="submit">Comentar</button>
                    </form>
                </div>
                <!-- Modal de Edição de Comentário -->
                <div id="editCommentModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeEditCommentModal()">&times;</span>
                        <h2>Editar Comentário</h2>
                        <form id="editCommentForm" method="POST">
                            <textarea id="editCommentContent" name="comentario" rows="4" required></textarea>
                            <input type="hidden" name="comentario_id" id="commentId">
                            <input type="hidden" name="post_id" id="postId">
                            <button type="submit">Salvar Alterações</button>
                        </form>
                    </div>
                </div>



                            <!-- Modal de Edição -->
                <div id="editPostModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeEditModal()">&times;</span>
                        <h2>Editar Publicação</h2>
                        <form id="editPostForm" method="POST">
                            <textarea id="editPostContent" name="conteudo" rows="4" required></textarea>
                            <input type="hidden" name="post_id" id="postId">
                            <button type="submit">Salvar Alterações</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Sem publicações ainda.</p>
    <?php endif; ?>

<!-- Script para abrir e fechar o Modal -->

</section>
</main>

<script src="js/feed.js"></script>
</body>
</html>
