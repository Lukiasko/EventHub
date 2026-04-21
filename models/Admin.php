<?php

declare(strict_types=1);

class Admin extends Model
{
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM admins WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        return $admin ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, username, created_at FROM admins WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $admin = $stmt->fetch();

        return $admin ?: null;
    }
}
