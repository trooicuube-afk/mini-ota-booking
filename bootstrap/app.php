<?php

declare(strict_types=1);

use App\Core\Session;

require BASE_PATH . '/vendor/autoload.php';

$envPath = BASE_PATH . '/.env';
if (is_file($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        if (getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}

$GLOBALS['config'] = require BASE_PATH . '/config/app.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');
Session::start();

if (!is_dir(BASE_PATH . '/public/assets/uploads')) {
    mkdir(BASE_PATH . '/public/assets/uploads', 0755, true);
}
