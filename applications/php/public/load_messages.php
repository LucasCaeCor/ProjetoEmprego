<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];
$chat_user_id = $_GET['user_id'];

// Buscar mensagens entre o usuário logado e o usuário de chat
$sql = "SELECT m.id, m.message, m.timestamp, u.nome, u.foto
        FROM messages m
        LEFT JOIN users u ON m.from_user_id = u.id
        WHERE (m.from_user_id = ? AND m.to_user_id = ?) 
           OR (m.from_user_id = ? AND m.to_user_id = ?)
        ORDER BY m.timestamp ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $chat_user_id, $chat_user_id, $user_id]);

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formatar a hora das mensagens
foreach ($messages as &$message) {
    
    $message['formatted_time'] = date('H:i', strtotime($message['timestamp']));
}

echo json_encode($messages);
?>
