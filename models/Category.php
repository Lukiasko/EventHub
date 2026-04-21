<?php

declare(strict_types=1);

class Category extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT c.id, c.name, c.created_at, COUNT(e.id) AS events_count
             FROM categories c
             LEFT JOIN events e ON e.category_id = c.id
             GROUP BY c.id, c.name, c.created_at
             ORDER BY c.name ASC'
        );

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();

        return $category ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO categories (name) VALUES (:name)');

        return $stmt->execute(['name' => $data['name']]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE categories SET name = :name WHERE id = :id');

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    }
}
