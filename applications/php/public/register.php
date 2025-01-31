<?php
include('config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    $sql = "INSERT INTO users (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $senha, $tipo]);

    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Work Easy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="fa fa-briefcase" href="index.php">Trabalhe Fácil</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="register.php"   >Cadastre-se</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<section class="register-container">
    <div class="form-wrapper">
        <h1 class="text-center">Cadastro</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Usuário</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="comum">Procurando Emprego</option>
                    <option value="empregador">Ofertando Emprego</option>
                    <option value="empregado">Já Empregado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
        <p class="text-center mt-3">Já tem uma conta? <a href="login.php">Faça login</a>.</p>
    </div>
</section>

<footer>
    <p>&copy; 2025 Trabalhe Fácil. Todos os direitos reservados.</p>
</footer>

<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg,rgb(56, 58, 63),rgb(5, 14, 40));
        color: #f4f4f9;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    header {
        width: 100%;
        background: linear-gradient(135deg, #aa5d0a, #388e3c);
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    nav a {
        color: white;
        margin: 0 15px;
        text-decoration: none;
        font-size: 18px;
    }

    .register-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 80px);
        width: 100%;
    }

    .form-wrapper {
        background-color: rgba(21, 20, 54, 0.8);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        max-width: 400px;
        width: 100%;
    }

    .form-wrapper h1 {
        color: #ffc107;
        margin-bottom: 20px;
        text-align: center;
    }

    .form-wrapper .form-control {
        background-color: black;
        color:rgb(255, 255, 255);
        border: 1px solid rgb(255, 255, 255);
        
    }

    .form-wrapper .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 5px rgba(255, 193, 7, 0.8);
    }

    .form-wrapper .btn-primary {
        background: linear-gradient(135deg, #aa5d0a, #388e3c);
        border-color:rgb(15, 13, 8);
        color:rgb(215, 215, 215);
    }

    .form-wrapper .btn-primary:hover {
        background-color: #e0a800;
        border-color: #d39e00;
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
