<?php

declare(strict_types=1);

class ContactController extends Controller
{
    public function index(): void
    {
        $errors = [];
        $data = ['name' => '', 'email' => '', 'message' => ''];

        if (is_post()) {
            $data = $_POST;
            $errors = $this->validate($data);

            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            if ($errors === []) {
                $messageModel = new ContactMessage();
                $messageModel->create($data);
                Session::flash('success', 'Správa bola odoslaná. Ďakujeme za kontaktovanie.');
                redirect('contact');
            }
        }

        $this->render('contact', [
            'pageTitle' => 'Kontakt',
            'errors' => $errors,
            'data' => $data,
        ]);
    }

    private function validate(array $data): array
    {
        $errors = [];
        $email = trim((string) ($data['email'] ?? ''));

        if (trim((string) ($data['name'] ?? '')) === '') {
            $errors[] = 'Meno je povinné.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Zadajte platný e-mail.';
        }

        if (trim((string) ($data['message'] ?? '')) === '') {
            $errors[] = 'Správa je povinná.';
        }

        return $errors;
    }
}
