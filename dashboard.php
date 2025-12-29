<?php
require_once 'config.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, wallet_balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get recent orders
$stmt_orders = $conn->prepare("
    SELECT o.id, s.name as service_name, o.link, o.quantity, o.price, o.currency, o.status, o.created_at 
    FROM orders o 
    JOIN services s ON o.service_id = s.id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC 
    LIMIT 5
");
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$orders = $stmt_orders->get_result();

// Get available services
$services_result = $conn->query("SELECT id, name, price_per_unit, currency FROM services ORDER BY id ASC");

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

    <h2>Recent Orders</h2>
    <?php if ($orders->num_rows > 0): ?>
        <ul>
        <?php while($row = $orders->fetch_assoc()): ?>
            <li>
                Order #<?php echo $row['id']; ?>: <?php echo htmlspecialchars($row['service_name']); ?><br>
                Link: <?php echo htmlspecialchars($row['link']); ?><br>
                Quantity: <?php echo $row['quantity']; ?> | Price: <?php echo $row['price'] . ' ' . $row['currency']; ?><br>
                Status: <?php echo ucfirst($row['status']); ?> | Created: <?php echo $row['created_at']; ?>
            </li>
        <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No recent orders.</p>
    <?php endif; ?>

    <h2>Available Services</h2>
    <?php if ($services_result->num_rows > 0): ?>
        <ul>
        <?php while($service = $services_result->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($service['name']); ?> - <?php echo $service['price_per_unit'] . ' ' . $service['currency']; ?>
            </li>
        <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No services available at the moment.</p>
    <?php endif; ?>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
<?php
$conn->close();
?>
