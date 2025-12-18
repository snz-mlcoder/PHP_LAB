<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cancelled</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4">Payment cancelled</h1>
            <p class="text-muted">No charge was made.</p>
            <a class="btn btn-primary" href="index.php">Back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
