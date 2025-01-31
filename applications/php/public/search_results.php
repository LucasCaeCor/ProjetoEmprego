<?php
include('config/db.php');

// Filtros de profissão e cidade
$service_filter = isset($_POST['service']) ? $_POST['service'] : '';
$city_filter = isset($_POST['city']) ? $_POST['city'] : '';

// Query para buscar todos os usuários cadastrados com latitude e longitude
$sql = "SELECT id, nome, latitude, longitude, foto, cargo, cidade FROM users WHERE latitude IS NOT NULL AND longitude IS NOT NULL";

// Aplicar filtros independentemente
$conditions = [];
$params = [];

if ($service_filter) {
    $conditions[] = "cargo LIKE :service";
    $params[':service'] = '%' . $service_filter . '%';
}
if ($city_filter) {
    $conditions[] = "cidade LIKE :city";
    $params[':city'] = '%' . $city_filter . '%';
}

// Se houver filtros, adicionar na query
if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$stmt = $pdo->prepare($sql);

// Vincular os parâmetros dinamicamente
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->execute();
$users = $stmt->fetchAll();

echo json_encode($users);
?>
