<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = [], int $status = 200): string
    {
        http_response_code($status);
        $currentUser = Auth::user();
        $success = flash('success');
        $error = flash('error');
        $errors = $data['errors'] ?? [];
        extract($data, EXTR_SKIP);

        ob_start();
        require BASE_PATH . '/app/Views/' . $view . '.php';
        $content = ob_get_clean();

        ob_start();
        require BASE_PATH . '/app/Views/layouts/app.php';
        $output = ob_get_clean();
        clear_old();
        echo $output;

        return $output;
    }

    protected function requireCsrf(): void
    {
        if (!verify_csrf($_POST['_csrf_token'] ?? null)) {
            flash('error', 'Your session expired. Please try again.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    protected function backWithErrors(array $errors): never
    {
        set_old($_POST);
        Session::put('_errors', $errors);
        flash('error', 'Please review the highlighted fields.');
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    protected function validationErrors(): array
    {
        $errors = Session::get('_errors', []);
        Session::forget('_errors');

        return is_array($errors) ? $errors : [];
    }
}
