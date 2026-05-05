<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ListingController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/listings', [ListingController::class, 'index']);
$router->get('/listing/{slug}', [ListingController::class, 'show']);
$router->get('/post', [ListingController::class, 'create']);
$router->post('/post', [ListingController::class, 'store']);
$router->get('/my-listings', [ListingController::class, 'myListings']);
$router->get('/listing/{id}/edit', [ListingController::class, 'edit']);
$router->post('/listing/{id}/edit', [ListingController::class, 'update']);
$router->post('/listing/{id}/delete', [ListingController::class, 'delete']);
$router->get('/saved', [ListingController::class, 'saved']);
$router->post('/listing/{id}/save', [ListingController::class, 'save']);
$router->post('/listing/{id}/unsave', [ListingController::class, 'unsave']);

$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/admin', [AdminController::class, 'dashboard']);
$router->get('/admin/listings', [AdminController::class, 'listings']);
$router->post('/admin/listings/{id}/approve', [AdminController::class, 'approveListing']);
$router->post('/admin/listings/{id}/reject', [AdminController::class, 'rejectListing']);
$router->post('/admin/listings/{id}/delete', [AdminController::class, 'deleteListing']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->post('/admin/users/{id}/update', [AdminController::class, 'updateUser']);
