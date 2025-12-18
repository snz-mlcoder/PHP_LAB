<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

$sessionId = (string)($_GET['session_id'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Thank you</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4">Thanks!</h1>
            <p class="text-muted mb-3">
              If payment succeeded, it will appear in Transactions after the webhook confirms.
            </p>
            <?php if ($sessionId !== ''): ?>
              <p class="small text-muted mb-4">Session: <code><?= htmlspecialchars($sessionId) ?></code></p>
            <?php endif; ?>
            <div class="d-flex gap-2">
              <a class="btn btn-primary" href="transactions.php">Transactions</a>
              <a class="btn btn-outline-secondary" href="index.php">Donate again</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
