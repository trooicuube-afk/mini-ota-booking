<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'LHT Estate'),
    'url' => rtrim(env('APP_URL', 'http://127.0.0.1:8080'), '/'),
    'env' => env('APP_ENV', 'local'),
    'debug' => filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),
    'database' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'lht_estate'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
    ],
];
