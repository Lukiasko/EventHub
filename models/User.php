<?php

declare(strict_types=1);

class User extends Model
{
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password)
             VALUES (:username, :email, :password)'
        );

        return $stmt->execute([
            'username' => trim((string) $data['username']),
            'email' => trim((string) $data['email']),
            'password' => password_hash((string) $data['password'], PASSWORD_DEFAULT),
        ]);
    }

    public function findByLogin(string $login): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users
             WHERE username = :login OR email = :login
             LIMIT 1'
        );
        $stmt->execute(['login' => trim($login)]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function existsByUsernameOrEmail(string $username, string $email): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*)
             FROM users
             WHERE username = :username OR email = :email'
        );
        $stmt->execute([
            'username' => trim($username),
            'email' => trim($email),
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }
}
