<?php
require_once 'config.php';

$input = file_get_contents('php://input');
$update = json_decode($input, true);

if (!isset($update['message'])) exit;

$chat_id = $update['message']['chat']['id'];
$text = $update['message']['text'] ?? '';

// Example: auto-teaching new resellers
$responses = [
    'hello' => "Hello! Welcome to " . SITE_NAME . ". How can I help you today?",
    'help' => "You can use /services to see available services, or /contact to reach support.",
    'reseller' => "As a reseller, please follow the rules in the group. Your training will start automatically."
];

$response = $responses[strtolower($text)] ?? "I'm sorry, I didn't understand that. Type 'help' for options.";

// Send reply
file_get_contents("https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage?chat_id=$chat_id&text=" . urlencode($response));
?>
