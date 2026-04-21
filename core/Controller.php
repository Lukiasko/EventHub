<?php

declare(strict_types=1);

class Controller
{
    protected function render(string $view, array $data = [], bool $admin = false): void
    {
        extract($data, EXTR_SKIP);

        $header = $admin ? 'admin_header.php' : 'header.php';
        $footer = $admin ? 'admin_footer.php' : 'footer.php';
        $viewFile = APP_ROOT . '/views/' . $view . '.php';

        if (!is_file($viewFile)) {
            throw new RuntimeException('View does not exist: ' . $view);
        }

        require APP_ROOT . '/views/partials/' . $header;
        require $viewFile;
        require APP_ROOT . '/views/partials/' . $footer;
    }

    protected function requireAdmin(): void
    {
        $auth = new Auth(new Admin());
        $auth->requireLogin();
    }
}
