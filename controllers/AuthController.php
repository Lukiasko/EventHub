<?php

declare(strict_types=1);

class AuthController extends Controller
{
    private Auth $auth;

    public function __construct()
    {
        $this->auth = new Auth(new Admin());
    }

    public function showLogin(): void
    {
        $this->login();
    }

    public function login(): void
    {
        if ($this->auth->check()) {
            redirect('admin_dashboard');
        }

        $errors = [];
        $username = '';

        if (is_post()) {
            $username = trim((string) ($_POST['username'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            if ($username === '' || $password === '') {
                $errors[] = 'Vyplňte používateľské meno aj heslo.';
            }

            if ($errors === [] && $this->auth->attempt($username, $password)) {
                Session::flash('success', 'Boli ste úspešne prihlásený.');
                redirect('admin_dashboard');
            }

            if ($errors === []) {
                $errors[] = 'Nesprávne prihlasovacie údaje.';
            }
        }

        $this->render('auth/login', [
            'pageTitle' => 'Prihlásenie do administrácie',
            'errors' => $errors,
            'username' => $username,
        ]);
    }

    public function logout(): void
    {
        $this->auth->logout();
        Session::start();
        Session::flash('success', 'Boli ste odhlásený.');
        redirect('login');
    }
}
