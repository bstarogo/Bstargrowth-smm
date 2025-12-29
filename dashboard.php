<?php
// dashboard.php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info
$stmt = $conn->prepare("SELECT username, email, wallet_balance FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get all services
$services_res = $conn->query("SELECT id, name, description, price_per_unit, currency FROM services ORDER BY id ASC");

// Get all currencies for conversion
$currencies_res = $conn->query("SELECT code, rate_to_ngn FROM currencies");
$currencies = [];
while ($row = $currencies_res->fetch_assoc()) {
    $currencies[$row['code']] = $row['rate_to_ngn'];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - BStarGrowth</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Wallet Balance: <?php echo number_format($user['wallet_balance'], 2); ?> NGN</p>

    <h2>Available Services</h2>
    <?php if ($services_res->num_rows > 0): ?>
        <ul>
            <?php while($service = $services_res->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($service['name']); ?></strong><br>
                    <?php echo htmlspecialchars($service['description']); ?><br>
                    Price: <?php echo number_format($service['price_per_unit'], 2) . ' ' . htmlspecialchars($service['currency']); ?>
                    <?php if ($service['currency'] != 'NGN' && isset($currencies[$service['currency']])): ?>
                        (≈ <?php echo number_format($service['price_per_unit'] * $currencies[$service['currency']], 2); ?> NGN)
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No services available at the moment.</p>
    <?php endif; ?>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
