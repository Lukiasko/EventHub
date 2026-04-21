<?php

declare(strict_types=1);

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register(): void
    {
        if (Session::get('user_id') !== null) {
            redirect('home');
        }

        $errors = [];
        $data = [
            'username' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
        ];

        if (is_post()) {
            $data = $_POST;
            $errors = $this->validateRegistration($data);

            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            if ($errors === []) {
                $this->userModel->create($data);
                Session::flash('success', 'Registrácia bola úspešná. Teraz sa môžete prihlásiť.');
                redirect('user_login');
            }
        }

        $this->render('auth/register', [
            'pageTitle' => 'Registrácia',
            'errors' => $errors,
            'data' => $data,
        ]);
    }

    public function login(): void
    {
        if (Session::get('user_id') !== null) {
            redirect('home');
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
                $errors[] = 'Všetky polia sú povinné';
            }

            if ($errors === [] && $this->attemptLogin($login, $password)) {
                Session::flash('success', 'Boli ste úspešne prihlásený.');
                redirect('home');
            }

            if ($errors === []) {
                $errors[] = 'Nesprávne prihlasovacie údaje';
            }
        }

        $this->render('auth/login_user', [
            'pageTitle' => 'Prihlásenie',
            'errors' => $errors,
            'login' => $login,
        ]);
    }

    public function logout(): void
    {
        Session::destroy();
        Session::start();
        Session::flash('success', 'Boli ste odhlásený.');
        redirect('home');
    }

    private function validateRegistration(array $data): array
    {
        $errors = [];
        $username = trim((string) ($data['username'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $confirmPassword = (string) ($data['confirm_password'] ?? '');

        if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
            $errors[] = 'Všetky polia sú povinné';
            return $errors;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Zadajte platný email';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Heslo musí mať aspoň 6 znakov';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Heslá sa nezhodujú';
        }

        if ($this->userModel->existsByUsernameOrEmail($username, $email)) {
            $errors[] = 'Používateľ už existuje';
        }

        return $errors;
    }

    private function attemptLogin(string $login, string $password): bool
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
