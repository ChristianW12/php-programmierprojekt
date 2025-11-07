<?php

class Favorit {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function add(int $userId, int $offerId): bool {
        if ($this->isFavorite($userId, $offerId)) {
            return true; // Already a favorite
        }

        try {
            $stmt = $this->db->prepare('INSERT INTO favourites (user_id, offer_id) VALUES (:user_id, :offer_id)');
            return $stmt->execute([':user_id' => $userId, ':offer_id' => $offerId]);
        } catch (PDOException $e) {
            error_log('Error adding favourite: ' . $e->getMessage());
            return false;
        }
    }

    public function remove(int $userId, int $offerId): bool {
        try {
            $stmt = $this->db->prepare('DELETE FROM favourites WHERE user_id = :user_id AND offer_id = :offer_id');
            return $stmt->execute(['user_id' => $userId, 'offer_id' => $offerId]);
        } catch (PDOException $e) {
            error_log('Error removing favourite: ' . $e->getMessage());
            return false;
        }
    }

    public function isFavorite(int $userId, int $offerId): bool {
        $stmt = $this->db->prepare('SELECT 1 FROM favourites WHERE user_id = :user_id AND offer_id = :offer_id');
        $stmt->execute([':user_id' => $userId, ':offer_id' => $offerId]);
        return (bool)$stmt->fetchColumn();
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare('SELECT o.* FROM offers o JOIN favourites f ON o.offer_id = f.offer_id WHERE f.user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
