<?php
// fetch_services.php
require_once 'config.php';

// Fetch all services
$services = [];
$result = $conn->query("SELECT id, name, description, price_per_unit, currency FROM services ORDER BY id ASC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price_per_unit' => $row['price_per_unit'],
            'currency' => $row['currency']
        ];
    }
}

// Return JSON for bot or front-end
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'services' => $services
]);
