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
        WHERE ((m.from_user_id = ? AND m.to_user_id = ?)
           OR (m.from_user_id = ? AND m.to_user_id = ?))
           AND (m.hidden_for_user_id IS NULL OR m.hidden_for_user_id != ?) -- Exclui as mensagens ocultadas
        ORDER BY m.timestamp ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$from_user_id, $to_user_id, $to_user_id, $from_user_id, $from_user_id]);
$messages = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens com <?php echo htmlspecialchars($to_user['nome']); ?></title>
    <link rel="stylesheet" href="css/direct.css">
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
        <form id="clearChatForm" method="POST">
            <button type="button" id="clearChat">Limpar Conversa</button>
        </form>
        <form method="POST" class="message-form">
            <textarea name="message" placeholder="Digite sua mensagem..." required></textarea>
            <button type="submit">Enviar</button>
        </form>
        <a href="#" onclick="goBack()" class="back-button">Voltar</a>

        <script>
       function goBack() {
    let lastPage = sessionStorage.getItem("lastPage");
    
    if (lastPage && lastPage !== window.location.href) {
        window.location.href = lastPage;
    } else {
        window.location.href = "home.php"; // Página padrão caso não haja histórico
    }
}
        </script>

    </main>
    <script>
document.getElementById('clearChat').addEventListener('click', function() {
    if (confirm("Tem certeza que deseja limpar a conversa? Isso apagará apenas para você.")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "clear_chat.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("to_user_id=<?php echo $to_user_id; ?>");

        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload(); // Recarrega a página para atualizar a conversa
            } else {
                alert("Erro ao limpar a conversa.");
            }
        };
    }
});
</script>

    
</body>
</html>
