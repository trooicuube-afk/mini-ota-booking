<?php

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Session;

function env(string $key, mixed $default = null): mixed
{
    $value = getenv($key);

    return $value === false ? $default : $value;
}

function config(string $key, mixed $default = null): mixed
{
    $value = $GLOBALS['config'] ?? [];
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');

    return rtrim(config('url'), '/') . ($path === '/' ? '/' : $path);
}

function asset(string $path): string
{
    return url($path);
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function redirect_back(string $fallback = '/'): never
{
    $referer = $_SERVER['HTTP_REFERER'] ?? null;
    if (is_string($referer) && $referer !== '') {
        $appHost = parse_url((string) config('url'), PHP_URL_HOST);
        $refererHost = parse_url($referer, PHP_URL_HOST);

        if ($refererHost === $appHost) {
            $path = parse_url($referer, PHP_URL_PATH) ?: '/';
            $query = parse_url($referer, PHP_URL_QUERY);
            redirect($path . (is_string($query) && $query !== '' ? '?' . $query : ''));
        }

        if (str_starts_with($referer, '/')) {
            redirect($referer);
        }
    }

    redirect($fallback);
}

function csrf_token(): string
{
    if (!Session::has('_csrf_token')) {
        Session::put('_csrf_token', bin2hex(random_bytes(32)));
    }

    return (string) Session::get('_csrf_token');
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(?string $token): bool
{
    $sessionToken = (string) Session::get('_csrf_token', '');

    return is_string($token) && $token !== '' && $sessionToken !== '' && hash_equals($sessionToken, $token);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        Session::put('_flash.' . $key, $message);
        return null;
    }

    $value = Session::get('_flash.' . $key);
    Session::forget('_flash.' . $key);

    return is_string($value) ? $value : null;
}

function set_old(array $data): void
{
    unset($data['password'], $data['password_confirmation'], $data['_csrf_token']);
    Session::put('_old', $data);
}

function old(string $key, mixed $default = ''): mixed
{
    $old = Session::get('_old', []);

    return is_array($old) && array_key_exists($key, $old) ? $old[$key] : $default;
}

function clear_old(): void
{
    Session::forget('_old');
}

function current_user(): ?array
{
    return Auth::user();
}

function is_admin(): bool
{
    $user = current_user();

    return $user !== null && $user['role'] === 'admin';
}

function selected(mixed $actual, mixed $expected): string
{
    return (string) $actual === (string) $expected ? 'selected' : '';
}

function checked(bool $condition): string
{
    return $condition ? 'checked' : '';
}

function price_vnd(mixed $value): string
{
    return number_format((float) $value, 0, ',', '.') . ' ₫';
}

function slugify(string $value): string
{
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?: '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'listing';
}
