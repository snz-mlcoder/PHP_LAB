<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $databaseUrl = getenv('DATABASE_URL');
    if ($databaseUrl && is_string($databaseUrl) && trim($databaseUrl) !== '') {
        $parts = parse_url($databaseUrl);

        if (is_array($parts)) {
            $host = (string)($parts['host'] ?? DB_HOST);
            $user = (string)($parts['user'] ?? DB_USER);
            $pass = (string)($parts['pass'] ?? DB_PASS);
            $port = $parts['port'] ?? null;

            $name = DB_NAME;
            if (isset($parts['path'])) {
                $name = ltrim((string)$parts['path'], '/');
            }

            $portPart = $port ? ';port=' . (int)$port : '';
            $dsn = "mysql:host={$host}{$portPart};dbname={$name};charset=utf8mb4";

            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        }
    }

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
