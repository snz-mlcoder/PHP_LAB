<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Normalize Kenyan MSISDN to 2547XXXXXXXX format.
 */
function normalize_ke_msisdn(string $input): ?string
{
    $s = preg_replace('/\s+/', '', $input);
    $s = str_replace(['+'], '', (string)$s);

    // 07XXXXXXXX => 2547XXXXXXXX
    if (preg_match('/^07\d{8}$/', $s)) {
        return '254' . substr($s, 1);
    }

    // 7XXXXXXXX (rare) => 2547XXXXXXXX
    if (preg_match('/^7\d{8}$/', $s)) {
        return '254' . $s;
    }

    // 2547XXXXXXXX
    if (preg_match('/^2547\d{8}$/', $s)) {
        return $s;
    }

    return null;
}

function log_json_payload(string $prefix, string $raw): void
{
    if (!is_dir(LOG_DIR)) {
        @mkdir(LOG_DIR, 0775, true);
    }
    $file = LOG_DIR . '/' . $prefix . '_' . date('Y-m-d_His') . '_' . bin2hex(random_bytes(3)) . '.json';
    @file_put_contents($file, $raw);
}
