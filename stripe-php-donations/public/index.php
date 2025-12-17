<?php
declare(strict_types=1);

require __DIR__ . '/../src/config.php';
require __DIR__ . '/../src/helpers.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Donate (Stripe)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/styles.css" rel="stylesheet">
  <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4 mb-2">Donate</h1>
            <p class="text-muted mb-4">Pay securely with Stripe (EUR).</p>

            <form id="donationForm" class="vstack gap-3">
              <div>
                <label class="form-label">Email (optional)</label>
                <input name="email" type="email" class="form-control" placeholder="you@example.com">
              </div>

              <div>
                <label class="form-label">Amount (EUR)</label>
                <input name="amount" type="text" class="form-control" placeholder="e.g. 10" required>
                <div class="form-text">Minimum: <?= (int)MIN_DONATION_EUR ?> EUR</div>
              </div>

              <button class="btn btn-primary btn-lg" type="submit">Donate with Stripe</button>
              <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="/transactions.php">Transactions</a>
              </div>

              <div id="msg" class="mt-2"></div>
            </form>

            <hr class="my-4">
            <small class="text-muted">
              Note: Donation is recorded after Stripe webhook confirms payment.
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
const stripe = Stripe("<?= htmlspecialchars(STRIPE_PUBLISHABLE_KEY) ?>");

const form = document.getElementById("donationForm");
const msg = document.getElementById("msg");

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  msg.innerHTML = "";

  const fd = new FormData(form);
  const res = await fetch("/create_checkout_session.php", { method: "POST", body: fd });
  const data = await res.json();

  if (!res.ok) {
    msg.innerHTML = `<div class="alert alert-danger">${data.error || "Error"}</div>`;
    return;
  }

  const { sessionId } = data;
  const { error } = await stripe.redirectToCheckout({ sessionId });
  if (error) {
    msg.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
  }
});
</script>
</body>
</html>
