<?php
require_once 'config.php';

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all services
$sql = "SELECT s.id, s.name, s.description, s.price_per_unit, s.currency, c.rate_to_ngn
        FROM services s
        LEFT JOIN currencies c ON s.currency = c.code
        ORDER BY s.id ASC";
$result = $conn->query($sql);

$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate price in NGN if currency rate exists
        $price_ngn = $row['rate_to_ngn'] ? $row['price_per_unit'] * $row['rate_to_ngn'] : $row['price_per_unit'];
        $services[] = [
            'id' => $row['id'],
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'price' => $row['price_per_unit'],
            'currency' => $row['currency'],
            'price_ngn' => number_format($price_ngn, 2)
        ];
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Services - BStarGrowth</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        ul { list-style-type: none; padding: 0; }
        li { background: #f9f9f9; margin-bottom: 10px; padding: 10px; border-radius: 5px; }
        strong { font-size: 1.1em; }
    </style>
</head>
<body>
    <h1>Available Services</h1>
    <?php if (!empty($services)): ?>
        <ul>
            <?php foreach($services as $service): ?>
                <li>
                    <strong><?php echo $service['name']; ?></strong><br>
                    <?php echo $service['description']; ?><br>
                    Price: <?php echo $service['price'] . ' ' . $service['currency']; ?>
                    <?php if ($service['currency'] != 'NGN'): ?>
                        (≈ <?php echo $service['price_ngn']; ?> NGN)
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No services available at the moment.</p>
    <?php endif; ?>
</body>
</html>
