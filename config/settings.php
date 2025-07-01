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
    'twig' => [
        'path' => $_ENV['TWIG_TEMPLATES_PATH'] ?? __DIR__.'/../templates',
        'options' => [
            'cache' => $_ENV['TWIG_CACHE'] ?? false,
        ],
    ],
    'session' => [
        'nonLocking' => $_ENV['SESSION_NON_LOCKING'] ?? false,
        'deleteCookieOnEmptySession' => $_ENV['SESSION_DELETE_COOKIE_ON_EMPTY_SESSION'] ?? false,
    ],
];
