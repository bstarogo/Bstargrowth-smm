<?php
// telegram_webhook.php — Final version

// Include config
include_once('config.php');

// Read incoming Telegram updates
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// Extract message
$message = $update['message'] ?? null;
$chat_id = $message['chat']['id'] ?? '';
$text = trim($message['text'] ?? '');
$username = $message['from']['username'] ?? '';
$first_name = $message['from']['first_name'] ?? '';

// Function to send Telegram messages
function sendMessage($chat_id, $text) {
    global $telegram_bot_token;
    $url = "https://api.telegram.org/bot{$telegram_bot_token}/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    file_get_contents($url . '?' . http_build_query($data));
}

// Demo bot token from config
$telegram_bot_token = TELEGRAM_BOT_TOKEN; // Replace in config with real token

// Example: simple keyword auto-reply
$keywords = [
    'reseller' => "Hello! 👋\n\nAs a reseller, you can:\n1. Fund your wallet\n2. Place orders\n3. Check order history\n4. Get support\n\nType 'help' anytime for guidance.",
    'help' => "Here’s what you can do:\n- Fund wallet: type 'fund'\n- Check balance: type 'balance'\n- Place order: type 'order'\n- View services: type 'services'\n- Get support: type 'support'",
    'fund' => "To fund your account, visit your dashboard or follow the instructions in your panel. Your funds will stay in your wallet until you spend them.",
    'balance' => "Your wallet balance is automatically updated after every transaction.",
    'order' => "To place an order, type 'order' followed by the service and quantity, or use the panel dashboard for easy order placement.",
    'services' => "You can view all available services in your dashboard or type 'services' here to see them listed.",
    'support' => "Need help? Contact support via your dashboard or email: support@bstargrowth.com.ng"
];

// Default reply
$default_reply = "Hi {$first_name}! 👋\n\nI'm your BStarGrowth assistant. Type 'help' to see available commands.";

// Process message
if($text) {
    $matched = false;
    foreach($keywords as $key => $reply) {
        if(stripos($text, $key) !== false) {
            sendMessage($chat_id, $reply);
            $matched = true;
            break;
        }
    }
    if(!$matched) {
        sendMessage($chat_id, $default_reply);
    }
}
?>
