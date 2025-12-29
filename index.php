<?php
// index.php
require_once 'config.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user info
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT username, wallet_balance FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>BStarGrowth SMM Panel</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p>Wallet Balance: <?php echo number_format($user['wallet_balance'], 2); ?> NGN</p>
    <nav>
        <a href="dashboard.php">Dashboard</a> |
        <a href="services.php">Services</a> |
        <a href="logout.php">Logout</a>
    </nav>
</body>
</html>
