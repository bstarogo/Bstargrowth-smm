<?php
session_start();
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $message = "Email already registered.";
    } else {
        // Insert new user
        $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $username, $email, $hashed_password);
        if ($insert->execute()) {
            $_SESSION['user_id'] = $insert->insert_id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Registration failed. Please try again.";
        }
        $insert->close();
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - BStarGrowth</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        form { max-width: 400px; margin: auto; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box;
        }
        input[type=submit] {
            padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer;
        }
        input[type=submit]:hover { background-color: #0056b3; }
        .message { color: red; }
    </style>
</head>
<body>
    <h1>Register</h1>
    <?php if ($message) echo "<p class='message'>" . htmlspecialchars($message) . "</p>"; ?>
    <form method="post" action="">
        <label>Username</label>
        <input type="text" name="username" required>
        
        <label>Email</label>
        <input type="email" name="email" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Register">
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
