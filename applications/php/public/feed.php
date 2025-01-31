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
$sql_stories = "SELECT s.id, s.foto, s.descricao, u.nome 
FROM stories s 
JOIN users u ON s.id_usuario = u.id 
ORDER BY s.data_postagem DESC";
$stmt_stories = $pdo->prepare($sql_stories);
$stmt_stories->execute();
$stories = $stmt_stories->fetchAll();

// Busca publicações
$sql_posts = "SELECT p.id, p.conteudo, p.data_postagem, u.nome, u.foto 
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

                    <!-- Botão de excluir história -->
                    <form action="delete_story.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta história?');">
                        <input type="hidden" name="story_id" value="<?php echo $story['id']; ?>">
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
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
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($post['conteudo'])); ?></p>
                        <p><small><?php echo date("d/m/Y H:i", strtotime($post['data_postagem'])); ?></small></p>

                        <!--  DIV Reações -->
                        <div class="reactions">
                            <?php
                            // Verifica se o usuário já reagiu a essa postagem
                            $sql_reactions = "SELECT * FROM reactions WHERE id_post = :id_post AND id_usuario = :id_usuario";
                            $stmt_reactions = $pdo->prepare($sql_reactions);
                            $stmt_reactions->execute([':id_post' => $post['id'], ':id_usuario' => $_SESSION['user_id']]);
                            $user_reaction = $stmt_reactions->fetch();
                            ?>

                            <button class="like" data-post-id="<?php echo $post['id']; ?>" data-reaction="like" <?php echo ($user_reaction['tipo'] === 'like') ? 'disabled' : ''; ?>>Curtir</button>
                            <button class="love" data-post-id="<?php echo $post['id']; ?>" data-reaction="love" <?php echo ($user_reaction['tipo'] === 'love') ? 'disabled' : ''; ?>>Amar</button>
                            <button class="haha" data-post-id="<?php echo $post['id']; ?>" data-reaction="haha" <?php echo ($user_reaction['tipo'] === 'haha') ? 'disabled' : ''; ?>>Haha</button>
                        </div>

                        <!-- Comentários -->
                        <div class="comments">
                            <h4>Comentários</h4>
                            <!-- Exemplo de comentários -->
                            <div class="comment">
                                <strong>João:</strong> Ótima postagem!
                            </div>
                            <div class="comment">
                                <strong>Ana:</strong> Muito interessante!
                            </div>

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
            alert(data); 
            location.reload(); 
        });
    });
});

    </script>
</body>
</html>
