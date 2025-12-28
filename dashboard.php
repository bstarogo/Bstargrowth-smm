<?php
// dashboard.php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, wallet_balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $wallet_balance);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>BStarGrowth - Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Wallet Balance: <?php echo number_format($wallet_balance, 2); ?> NGN</p>

    <h3>Menu</h3>
    <ul>
        <li><a href="services.php">View Services</a></li>
        <li><a href="fetch_services.php">Order Services</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
