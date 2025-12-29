<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit;
}
echo "<h1>Welcome to your Dashboard!</h1>";
echo "<p>Manage your services, orders, and wallet here.</p>";
