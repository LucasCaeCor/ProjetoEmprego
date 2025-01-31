<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Busca informações do usuário logado
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Atualiza as informações do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $cargo = $_POST['cargo'];
    $contato = $_POST['contato'];
    $foto = $_FILES['foto']['name'];
    $tipo = $_POST['tipo']; 
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $cidade = $_POST['cidade'];
    $descricao = $_POST['descricao'];

    if ($foto) {
        // Lida com o upload de foto
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $foto = basename($foto);
        } else {
            $foto = $user['foto'];
        }
    } else {
        $foto = $user['foto'];
    }

    // Atualiza as informações no banco de dados, incluindo cidade, serviço, latitude, longitude e tipo
    $sql = "UPDATE users SET nome = ?, idade = ?, cargo = ?, contato = ?, foto = ?, tipo = ?, latitude = ?, longitude = ?, cidade = ?, descricao = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $idade, $cargo, $contato, $foto, $tipo, $latitude, $longitude, $cidade, $descricao, $user_id]);


    header("Location: home.php");
    exit;




}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Work Easy</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <header>
        <nav>
            <a href="home.php">Home</a>
            <a href="feed.php">Feed</a>
            <a href="map.php">Mapa</a>
            <a href="logout.php">Sair</a>

            
        </nav>
    </header>
    <main>
    <h1>Editar Perfil</h1>
        <section class="formulario">

        <form method="POST" enctype="multipart/form-data">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?php echo $user['nome']; ?>" required>

            <label for="idade">Idade</label>
            <input type="number" name="idade" id="idade" value="<?php echo $user['idade']; ?>" required>

            <label for="cargo">Tipo de Serviço</label>
            <input type="text" name="cargo" id="cargo" value="<?php echo $user['cargo']; ?>" required>

            <label for="contato">Contato</label>
            <input type="text" name="contato" id="contato" value="<?php echo $user['contato']; ?>" required>

            <label for="foto">Foto de Perfil</label>
            <input type="file" name="foto" id="foto">

            <!-- Campo para selecionar tipo -->
            <label for="tipo">Tipo de Usuário</label>
            <select name="tipo" id="tipo" required>
                <option value="procurando emprego" <?php echo ($user['tipo'] == 'procurando emprego') ? 'selected' : ''; ?>>Procurando Emprego</option>
                <option value="empregador" <?php echo ($user['tipo'] == 'empregador') ? 'selected' : ''; ?>>Empregador</option>
                <option value="empregado" <?php echo ($user['tipo'] == 'empregado') ? 'selected' : ''; ?>>Empregado</option>
            </select>
    <br>

            <!-- Campo Descrição -->
            <label class="descricao" for="Descrição">Descrição</label>
            <textarea type="textarea" name="descricao" placeholder="..."  id="descricao"  value="<?php echo $user['descricao']; ?>" required></textarea>

            <br>
            


            <!-- Campos para latitude e longitude -->
            <label for="latitude">Latitude</label>
            <input type="text" name="latitude" id="latitude" value="<?php echo $user['latitude']; ?>" placeholder="Digite a latitude">

            <label for="longitude">Longitude</label>
            <input type="text" name="longitude" id="longitude" value="<?php echo $user['longitude']; ?>" placeholder="Digite a longitude">

            <!-- Campos para cidade e serviço -->
            <label for="cidade">Cidade</label>
            <input type="text" name="cidade" id="cidade" value="<?php echo $user['cidade']; ?>" required>



            <button type="submit">Salvar Alterações</button>
        </form>
        </section>

        <footer>
    <p>&copy; 2025 Trabalhe Fácil. Todos os direitos reservados.</p>
</footer>

    </main>
</body>
</html>

