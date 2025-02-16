<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['to_user_id'])) {
    echo json_encode(["success" => false, "error" => "Acesso negado"]);
    exit;
}

$from_user_id = $_SESSION['user_id'];
$to_user_id = $_POST['to_user_id'];

// Oculta as mensagens apenas para o usuário que clicou no botão
$sql = "UPDATE messages SET hidden_for_user_id = ? WHERE (from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?)";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([$from_user_id, $from_user_id, $to_user_id, $to_user_id, $from_user_id]);

if ($success) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Falha ao limpar a conversa"]);
}
?>
