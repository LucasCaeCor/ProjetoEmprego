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
</head>

<style>


    



.add-story {
    width: 100%;
    max-width: 500px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 10px;
}

.stories {
    width: 100%;
    padding: 20px;
    background-color: #f9f9f9;
}

.stories h2 {
    text-align: center;
    margin-bottom: 20px;
}

.stories-container {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding: 10px 0;
    scroll-behavior: smooth;
}

.stories-container::-webkit-scrollbar {
    height: 8px;
}

.stories-container::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
}

.story {
    flex: 0 0 200px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    text-align: center;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.story img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

.add-story {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    border: 2px dashed #ccc;
    background-color: #fafafa;
}

.add-story textarea {
    width: 100%;
    margin-bottom: 10px;
}

.add-story input[type="file"],
.add-story button {
    margin-top: 10px;
}


.post {
    background-color: #fff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.post-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.profile-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
    object-fit: cover;
}

.post-header h3 {
    font-size: 18px;
    color: #333;
}

.post p {
    color: #333;
    font-size: 16px;
}

.reactions {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

/* Ajustes para os botões de reações */
.reactions button {
    background-color: #f0f2f5;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
    margin-right: 10px;
}

.reactions button:hover {
    background-color: #e4e6eb;
}

.reactions button:disabled {
    background-color: #d1d3d8;
    cursor: not-allowed;
}

/* Estilo do menu dropdown */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #fff;
    min-width: 160px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border-radius: 5px;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown-content a {
    color: black;
    padding: 8px 12px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.post {
    margin: 20px 0;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.post-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.profile-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.post-header h3 {
    flex-grow: 1;
    margin-left: 10px;
}

.comment-form input[type="text"] {
    width: 80%;
    padding: 8px;
    border-radius: 5px;
    margin-right: 10px;
}

.comment-form button {
    padding: 8px 15px;
    border-radius: 5px;
    background-color: #4CAF50;
    color: white;
    border: none;
}

.comment-form button:hover {
    background-color: #45a049;
}

/* Botão "Publicar" */
form button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

form button[type="submit"]:hover {
    background-color: #45a049;
}


.comments {
    margin-top: 20px;
    background-color: #f7f7f7;
    padding: 10px;
    border-radius: 10px;
}

.comment {
    margin: 5px 0;
    font-size: 14px;
    color: #333;
}

.comment strong {
    font-weight: bold;
}

.comment-form input {
    width: 85%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-right: 10px;
}

.comment-form button {
    background-color: #1877f2;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
}

.comment-form button:hover {
    background-color: #165eab;
}

</style>
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
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>">Editar</a>
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
                        <div class="comment">
                            <strong><?php echo htmlspecialchars($comment['nome']); ?>:</strong>
                            <?php echo nl2br(htmlspecialchars($comment['comentario'])); ?>

                            <!-- Dropdown para editar ou excluir o comentário -->
                            <?php
                            // Verifica se o usuário logado é o dono do comentário ou da publicação
                            $is_owner = ($_SESSION['user_id'] == $comment['id_comment_owner']) || ($_SESSION['user_id'] == $post['id_usuario']);
                            if ($is_owner): ?>
                                <div class="dropdown">
                                    <button class="dropdown-btn">Opções</button>
                                    <div class="dropdown-content">
                                        <a href="editar_comentario.php?id_comentario=<?php echo $comment['id']; ?>&id_post=<?php echo $post['id']; ?>">Editar</a>
                                        <a href="excluir_comentario.php?id_comentario=<?php echo $comment['id']; ?>&id_post=<?php echo $post['id']; ?>">Excluir</a>
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


            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Sem publicações ainda.</p>
    <?php endif; ?>
</section>

<!-- Estilo para o dropdown -->
<style>
/* Estilos para o dropdown */
.comment .dropdown {
    position: relative;
    display: inline-block;
}

.comment .dropdown-btn {
    background-color: #f1f1f1;
    border: none;
    padding: 5px;
    cursor: pointer;
}

.comment .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 100px;
    z-index: 1;
}

.comment .dropdown:hover .dropdown-content {
    display: block;
}

.comment .dropdown-content a {
    padding: 8px;
    text-decoration: none;
    display: block;
    color: black;
}

.comment .dropdown-content a:hover {
    background-color: #ddd;
}

</style>

    </main>

    <script>
        
        document.querySelectorAll('.reactions button').forEach(button => {
    button.addEventListener('click', function() {
        const postId = this.getAttribute('data-post-id');
        const reactionType = this.getAttribute('data-reaction');

        fetch('add_reaction.php', {
            method: 'POST',
            body: new URLSearchParams({
                'id_post': postId,
                'tipo': reactionType
            })
        }).then(response => response.text()).then(data => {
            console.log(data);  // Aqui você pode verificar se a resposta está correta
            location.reload();  // Atualiza a página para refletir a nova reação
        });
    });
});


    </script>
</body>
</html>
