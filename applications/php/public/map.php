<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('config/db.php');

// Pegando as informações do usuário logado
$user_id = $_SESSION['user_id'];

// Buscar informações do usuário logado para pegar a latitude e longitude
$sql_user = "SELECT latitude, longitude FROM users WHERE id = ?";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute([$user_id]);
$user_data = $stmt_user->fetch();

$user_latitude = $user_data['latitude'] ?? -25.4343;
$user_longitude = $user_data['longitude'] ?? -49.2771;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Serviços</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/map.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgFfMs19BXGQhVLWBzdQbkbuwlQemqqpc" async defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    let map;
    let markers = [];
    let selectedMarker = null;
    let userLocation = { lat: <?php echo $user_latitude; ?>, lng: <?php echo $user_longitude; ?> };
    window.onload = function() {
    initMap();
    }
    </script>
</head>
<body>
    <header>
        <nav>
            <a href="home.php">Home</a>
            <a href="feed.php">Feed</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>
    <main>
        <h1>Mapa de Serviços</h1>
        <div id="map" style="width: 100%; height: 500px;"></div>
        <div>
            <label for="service-filter">Serviço:</label>
            <input type="text" id="service-filter" placeholder="Ex: Eletricista, Designer">
        </div>
        <div>
            <label for="city-filter">Cidade:</label>
            <input type="text" id="city-filter" placeholder="Ex: São Paulo">
        </div>
        <button onclick="applyFilters()">Procurar ao Redor</button>
        <div id="cards-container" class="mt-4">
            <!-- Seção aonde os cards vão aparecer -->
        </div>
    </main>
    <script src="js/map.js"></script>
</body>
</html>
