<?php
// services.php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch services
$services = [];
$result = $conn->query("SELECT id, name, description, price_per_unit, currency FROM services ORDER BY id ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BStarGrowth - Services</title>
</head>
<body>
    <h2>Available Services</h2>
    <ul>
        <?php if (!empty($services)) : ?>
            <?php foreach ($services as $service) : ?>
                <li>
                    <strong><?php echo htmlspecialchars($service['name']); ?></strong><br>
                    Description: <?php echo htmlspecialchars($service['description']); ?><br>
                    Price: <?php echo number_format($service['price_per_unit'], 2) . ' ' . htmlspecialchars($service['currency']); ?><br>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No services available at the moment.</li>
        <?php endif; ?>
    </ul>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
