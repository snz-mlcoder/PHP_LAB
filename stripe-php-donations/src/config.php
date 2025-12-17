<?php
declare(strict_types=1);

$envAppUrl = getenv('APP_URL');
if ($envAppUrl && trim($envAppUrl) !== '') {
    define('APP_URL', rtrim($envAppUrl, '/'));
} else {
    $scheme = (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : (!empty($_SERVER['HTTPS']) ? 'https' : 'http'));
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('APP_URL', $scheme . '://' . $host);
}

define('STRIPE_SECRET_KEY', (string)(getenv('STRIPE_SECRET_KEY') ?: ''));
define('STRIPE_PUBLISHABLE_KEY', (string)(getenv('STRIPE_PUBLISHABLE_KEY') ?: ''));
define('STRIPE_WEBHOOK_SECRET', (string)(getenv('STRIPE_WEBHOOK_SECRET') ?: ''));

define('DONATION_CURRENCY', (string)(getenv('DONATION_CURRENCY') ?: 'eur'));
define('MIN_DONATION_EUR', (int)(getenv('MIN_DONATION_EUR') ?: 1));

define('DB_HOST', (string)(getenv('DB_HOST') ?: '127.0.0.1'));
define('DB_NAME', (string)(getenv('DB_NAME') ?: 'donations'));
define('DB_USER', (string)(getenv('DB_USER') ?: 'root'));
define('DB_PASS', (string)(getenv('DB_PASS') ?: ''));

define('LOG_DIR', __DIR__ . '/../storage/logs');
