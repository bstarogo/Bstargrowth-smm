<?php
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();
    echo "Registration successful!";
}
?>
<form method="POST">
    Username: <input name="username"><br>
    Email: <input name="email"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Register</button>
</form>
