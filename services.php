<?php
require_once 'config.php';

// Connect to database
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
    <title>Services - BStarGrowth</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #333; }
        ul { list-style: none; padding: 0; }
        li { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        strong { font-size: 18px; }
    </style>
</head>
<body>
    <h1>Available Services</h1>
    <?php if ($result->num_rows > 0): ?>
        <ul>
        <?php while($row = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                <?php echo nl2br(htmlspecialchars($row['description'])); ?><br>
                <em>Price:</em> <?php echo number_format($row['price_per_unit'], 2) . ' ' . htmlspecialchars($row['currency']); ?>
            </li>
        <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No services available at the moment.</p>
    <?php endif; ?>
</body>
</html>
<?php
$conn->close();
?>
