<?php

declare(strict_types=1);

class Event extends Model
{
    public function all(?int $categoryId = null): array
    {
        $sql = 'SELECT e.*, c.name AS category_name
                FROM events e
                INNER JOIN categories c ON c.id = e.category_id';
        $params = [];

        if ($categoryId !== null) {
            $sql .= ' WHERE e.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $sql .= ' ORDER BY e.event_date ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function upcoming(int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            'SELECT e.*, c.name AS category_name
             FROM events e
             INNER JOIN categories c ON c.id = e.category_id
             WHERE e.event_date >= NOW()
             ORDER BY e.event_date ASC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT e.*, c.name AS category_name
             FROM events e
             INNER JOIN categories c ON c.id = e.category_id
             ORDER BY e.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT e.*, c.name AS category_name
             FROM events e
             INNER JOIN categories c ON c.id = e.category_id
             WHERE e.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $event = $stmt->fetch();

        return $event ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO events (category_id, title, description, location, event_date, image)
             VALUES (:category_id, :title, :description, :location, :event_date, :image)'
        );

        return $stmt->execute($this->mapData($data));
    }

    public function update(int $id, array $data): bool
    {
        $params = $this->mapData($data);
        $params['id'] = $id;

        $stmt = $this->db->prepare(
            'UPDATE events
             SET category_id = :category_id,
                 title = :title,
                 description = :description,
                 location = :location,
                 event_date = :event_date,
                 image = :image
             WHERE id = :id'
        );

        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM events WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM events')->fetchColumn();
    }

    public function countUpcoming(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM events WHERE event_date >= NOW()')->fetchColumn();
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM events WHERE category_id = :category_id');
        $stmt->execute(['category_id' => $categoryId]);

        return (int) $stmt->fetchColumn();
    }

    private function mapData(array $data): array
    {
        return [
            'category_id' => (int) $data['category_id'],
            'title' => trim((string) $data['title']),
            'description' => trim((string) $data['description']),
            'location' => trim((string) $data['location']),
            'event_date' => str_replace('T', ' ', (string) $data['event_date']),
            'image' => trim((string) ($data['image'] ?? '')),
        ];
    }
}
