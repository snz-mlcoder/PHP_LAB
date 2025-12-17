<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function tinypesa_initiate_stk_push(
    string $phone,
    float $amount,
    string $accountReference,
    string $transactionDesc,
    string $callbackUrl
): array {
    if (TINYPESA_API_KEY === '') {
        throw new RuntimeException("Missing TINYPESA_API_KEY env var.");
    }

    // Payload shape depends on TinyPesa endpoint.
    // We keep it configurable and tolerant.
    $payload = [
        'api_key' => TINYPESA_API_KEY,
        'amount' => $amount,
        'msisdn' => $phone,
        'account_reference' => $accountReference,
        'transaction_desc' => $transactionDesc,
        'callback_url' => $callbackUrl,
    ];

    $ch = curl_init(TINYPESA_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES),
        CURLOPT_TIMEOUT => 30,
    ]);

    $resp = curl_exec($ch);
    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException("cURL error: {$err}");
    }

    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($resp, true);

    if ($status < 200 || $status >= 300) {
        $msg = is_array($decoded) ? json_encode($decoded) : $resp;
        throw new RuntimeException("TinyPesa HTTP {$status}: {$msg}");
    }

    if (!is_array($decoded)) {
        throw new RuntimeException("TinyPesa returned non-JSON response: {$resp}");
    }

    return $decoded;
}
