<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/stripe.php';

try {
    if (STRIPE_SECRET_KEY === '' || STRIPE_PUBLISHABLE_KEY === '') {
        json_response(['error' => 'Stripe is not configured (missing keys).'], 400);
        exit;
    }

    stripe_init();

    $email = trim((string)($_POST['email'] ?? ''));
    $amountRaw = (string)($_POST['amount'] ?? '');

    $amountCents = to_eur_cents($amountRaw);
    if ($amountCents === null) {
        json_response(['error' => 'Invalid amount. Example: 10 or 10.50 (min ' . MIN_DONATION_EUR . ' EUR)'], 400);
        exit;
    }

    $successUrl = APP_URL . '/success.php?session_id={CHECKOUT_SESSION_ID}';
    $cancelUrl  = APP_URL . '/cancel.php';

    $session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'success_url' => $successUrl,
        'cancel_url' => $cancelUrl,
        'customer_email' => ($email !== '' ? $email : null),
        'line_items' => [[
            'quantity' => 1,
            'price_data' => [
                'currency' => DONATION_CURRENCY,
                'unit_amount' => $amountCents,
                'product_data' => [
                    'name' => 'Donation',
                ],
            ],
        ]],
        'metadata' => [
            'type' => 'donation',
        ],
    ]);

    json_response(['sessionId' => $session->id], 200);
} catch (Throwable $e) {
    json_response(['error' => $e->getMessage()], 500);
}
