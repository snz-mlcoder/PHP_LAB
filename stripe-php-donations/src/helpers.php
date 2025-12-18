<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function log_line(string $prefix, string $message): void
{
    if (!is_dir(LOG_DIR)) {
        @mkdir(LOG_DIR, 0775, true);
    }
    $file = LOG_DIR . '/' . $prefix . '_' . date('Y-m-d') . '.log';
    @file_put_contents($file, '[' . date('c') . '] ' . $message . PHP_EOL, FILE_APPEND);
}

function to_eur_cents(string $amount): ?int
{
    $s = trim($amount);
    if ($s === '') return null;
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $s)) return null;

    $float = (float)$s;
    if ($float < MIN_DONATION_EUR) return null;

    // Convert EUR to cents safely
    return (int)round($float * 100);
}

function json_response(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}