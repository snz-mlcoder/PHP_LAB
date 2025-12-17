<?php
declare(strict_types=1);

require __DIR__ . '/../src/config.php';
require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/tinypesa.php';

$error = null;
$info = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone  = trim((string)($_POST['phone'] ?? ''));
    $amount = trim((string)($_POST['amount'] ?? ''));

    // Basic validation
    $phoneNormalized = normalize_ke_msisdn($phone);
    if ($phoneNormalized === null) {
        $error = "Invalid phone number. Use formats like 07XXXXXXXX or 2547XXXXXXXX.";
    } elseif (!is_numeric($amount) || (float)$amount <= 0) {
        $error = "Invalid amount. Enter a number greater than 0.";
    } else {
        $amountValue = (float)$amount;

        try {
            $callbackUrl = rtrim(APP_URL, '/') . '/callback.php';
            $resp = tinypesa_initiate_stk_push(
                phone: $phoneNormalized,
                amount: $amountValue,
                accountReference: 'Donation',
                transactionDesc: 'Donation payment',
                callbackUrl: $callbackUrl
            );

            // We don’t know exact TinyPesa fields (they vary by integration),
            // so we show a friendly message and dump a request id if present.
            $reqId = (string)($resp['request_id'] ?? $resp['CheckoutRequestID'] ?? $resp['checkout_request_id'] ?? '');
            $info = "STK Push sent to {$phoneNormalized}. Approve it on your phone."
                . ($reqId ? " (Request ID: {$reqId})" : "");
        } catch (Throwable $e) {
            $error = "Failed to initiate payment: " . $e->getMessage();
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>M-Pesa Donations (TinyPesa)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4 mb-2">Donate via M-Pesa</h1>
            <p class="text-muted mb-4">Enter your phone number and amount. You’ll receive an STK prompt.</p>

            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($info): ?>
              <div class="alert alert-success"><?= htmlspecialchars($info) ?></div>
              <div class="d-flex gap-2">
                <a class="btn btn-outline-primary" href="/transactions.php">View Transactions</a>
                <a class="btn btn-outline-secondary" href="/">New Donation</a>
              </div>
              <hr class="my-4">
            <?php endif; ?>

            <form method="post" class="vstack gap-3">
              <div>
                <label class="form-label">Phone Number</label>
                <input name="phone" class="form-control" placeholder="07XXXXXXXX or 2547XXXXXXXX" required>
              </div>

              <div>
                <label class="form-label">Amount (KES)</label>
                <input name="amount" type="number" min="1" step="1" class="form-control" placeholder="e.g. 10" required>
              </div>

              <button class="btn btn-primary btn-lg" type="submit">Donate</button>
              <a class="btn btn-link" href="/transactions.php">Transactions</a>
            </form>

            <hr class="my-4">
            <small class="text-muted">
              Note: Payment confirmation is recorded only after TinyPesa calls the webhook at <code>/callback.php</code>.
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
