<?php
/**
 * BStarGrowth Full Config
 * Complete system config – Database, Email, Telegram, Payments, Security
 * Safe for GitHub – No real credentials included
 */


/***********************
 DATABASE CONFIG
************************/
define("DB_HOST", "localhost");
define("DB_NAME", "YOUR_DATABASE_NAME");
define("DB_USER", "YOUR_DATABASE_USER");
define("DB_PASS", "YOUR_DATABASE_PASSWORD");


/***********************
 SITE CONFIG
************************/
define("SITE_NAME", "BStarGrowth SMM Panel");
define("BASE_URL", "https://bstargrowth.com.ng/"); 
define("DEFAULT_CURRENCY", "NGN"); 
define("ALLOW_MULTI_CURRENCY", true);

// Supported Currencies
$SUPPORTED_CURRENCIES = [
    "NGN", "USD", "EUR", "GBP", "INR"
];


/***********************
 EMAIL SUPPORT SYSTEM
************************/
define("SUPPORT_EMAIL", "support@bstargrowth.com.ng");

// IMAP Email
define("IMAP_HOST", "mail.bstargrowth.com.ng");
define("IMAP_PORT", "993");
define("IMAP_USER", "support@bstargrowth.com.ng");
define("IMAP_PASS", "YOUR_EMAIL_PASSWORD");
define("IMAP_SSL", true);


/***********************
 TELEGRAM BOT
************************/
define("TELEGRAM_BOT_USERNAME", "YOUR_BOT_USERNAME");
define("TELEGRAM_BOT_TOKEN", "TG-BOT-DEMO-TOKEN-PLACEHOLDER-12345"); // Demo placeholder
define("TELEGRAM_WEBHOOK_URL", BASE_URL . "telegram_webhook.php");


/***********************
 PAYMENT GATEWAYS
************************/

// Paystack
define("PAYSTACK_PUBLIC_KEY", "pk_demo_12345");
define("PAYSTACK_SECRET_KEY", "sk_demo_12345");

// Flutterwave
define("FLUTTER_PUBLIC_KEY", "FLWPUBK_DEMO-12345");
define("FLUTTER_SECRET_KEY", "FLWSECK_DEMO-12345");

// Payeer
define("PAYEER_ACCOUNT", "P0000000");
define("PAYEER_API_KEY", "PAYEER-DEMO-KEY");

define("ENABLE_PAYSTACK", true);
define("ENABLE_FLUTTERWAVE", true);
define("ENABLE_PAYEER", true);


/***********************
 SECURITY
************************/
define("PRODUCTION_MODE", true);
define("ALLOW_REGISTRATION", true);
define("ENABLE_EMAIL_VERIFICATION", true);
define("ENABLE_TWO_FACTOR", false);


/***********************
 SYSTEM FEATURES
************************/
define("ENABLE_API_RESELLER", true);
define("ENABLE_ORDER_AUTO_PROCESS", true);
define("ENABLE_TICKET_SYSTEM", true);
define("ENABLE_NOTIFICATIONS", true);


/***********************
 TIMEZONE
************************/
date_default_timezone_set("Africa/Lagos");


/***********************
 DATABASE CONNECTION
************************/
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("DATABASE CONNECTION FAILED");
}
?>
