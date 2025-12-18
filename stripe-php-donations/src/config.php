<?php
declare(strict_types=1);

// App environment
define('APP_ENV', (string) (getenv('APP_ENV') ?: 'production'));
define('APP_DEBUG', ((string) getenv('APP_DEBUG')) === 'true');

// App URL (used for Stripe redirect URLs)
$envAppUrl = getenv('APP_URL');
if ($envAppUrl && trim((string)$envAppUrl) !== '') {
    define('APP_URL', rtrim((string)$envAppUrl, '/'));
} else {
    $scheme = (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']))
        ? (string)$_SERVER['HTTP_X_FORWARDED_PROTO']
        : ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $base = $scheme . '://' . $host;

    // Fallback: if no APP_URL set, try to approximate current directory (public)
    $dir = rtrim(str_replace('\\', '/', dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
    define('APP_URL', rtrim($base . $dir, '/'));
}


// Stripe keys
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
