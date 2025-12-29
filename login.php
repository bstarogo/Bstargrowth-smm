<?php
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    $stmt->fetch();
    if ($hash && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        echo "Login successful!";
    } else {
        echo "Invalid credentials!";
    }
}
?>
<form method="POST">
    Email: <input name="email"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Login</button>
</form>
