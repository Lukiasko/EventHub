<?php

declare(strict_types=1);

class ContactMessage extends Model
{
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO contact_messages (name, email, message)
             VALUES (:name, :email, :message)'
        );

        return $stmt->execute([
            'name' => trim((string) $data['name']),
            'email' => trim((string) $data['email']),
            'message' => trim((string) $data['message']),
        ]);
    }

    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM contact_messages
             ORDER BY created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
    }
}
