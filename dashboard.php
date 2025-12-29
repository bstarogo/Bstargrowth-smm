<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user info
$stmt = $conn->prepare("SELECT username, email, wallet_balance FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($username, $email, $wallet_balance);
$stmt->fetch();
$stmt->close();

// Fetch user's orders
$stmt = $conn->prepare("SELECT o.id, s.name, o.quantity, o.price, o.status FROM orders o JOIN services s ON o.service_id = s.id WHERE o.user_id = ? ORDER BY o.created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - BStarGrowth</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; }
        a.logout { color: red; float: right; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?> <a href="logout.php" class="logout">Logout</a></h1>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Wallet Balance: <?php echo number_format($wallet_balance, 2); ?> NGN</p>

    <h2>Your Orders</h2>
    <?php if (count($orders) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Service</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo $order['price'] . ' NGN'; ?></td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>

    <p><a href="services.php">Order a Service</a></p>
</body>
</html>
