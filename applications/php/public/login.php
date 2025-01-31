<?php
include('config/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: home.php");
        exit;
    } else {
        $error = "E-mail ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Work Easy</title>
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
                        <a class="nav-link" aria-current="page" hidden href="index.php">Página Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php"  >Cadastre-se</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


<section class="login-container">
    <div class="form-wrapper">
        <h1 class="text-center">Login</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Endereço de E-mail</label>
                <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1"  class="form-label">Senha</label>
                <input type="password" class="form-control" name="senha" id="exampleInputPassword1" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Lembre-se de mim</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <p class="text-center mt-3">Não tem uma conta? <a href="register.php">Cadastre-se</a>.</p>
    </div>
</section>





<footer>
    <p>&copy; 2025 Trabalhe Fácil. Todos os direitos reservados.</p>
</footer>

<style>
        /* Estilos para a página de login e cadastro */
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg,rgb(56, 58, 63),rgb(5, 14, 40));
    color: #f4f4f9;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction:column;
    justify-content: center;
    align-items: center;}
/* Estilo principal da página */

.login-container {
    display: flex;
    flex-direction:column;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 80px); 
    width: 100%;
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
    transition: color 0.3s, transform 0.3s;
}

nav a:hover {
    color:rgb(15, 15, 156);
    transform: scale(1.1);
}

.card {
    display:flex;
    align-items: center;
    flex-direction: column;
    text-align: center;
    padding: 50px;
    background: linear-gradient(135deg,rgb(4, 37, 44),rgb(54, 68, 54));
    margin-top: 20px;
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
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100%;
    background: transparent;
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
    background-color: transparent;
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

.error {
    color: #dc3545;
    text-align: center;
    font-size: 0.9rem;
}

.form-wrapper a {
    color: #ffc107;
}

.form-wrapper a:hover {
    text-decoration: underline;
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
