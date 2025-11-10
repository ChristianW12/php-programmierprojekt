<?php

require_once __DIR__ . '/db-connection.php';
require_once __DIR__ . '/Angebot.php';

class UserService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = mitDBverbinden();
    }

    /**
     * Prüft, ob das übergebene Passwort zum Nutzer passt.
     */
    public function verifyPassword(int $userId, string $plainPassword): bool
    {
        $stmt = $this->db->prepare('SELECT password FROM users WHERE user_id = :user_id');
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user ? password_verify($plainPassword, $user['password'] ?? '') : false;
    }

    /**
     * Entfernt alle zum Nutzer gehörenden Daten (eigene Gebote, Angebote inkl. Bilder)
     * und löscht anschließend den Nutzer selbst.
     */
    public function deleteUser(int $userId, string $imageBasePath = ''): bool
    {
        $this->db->beginTransaction();
        try {
            $userStmt = $this->db->prepare('SELECT mail FROM users WHERE user_id = :user_id');
            $userStmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $userStmt->execute();
            $user = $userStmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                throw new RuntimeException('Nutzer wurde nicht gefunden.');
            }

            // Eigene Gebote des Nutzers entfernen (Identifikation über Mail)
            if (!empty($user['mail'])) {
                $stmtDeleteBids = $this->db->prepare('DELETE FROM bids WHERE mail = :mail');
                $stmtDeleteBids->bindValue(':mail', $user['mail'], \PDO::PARAM_STR);
                $stmtDeleteBids->execute();
            }

            // Alle eigenen Angebote inkl. Bilder löschen
            $offerStmt = $this->db->prepare('SELECT offer_id FROM offers WHERE user_id = :user_id');
            $offerStmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $offerStmt->execute();
            $offerIds = $offerStmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];

            foreach ($offerIds as $offerId) {
                $angebot = new Angebot((int)$offerId);
                $angebot->deleteOffer($userId, true, $imageBasePath);
            }

            // Nutzer endgültig entfernen
            $stmtDeleteUser = $this->db->prepare('DELETE FROM users WHERE user_id = :user_id');
            $stmtDeleteUser->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $stmtDeleteUser->execute();

            $this->db->commit();
            return true;
        } catch (\Throwable $th) {
            $this->db->rollBack();
            throw $th;
        }
    }
}
