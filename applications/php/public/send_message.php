<?php
include('config/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'], $_POST['to_user_id'])) {
    $message = trim($_POST['message']);
    $to_user_id = $_POST['to_user_id'];
    $from_user_id = $_SESSION['user_id'];

    if (!empty($message)) {
        
        $sql = "INSERT INTO messages (from_user_id, to_user_id, message, timestamp) VALUES (?, ?, ?, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$from_user_id, $to_user_id, $message]);


        echo json_encode(['status' => 'success']);
    } else {

        echo json_encode(['status' => 'error', 'message' => 'Mensagem não pode estar vazia']);
    }
} else {

    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
}
?>
