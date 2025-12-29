<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to DB
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

// Fetch user orders
$order_sql = "SELECT o.id, s.name AS service_name, o.link, o.quantity, o.price, o.currency, o.status, o.created_at
              FROM orders o
              JOIN services s ON o.service_id = s.id
              WHERE o.user_id = ?
              ORDER BY o.created_at DESC";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $_SESSION['user_id']);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

$orders = [];
while ($row = $order_result->fetch_assoc()) {
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
        h1, h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Wallet Balance: <?php echo number_format($wallet_balance,2); ?> NGN</p>
    <p><a href="services.php">View Services</a> | <a href="logout.php">Logout</a></p>

    <h2>Your Orders</h2>
    <?php if (!empty($orders)): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Service</th>
                <th>Link</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['link']); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo $order['price']; ?></td>
                    <td><?php echo $order['currency']; ?></td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                    <td><?php echo $order['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>
</body>
</html>
