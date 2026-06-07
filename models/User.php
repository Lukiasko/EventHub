<?php

declare(strict_types=1);

class User extends Model
{
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, nickname, avatar, password)
             VALUES (:username, :email, :nickname, :avatar, :password)'
        );

        return $stmt->execute([
            'username' => trim((string) $data['username']),
            'email' => trim((string) $data['email']),
            'nickname' => trim((string) ($data['nickname'] ?? $data['username'])),
            'avatar' => null,
            'password' => password_hash((string) $data['password'], PASSWORD_DEFAULT),
        ]);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findByLogin(string $login): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users
             WHERE username = :username OR email = :email
             LIMIT 1'
        );
        $login = trim($login);
        $stmt->execute([
            'username' => $login,
            'email' => $login,
        ]);
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

    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users
             SET nickname = :nickname,
                 avatar = :avatar
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'nickname' => trim((string) $data['nickname']),
            'avatar' => $data['avatar'],
        ]);
    }

    public function registeredEvents(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT er.created_at AS registered_at,
                    e.id,
                    e.title,
                    e.event_date,
                    e.location,
                    c.name AS category_name
             FROM event_registrations er
             INNER JOIN events e ON e.id = er.event_id
             LEFT JOIN categories c ON c.id = e.category_id
             WHERE er.user_id = :user_id
             ORDER BY e.event_date ASC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function isRegisteredForEvent(int $userId, int $eventId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*)
             FROM event_registrations
             WHERE user_id = :user_id AND event_id = :event_id'
        );
        $stmt->execute([
            'user_id' => $userId,
            'event_id' => $eventId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function registerForEvent(int $userId, int $eventId): bool
    {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO event_registrations (user_id, event_id)
             VALUES (:user_id, :event_id)'
        );

        return $stmt->execute([
            'user_id' => $userId,
            'event_id' => $eventId,
        ]);
    }

    public function unregisterFromEvent(int $userId, int $eventId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM event_registrations
             WHERE user_id = :user_id AND event_id = :event_id'
        );

        return $stmt->execute([
            'user_id' => $userId,
            'event_id' => $eventId,
        ]);
    }
}
