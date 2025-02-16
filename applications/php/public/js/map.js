

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


