<?php
// telegram_webhook.php
require_once 'config.php';

// Get the incoming update from Telegram
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if(!$update) {
    exit;
}

// Extract chat ID and message
$chat_id = $update['message']['chat']['id'] ?? null;
$message = $update['message']['text'] ?? '';

// Simple auto-reply example
if($chat_id && $message) {
    $reply = "Hello! Welcome to BStarGrowth. Send /reseller to start your reseller training.";
    
    // Use your bot token from config.php (replace DEMO_TOKEN with actual token)
    $bot_token = BOT_TOKEN;
    $url = "https://api.telegram.org/bot$bot_token/sendMessage";
    
    $post_fields = [
        'chat_id' => $chat_id,
        'text' => $reply
    ];
    
    // Send response to Telegram
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
}
