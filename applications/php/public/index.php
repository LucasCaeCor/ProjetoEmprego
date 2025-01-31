<?php
// Inicia a sessão
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['user_id'])) {
    // Se estiver logado, redireciona para a home
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabalhe Fácil - Página Inicial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a   class="fa fa-briefcase" href="index.php">Trabalhe Fácil</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Página Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Cadastre-se</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


    <main>
        <section class="intro">
            <h1>Bem-vindo ao Trabalhe Fácil!</h1>
            <p>Conecte-se com empregadores e empregados de forma simples e prática. Faça login ou cadastre-se para começar.</p>

        </section>

        <section class="cards">
            <div class="card">
                <h3>Vantagens</h3>
                <p>Encontre oportunidades de trabalho de maneira rápida e fácil.</p>
            </div>
            <div class="card">
                <h3>Plataforma Segura</h3>
                <p>Seu cadastro e dados são protegidos por tecnologias avançadas.</p>
            </div>
            <div class="card">
                <h3>Suporte 24/7</h3>
                <p>Nosso time de suporte está disponível a qualquer hora para ajudar.</p>
            </div>
        </section>

        <section class="about">
            <h2>Sobre o Trabalhe Fácil</h2>
            <p>O Trabalhe Fácil é uma plataforma que conecta empregadores e trabalhadores de forma ágil e segura. Nosso objetivo é facilitar a contratação e garantir uma experiência sem complicações para todos os usuários.</p>
        </section>
    </main>


    <footer>
        <p>&copy; 2025 Trabalhe Fácil. Todos os direitos reservados.</p>
    </footer>

    <style>


    /* Estilos para a página de login e cadastro */
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #2f3b52, #1a1e29);
        color: #f4f4f9;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        overflow: hidden; 
    }

    header {
        background: linear-gradient(135deg,rgb(170, 93, 10), #388e3c); 
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    nav a {
        color: white;
        margin: 0 15px;
        text-decoration: none;
        font-size: 18px;
        transition: color 0.3s, transform 0.3s;
    }

    nav a:hover {
        color:rgb(15, 15, 156); 
        transform: scale(1.1);
    }

    .intro {
        text-align: center;
        padding: 50px;
        background: linear-gradient(135deg,rgb(4, 37, 44),rgb(54, 68, 54));
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        flex-grow: 1; 
    }

    .cta-buttons {
        margin-top: 30px;
    }

    .cta-button {
        background: linear-gradient(135deg, #4CAF50, #388e3c); 
        color: white;
        padding: 15px 30px;
        font-size: 18px;
        text-decoration: none;
        border-radius: 5px;
        margin: 0 10px;
        transition: background 0.3s, transform 0.3s;
    }

    .cta-button:hover {
        background: linear-gradient(135deg, #388e3c, #4CAF50); 
        transform: scale(1.1);
    }

    .cards {
        display: flex;
        justify-content: space-around;
        margin: 40px 0;
    }

    .card {
        background: #1a1e29;
        color: white;
        padding: 20px;
        width: 25%;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        transition: transform 0.3s ease, background-color 0.3s;
    }

    .card:hover {
        transform: translateY(-10px);
        background: #2f3b52;
    }

    .about {
        text-align: center;
        padding: 50px;
        background: linear-gradient(135deg,rgb(4, 37, 44),rgb(54, 68, 54));
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    footer {
        text-align: center;
        background-color: #1a1e29;
        color: white;
        padding: 20px;
        width: 100%;
        margin-top: auto;
    }
</style>


</body>
</html>
