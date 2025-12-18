<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/db.php';

header('Content-Type: application/json; charset=utf-8');

$payload = @file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

if ($payload === false || trim($payload) === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Empty body']);
    exit;
}

try {
    if (STRIPE_WEBHOOK_SECRET === '') {
        throw new RuntimeException('Missing STRIPE_WEBHOOK_SECRET');
    }

    // Verify signature
    $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, STRIPE_WEBHOOK_SECRET);

    if ($event->type === 'checkout.session.completed') {
        /** @var \Stripe\Checkout\Session $session */
        $session = $event->data->object;

        $sessionId = (string)($session->id ?? '');
        $email = isset($session->customer_details->email) ? (string)$session->customer_details->email : null;
        $currency = isset($session->currency) ? (string)$session->currency : DONATION_CURRENCY;
        $amountTotal = isset($session->amount_total) ? (int)$session->amount_total : null;
        $paymentIntent = isset($session->payment_intent) ? (string)$session->payment_intent : null;

        if ($sessionId === '' || $amountTotal === null) {
            log_line('stripe_webhook', 'Invalid session payload');
            echo json_encode(['ok' => true]);
            exit;
        }

        $pdo = db();

        $stmt = $pdo->prepare(
            "INSERT INTO donations (email, amount_cents, currency, stripe_session_id, stripe_payment_intent_id, status)
             VALUES (:email, :amount_cents, :currency, :session_id, :pi, 'paid')
             ON DUPLICATE KEY UPDATE
               stripe_payment_intent_id = VALUES(stripe_payment_intent_id),
               status = 'paid'"
        );

        $stmt->execute([
            ':email' => $email,
            ':amount_cents' => $amountTotal,
            ':currency' => $currency,
            ':session_id' => $sessionId,
            ':pi' => $paymentIntent,
        ]);

        log_line('stripe_webhook', "Stored donation session={$sessionId} amount_cents={$amountTotal}");
    }

    echo json_encode(['ok' => true]);
} catch (\UnexpectedValueException $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid payload']);
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid signature']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
