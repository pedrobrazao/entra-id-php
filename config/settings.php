<?php

use Dotenv\Dotenv;
use Monolog\Logger;

// Load values from .env into environmental variables
Dotenv::createImmutable(__DIR__.'/..')->safeLoad();

return [
    'displayErrorDetails' => (bool) ($_ENV['APP_DISPLAY_ERROR_DETAILS'] ?? true), // Should be set to false in production
    'logErrors' => (bool) ($_ENV['APP_LOG_ERRORS'] ?? true),
    'logErrorDetails' => (bool) ($_ENV['APP_LOG_ERROR_DETAILS'] ?? true),
    'logger' => [
        'name' => $_ENV['LOGGER_NAME'] ?? 'app',
        'path' => $_ENV['LOGGER_PATH'] ?? 'php://stderr',
        'level' => $_ENV['LOGGER_LEVEL'] ?? Logger::DEBUG,
    ],
];
