<?php
// register.php
session_start();
require_once 'config.php';

// Create DB connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirmPassword)) {
        if ($password !== $confirmPassword) {
            $error = "Passwords do not match.";
        } else {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Username or email already taken.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashedPassword);

                if ($stmt->execute()) {
                    $success = "Registration successful! You can now <a href='login.php'>login</a>.";
                    
                    // Optional: Send Telegram notification to admin or resellers
                    // Example:
                    // sendTelegramNotification($username, $email);

                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
            $stmt->close();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - BStarGrowth</title>
</head>
<body>
    <h1>Register</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
