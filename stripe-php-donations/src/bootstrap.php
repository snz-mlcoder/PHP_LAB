<?php
declare(strict_types=1);

// Central bootstrap for all public entrypoints.
require_once __DIR__ . '/../vendor/autoload.php';

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname($envFile));
    $dotenv->safeLoad();
}

require_once __DIR__ . '/config.php';
