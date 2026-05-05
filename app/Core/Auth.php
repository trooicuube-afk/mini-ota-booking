<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private static ?array $user = null;

    public static function user(): ?array
    {
        if (!Session::has('user_id')) {
            self::$user = null;
            return null;
        }

        if (self::$user === null) {
            self::$user = (new User())->find((int) Session::get('user_id'));
        }

        return self::$user;
    }

    public static function id(): ?int
    {
        $user = self::user();

        return $user === null ? null : (int) $user['id'];
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = (new User())->findByEmail($email);
        if ($user === null || $user['status'] !== 'active') {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        self::login((int) $user['id']);
        return true;
    }

    public static function login(int $userId): void
    {
        session_regenerate_id(true);
        Session::put('user_id', $userId);
        self::$user = null;
    }

    public static function logout(): void
    {
        Session::forget('user_id');
        self::$user = null;
        session_regenerate_id(true);
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            flash('error', 'Please sign in to continue.');
            redirect('/login');
        }

        $user = self::user();
        if ($user !== null && ($user['status'] ?? '') !== 'active') {
            self::logout();
            flash('error', 'Your account has been disabled.');
            redirect('/login');
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        $user = self::user();
        if ($user === null || $user['role'] !== 'admin') {
            (new \App\Controllers\HomeController())->error(403, 'You do not have access to this area.');
            exit;
        }
    }
}
