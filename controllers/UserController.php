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
                redirect('login');
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

    public function profile(): void
    {
        $this->requireUser();

        $userId = (int) Session::get('user_id');
        $user = $this->userModel->findById($userId);

        if (!$user) {
            Session::destroy();
            Session::start();
            Session::flash('error', 'Používateľ nebol nájdený.');
            redirect('login');
        }

        $errors = [];

        if (is_post()) {
            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            $nickname = trim((string) ($_POST['nickname'] ?? ''));

            if ($nickname === '') {
                $errors[] = 'Prezývka je povinná.';
            } elseif (strlen($nickname) < 2) {
                $errors[] = 'Prezývka musí mať aspoň 2 znaky.';
            }

            $avatarPath = $user['avatar'];
            if ($errors === []) {
                $avatarErrors = [];
                $newAvatarPath = $this->handleAvatarUpload($userId, $avatarErrors, $user['avatar']);
                $errors = array_merge($errors, $avatarErrors);

                if ($errors === []) {
                    $profileUpdated = $this->userModel->updateProfile($userId, [
                        'nickname' => $nickname,
                        'avatar' => $newAvatarPath,
                    ]);

                    if ($profileUpdated) {
                        $this->deleteStoredAvatar($user['avatar']);
                    } elseif ($newAvatarPath !== null && $newAvatarPath !== $user['avatar']) {
                        $this->deleteStoredAvatar($newAvatarPath);
                    }

                    Session::set('username', $nickname);
                    Session::flash('success', 'Profil bol úspešne upravený.');
                    redirect('profile');
                }

                $avatarPath = $newAvatarPath;
            }

            $user = array_merge($user, [
                'nickname' => $nickname,
                'avatar' => $avatarPath,
            ]);
        }

        $this->render('profile', [
            'pageTitle' => 'Profil používateľa',
            'user' => $user,
            'registeredEvents' => $this->userModel->registeredEvents($userId),
            'errors' => $errors,
        ]);
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
        $displayName = trim((string) ($user['nickname'] ?? ''));
        Session::set('username', $displayName !== '' ? $displayName : $user['username']);

        return true;
    }

    private function handleAvatarUpload(int $userId, array &$errors, ?string $currentAvatar): ?string
    {
        $avatarFile = $_FILES['avatar'] ?? null;

        if (!is_array($avatarFile) || ($avatarFile['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $currentAvatar;
        }

        if (($avatarFile['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            $errors[] = 'Profilovú fotku sa nepodarilo nahrať.';
            return $currentAvatar;
        }

        if (($avatarFile['size'] ?? 0) > 2 * 1024 * 1024) {
            $errors[] = 'Profilová fotka môže mať najviac 2 MB.';
            return $currentAvatar;
        }

        $imageInfo = @getimagesize((string) $avatarFile['tmp_name']);
        if ($imageInfo === false || !isset($imageInfo['mime'])) {
            $errors[] = 'Profilová fotka musí byť obrázok.';
            return $currentAvatar;
        }

        $mimeToExtension = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        $mime = (string) $imageInfo['mime'];
        if (!isset($mimeToExtension[$mime])) {
            $errors[] = 'Podporované sú len formáty JPG, PNG, GIF a WEBP.';
            return $currentAvatar;
        }

        $uploadDirectory = APP_ROOT . '/public/uploads/users';
        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0775, true) && !is_dir($uploadDirectory)) {
            $errors[] = 'Nepodarilo sa pripraviť priečinok pre profilovú fotku.';
            return $currentAvatar;
        }

        $filename = 'user_' . $userId . '.' . $mimeToExtension[$mime];
        $targetPath = $uploadDirectory . '/' . $filename;

        $this->removeUserAvatarVariants($uploadDirectory, $userId, $currentAvatar);

        if (!move_uploaded_file((string) $avatarFile['tmp_name'], $targetPath)) {
            $errors[] = 'Profilovú fotku sa nepodarilo uložiť.';
            return $currentAvatar;
        }

        return 'public/uploads/users/' . $filename;
    }

    private function deleteStoredAvatar(?string $avatarPath): void
    {
        $avatarPath = trim((string) $avatarPath);

        if ($avatarPath === '' || preg_match('/^https?:\/\//', $avatarPath) === 1) {
            return;
        }

        if (!str_starts_with($avatarPath, 'public/uploads/users/')) {
            return;
        }

        $fullPath = APP_ROOT . '/' . ltrim($avatarPath, '/');

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function removeUserAvatarVariants(string $uploadDirectory, int $userId, ?string $currentAvatar): void
    {
        foreach (glob($uploadDirectory . '/user_' . $userId . '.*') ?: [] as $filePath) {
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }

        $this->deleteStoredAvatar($currentAvatar);
    }
}
