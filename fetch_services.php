<?php
require_once 'config.php';

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit;
}

// Fetch all services
$sql = "SELECT id, name, description, price_per_unit, currency FROM services ORDER BY id ASC";
$result = $conn->query($sql);

$services = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price_per_unit' => (float)$row['price_per_unit'],
            'currency' => $row['currency']
        ];
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'data' => $services
]);

$conn->close();
?>
