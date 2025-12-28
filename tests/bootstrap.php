<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables for tests
if (file_exists(__DIR__ . '/../.env.test')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env.test');
    $dotenv->load();
} elseif (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();
}
