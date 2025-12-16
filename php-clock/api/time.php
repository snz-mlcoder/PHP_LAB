<?php
// api/time.php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$timezone = $_GET['tz'] ?? 'Europe/Rome';

// Basic allow-list to avoid invalid timezone warnings and keep it safe/simple
$allowedTimezones = [
    'Europe/Rome',
    'UTC',
    'America/New_York',
    'Asia/Tehran',
    'Asia/Tokyo',
];

if (!in_array($timezone, $allowedTimezones, true)) {
    $timezone = 'Europe/Rome';
}

date_default_timezone_set($timezone);

echo json_encode([
    'timezone' => $timezone,
    'time' => date('H:i:s'),
    'date' => date('Y-m-d'),
    'day' => date('l'),
], JSON_UNESCAPED_UNICODE);
