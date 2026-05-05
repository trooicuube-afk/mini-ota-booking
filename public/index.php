<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ListingController;
use App\Core\Router;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/bootstrap/app.php';

$router = new Router();

require BASE_PATH . '/routes/web.php';

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $exception) {
    http_response_code(500);

    if (config('debug')) {
        echo '<pre>' . e($exception->getMessage() . "\n" . $exception->getTraceAsString()) . '</pre>';
        exit;
    }

    (new HomeController())->error(500, 'Something went wrong. Please try again.');
}
