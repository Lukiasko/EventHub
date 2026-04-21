<?php

declare(strict_types=1);

class AuthController extends Controller
{
    private Auth $auth;
    private User $userModel;

    public function __construct()
    {
        $this->auth = new Auth(new Admin());
        $this->userModel = new User();
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
        $login = '';

        if (is_post()) {
            $login = trim((string) ($_POST['login'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            if ($login === '' || $password === '') {
                $errors[] = 'Všetky polia sú povinné.';
            }

            if ($errors === [] && $this->auth->attempt($login, $password)) {
                Session::remove('user_id');
                Session::remove('username');
                Session::flash('success', 'Boli ste úspešne prihlásený.');
                redirect('admin_dashboard');
            }

            if ($errors === [] && $this->attemptUserLogin($login, $password)) {
                Session::remove('admin_id');
                Session::remove('admin_username');
                Session::flash('success', 'Boli ste úspešne prihlásený.');
                redirect('home');
            }

            if ($errors === []) {
                $errors[] = 'Nesprávne prihlasovacie údaje.';
            }
        }

        $this->render('auth/login', [
            'pageTitle' => 'Prihlásenie',
            'errors' => $errors,
            'login' => $login,
        ]);
    }

    public function logout(): void
    {
        $this->auth->logout();
        Session::start();
        Session::flash('success', 'Boli ste odhlásený.');
        redirect('login');
    }

    private function attemptUserLogin(string $login, string $password): bool
    {
        $user = $this->userModel->findByLogin($login);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        Session::regenerate();
        Session::set('user_id', (int) $user['id']);
        Session::set('username', $user['username']);

        return true;
    }
}
