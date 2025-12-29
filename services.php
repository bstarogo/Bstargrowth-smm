<?php
require_once 'config.php';

// Connect to DB
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch services
$sql = "SELECT id, name, description, price_per_unit, currency FROM services ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Services - <?= SITE_NAME ?></title>
</head>
<body>
    <h1>Available Services</h1>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                    <?= htmlspecialchars($row['description']) ?><br>
                    Price: <?= $row['price_per_unit'] ?> <?= $row['currency'] ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No services available at the moment.</p>
    <?php endif; ?>
</body>
</html>
<?php $conn->close(); ?>
