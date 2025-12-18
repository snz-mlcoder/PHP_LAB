# stripe-php-donations

A simple Stripe Checkout donation app built with PHP + MySQL.
It creates a Checkout Session, redirects donors to Stripe, and stores confirmed payments using a webhook.

## Tech
- PHP 8.1+
- Stripe Checkout + Webhooks
- MySQL
- Bootstrap (CDN)

## Setup (XAMPP)
1) Put the project into:
- C:\xampp\htdocs\stripe-php-donations

2) Install dependencies (no global composer needed)
- Download composer.phar into project, then run:
```bash
C:\xampp\php\php.exe composer.phar install

Environment variables
Copy and fill:

copy .env.example .env

Set at minimum:

APP_URL=http://localhost/stripe-php-donations/public

Pages

/public/index.php Donation form

/public/transactions.php List of confirmed donations

/public/webhook.php Stripe webhook receiver

Notes

Do not treat success redirect as proof of payment.

Always confirm via webhook.