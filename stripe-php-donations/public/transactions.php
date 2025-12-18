<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/db.php';

$pdo = db();
$rows = $pdo->query(
    "SELECT id, email, amount_cents, currency, stripe_session_id, created_at
     FROM donations
     ORDER BY id DESC
     LIMIT 200"
)->fetchAll(PDO::FETCH_ASSOC);

function format_amount(int $cents, string $currency): string
{
    return strtoupper($currency) . ' ' . number_format($cents / 100, 2, '.', '');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Transactions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h4 mb-0">Donations</h1>
      <a class="btn btn-primary" href="index.php">New donation</a>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped align-middle mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Stripe Session</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$rows): ?>
                <tr><td colspan="5" class="text-muted">No donations yet.</td></tr>
              <?php else: ?>
                <?php foreach ($rows as $r): ?>
                  <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td><?= htmlspecialchars((string)($r['email'] ?? '')) ?></td>
                    <td><?= htmlspecialchars(format_amount((int)$r['amount_cents'], (string)$r['currency'])) ?></td>
                    <td><code><?= htmlspecialchars((string)$r['stripe_session_id']) ?></code></td>
                    <td><?= htmlspecialchars((string)$r['created_at']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <p class="text-muted mt-3 mb-0">
      Payments are confirmed via Stripe webhook (<code>webhook.php</code>).
    </p>
  </div>
</body>
</html>
