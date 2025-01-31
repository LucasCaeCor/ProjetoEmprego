<?php
include('config/db.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$from_user_id = $_SESSION['user_id'];
$to_user_id = $_GET['to'] ?? null;

// Verifica se o ID do destinatário foi fornecido
if (!$to_user_id) {
    header("Location: home.php");
    exit;
}

// Busca informações do destinatário
$sql = "SELECT nome, foto FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$to_user_id]);
$to_user = $stmt->fetch();

if (!$to_user) {
    header("Location: home.php");
    exit;
}

// Processa o envio da mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $sql = "INSERT INTO messages (from_user_id, to_user_id, message, timestamp) VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$from_user_id, $to_user_id, $message]);
    }
}

// Busca as mensagens trocadas entre os usuários
$sql = "SELECT m.message, m.timestamp, u.nome AS sender, u.foto AS sender_foto
        FROM messages m
        JOIN users u ON m.from_user_id = u.id
        WHERE (m.from_user_id = ? AND m.to_user_id = ?)
           OR (m.from_user_id = ? AND m.to_user_id = ?)
        ORDER BY m.timestamp ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$from_user_id, $to_user_id, $to_user_id, $from_user_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens com <?php echo htmlspecialchars($to_user['nome']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="home.php">Home</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Mensagens com <?php echo htmlspecialchars($to_user['nome']); ?></h1>
        <div class="chat-box">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message">
                        <div class="message-header">
                            <img src="uploads/<?php echo htmlspecialchars($msg['sender_foto']) ?: 'default.jpg'; ?>" alt="Foto de <?php echo htmlspecialchars($msg['sender']); ?>" class="message-sender-photo">
                            <strong><?php echo htmlspecialchars($msg['sender']); ?>:</strong>
                        </div>
                        <p><?php echo htmlspecialchars($msg['message']); ?></p>
                        <small><?php echo date('d/m/Y H:i', strtotime($msg['timestamp'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Sem mensagens ainda.</p>
            <?php endif; ?>
        </div>
        <form method="POST" class="message-form">
            <textarea name="message" placeholder="Digite sua mensagem..." required></textarea>
            <button type="submit">Enviar</button>
        </form>
        <a href="home.php" class="back-button">Voltar</a>
    </main>

    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

/* Cabeçalho com navegação */
header {
    background-color: #333;
    color: #fff;
    padding: 10px 0;
    text-align: center;
}

nav a {
    color: #fff;
    margin: 0 15px;
    text-decoration: none;
}

nav a:hover {
    text-decoration: underline;
}

/* Estilo principal da página */
main {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Título */
h1 {
    font-size: 24px;
    margin-bottom: 20px;
}

/* Caixa de mensagens */
.chat-box {
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 20px;
}

/* Estilo das mensagens */
.message {
    background-color: #f9f9f9;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
}

.message-header {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.message-sender-photo {
    width: 35px; /* Ajustado para tamanho adequado */
    height: 35px; /* Ajustado para tamanho adequado */
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
}

.message strong {
    font-size: 16px;
}

.message p {
    font-size: 14px;
    margin: 5px 0;
}

.message small {
    font-size: 12px;
    color: #888;
}

/* Estilo para mensagens enviadas e recebidas */
.message.sent {
    background-color: #e1f5fe; 
    align-self: flex-end;
}

.message.received {
    background-color: #f1f1f1; 
    align-self: flex-start;
}

/* Formulário para envio de mensagens */
.message-form {
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}

.message-form textarea {
    padding: 10px;
    font-size: 14px;
    border-radius: 4px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    resize: vertical;
    min-height: 80px;
}

.message-form button {
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.message-form button:hover {
    background-color: #45a049;
}

/* Botão de voltar */
.back-button {
    display: inline-block;
    padding: 10px 15px;
    background-color: #ccc;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 20px;
}

.back-button:hover {
    background-color: #bbb;
}
</style>
</body>
</html>
