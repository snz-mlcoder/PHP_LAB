<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

require_once __DIR__ . '/../vendor/autoload.php';

function stripe_init(): void
{
    if (STRIPE_SECRET_KEY === '') {
        throw new RuntimeException("Missing STRIPE_SECRET_KEY");
    }
    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
}
