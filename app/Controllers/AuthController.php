<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->render('auth/login', [
            'title' => 'Sign in — LHT Estate',
            'errors' => $this->validationErrors(),
        ]);
    }

    public function login(): void
    {
        $this->requireCsrf();

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }
        if ($password === '') {
            $errors['password'] = 'Password is required.';
        }

        if ($errors !== []) {
            $this->backWithErrors($errors);
        }

        if (!Auth::attempt($email, $password)) {
            $this->backWithErrors(['email' => 'Invalid credentials or inactive account.']);
        }

        flash('success', 'Welcome back.');
        redirect(is_admin() ? '/admin' : '/my-listings');
    }

    public function registerForm(): void
    {
        $this->render('auth/register', [
            'title' => 'Create account — LHT Estate',
            'errors' => $this->validationErrors(),
        ]);
    }

    public function register(): void
    {
        $this->requireCsrf();

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirmation = (string) ($_POST['password_confirmation'] ?? '');
        $errors = [];

        if (strlen($name) < 2 || strlen($name) > 120) {
            $errors['name'] = 'Name must be 2–120 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        } elseif ((new User())->findByEmail($email) !== null) {
            $errors['email'] = 'This email is already registered.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirmation) {
            $errors['password_confirmation'] = 'Password confirmation does not match.';
        }

        if ($errors !== []) {
            $this->backWithErrors($errors);
        }

        $userId = (new User())->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        Auth::login($userId);
        flash('success', 'Account created. You can now post listings.');
        redirect('/post');
    }

    public function logout(): void
    {
        $this->requireCsrf();

        Auth::logout();
        flash('success', 'You have signed out.');
        redirect('/');
    }
}
