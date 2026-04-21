<?php

declare(strict_types=1);

class Auth
{
    public function __construct(private Admin $adminModel)
    {
    }

    public function attempt(string $username, string $password): bool
    {
        $admin = $this->adminModel->findByUsername($username);

        if (!$admin || !password_verify($password, $admin['password'])) {
            return false;
        }

        Session::regenerate();
        Session::set('admin_id', (int) $admin['id']);
        Session::set('admin_username', $admin['username']);

        return true;
    }

    public function check(): bool
    {
        return Session::get('admin_id') !== null;
    }

    public function id(): ?int
    {
        $id = Session::get('admin_id');

        return $id === null ? null : (int) $id;
    }

    public function username(): ?string
    {
        return Session::get('admin_username');
    }

    public function requireLogin(): void
    {
        if (!$this->check()) {
            Session::flash('error', 'Pre vstup do administrácie sa musíte prihlásiť.');
            redirect('login');
        }
    }

    public function logout(): void
    {
        Session::remove('admin_id');
        Session::remove('admin_username');
        Session::regenerate();
    }
}
