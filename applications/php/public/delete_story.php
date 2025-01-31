<?php

include('config/db.php');
session_start(); 
// Verificar se o ID da história foi enviado e o usuário está logado
if (isset($_POST['story_id']) && isset($_SESSION['user_id'])) {
    $story_id = $_POST['story_id'];
    $user_id = $_SESSION['user_id'];


    $query = "SELECT id_usuario FROM stories WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $story_id, PDO::PARAM_INT);
    $stmt->execute();

    $story = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se a história for encontrada e o ID do usuário que postou for o mesmo do usuário logado
    if ($story) {
        if ($story['id_usuario'] == $user_id) {
            // Deletar a história do banco de dados
            $deleteQuery = "DELETE FROM stories WHERE id = ?";
            $deleteStmt = $pdo->prepare($deleteQuery);
            $deleteStmt->bindParam(1, $story_id, PDO::PARAM_INT);

            if ($deleteStmt->execute()) {

                header('Location: feed.php');
                exit();
            } else {
                echo "Erro ao excluir a história. Tente novamente.";
            }
        } else {
            echo "Você não tem permissão para excluir esta história.";
        }
    } else {
        echo "História não encontrada.";
    }
} else {

    echo "História não encontrada ou usuário não autorizado.";
}
?>
