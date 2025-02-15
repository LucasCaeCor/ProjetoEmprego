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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgFfMs19BXGQhVLWBzdQbkbuwlQemqqpc" async defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      let map;
let markers = [];
let selectedMarker = null;
let userLocation = { lat: <?php echo $user_latitude; ?>, lng: <?php echo $user_longitude; ?> };

window.onload = function() {
    initMap();
};

// Inicializa o mapa
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: userLocation,
        zoom: 12,
    });

    // Marcador para a localização do usuário logado
    new google.maps.Marker({
        position: userLocation,
        map: map,
        icon: {
            url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
            scaledSize: new google.maps.Size(40, 40),
        },
        title: "Você está aqui!",
    });
}

// Centraliza o mapa e adiciona a "pingola" do card selecionado
function centerMap(lat, lng) {
    const newLocation = new google.maps.LatLng(lat, lng);

    // Centraliza o mapa e ajusta o zoom
    map.setCenter(newLocation);
    map.setZoom(15);

    // Remove o marcador anterior, se tiver on
    if (selectedMarker) {
        selectedMarker.setMap(null);
    }

    // Adiciona novo marcador
    selectedMarker = new google.maps.Marker({
        position: newLocation,
        map: map,
        icon: {
            url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
            scaledSize: new google.maps.Size(40, 40),
        },
        title: "Localização selecionada",
    });
}

// Aplica os filtros e atualiza os cards via AJAX
function applyFilters() {
    const serviceFilter = document.getElementById("service-filter").value;
    const cityFilter = document.getElementById("city-filter").value;

    $.ajax({
        url: 'search_results.php',
        method: 'POST',
        data: {
            service: serviceFilter,
            city: cityFilter
        },
        success: function(response) {
            const userCards = JSON.parse(response);
            showUserCards(userCards);
        }
    });
}

// Exibe os cards dos usuários
function showUserCards(userCards) {
    const cardsContainer = document.getElementById("cards-container");
    cardsContainer.innerHTML = ""; // Limpa os cards anteriores

    if (userCards.length > 0) {
        userCards.forEach(user => {
            if (user.latitude && user.longitude) {
                const card = `
                <div id="cards-container">
                    <div class="card">
                        <div class="card-header">
                            <img src="uploads/${user.foto || 'default.jpg'}" class="card-img-top" alt="Foto do usuário">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${user.nome}</h5>
                            <p class="card-text">Serviço: ${user.cargo || 'Não informado'}</p>
                            <p class="card-text">Cidade: ${user.cidade || 'Não informada'}</p>
                            <a href="profile.php?id=${user.id}" class="btn btn-primary">Ver Perfil</a>
                            <button class="btn btn-secondary" onclick="centerMap(${user.latitude}, ${user.longitude})">Ver no Mapa</button>
                        </div>
                    </div>
                </div>
                `;
                cardsContainer.innerHTML += card;
            } else {
                console.warn("Usuário sem localização: ", user.nome);
            }
        });
    } else {
        cardsContainer.innerHTML = "<p>Nenhum resultado encontrado para sua pesquisa.</p>";
    }
}



    </script>
    <style>

/* Estilo do container de cards */
#cards-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 20px;
}

/* Estilo dos cards */
.card {
    flex: 1 1 calc(33.333% - 40px);
    max-width: 300px;
    margin: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    background-color: #fff;
}

/* Responsividade dos cards */
@media (max-width: 768px) {
    .card {
        flex: 1 1 calc(50% - 20px);
    }
}

@media (max-width: 480px) {
    .card {
        flex: 1 1 100%;
    }
}
.btn {
    display: inline-block;
    margin: 5px;
    padding: 10px 20px;
    font-size: 14px;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
}


        .card-header {
            padding: 20px;
            background-color: #f9f9f9;
        }

/* Ajuste para imagens no card */
.card-img-top {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin: 10px auto;
}

        .card-body {
            padding: 20px;
            background-color: #fff;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }

        .card-text {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }
    </style>
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
</body>
</html>
