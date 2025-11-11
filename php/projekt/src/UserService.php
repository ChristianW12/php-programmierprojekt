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
     * Authentifiziert einen Nutzer anhand Mail/Passwort und kümmert sich um Legacy-Rehashes.
     *
     * @return array{success: bool, user?: array<string, mixed>, message?: string}
     */
    public function loginUser(string $mail, string $plainPassword): array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE mail = :mail');
        $stmt->bindValue(':mail', $mail, \PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Ungültiger Benutzername oder Passwort. Versuchen Sie es nochmal.',
            ];
        }

        $storedPassword = (string)($user['password'] ?? '');
        $needsRehash = password_needs_rehash($storedPassword, PASSWORD_DEFAULT);

        if ($needsRehash) {
            // Altpasswörter wurden im Klartext gespeichert -> direkter Vergleich
            if ($plainPassword !== $storedPassword) {
                return [
                    'success' => false,
                    'message' => 'Ungültiger Benutzername oder Passwort. Versuchen Sie es nochmal.',
                ];
            }

            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
            $updateStmt = $this->db->prepare('UPDATE users SET password = :password WHERE user_id = :user_id');
            $updateStmt->bindValue(':password', $hashedPassword, \PDO::PARAM_STR);
            $updateStmt->bindValue(':user_id', (int)$user['user_id'], \PDO::PARAM_INT);
            $updateStmt->execute();
            $storedPassword = $hashedPassword;
        } elseif (!password_verify($plainPassword, $storedPassword)) {
            return [
                'success' => false,
                'message' => 'Ungültiger Benutzername oder Passwort. Versuchen Sie es nochmal.',
            ];
        }

        unset($user['password']);

        return [
            'success' => true,
            'user' => $user,
        ];
    }

    /**
     * Lädt einen Nutzer anhand seiner Mail-Adresse.
     *
     * @return array<string, mixed>|null
     */
    public function getUserByMail(string $mail): ?array
    {
        $mail = trim($mail);
        if ($mail === '') {
            return null;
        }

        $stmt = $this->db->prepare('SELECT * FROM users WHERE mail = :mail LIMIT 1');
        $stmt->bindValue(':mail', $mail, \PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

        if ($user) {
            unset($user['password']); // Passwort nie an Aufrufer zurückgeben
        }

        return $user;
    }

    /**
     * Aktualisiert die Stammdaten eines Nutzers.
     *
     * @param array{name?: string, mail?: string, ort?: string, plz?: string, str?: string} $profileData
     */
    public function updateUserProfile(int $userId, array $profileData): bool
    {
        $payload = [
            'name' => trim($profileData['name'] ?? ''),
            'mail' => trim($profileData['mail'] ?? ''),
            'ort' => trim($profileData['ort'] ?? ''),
            'plz' => trim($profileData['plz'] ?? ''),
            'str' => trim($profileData['str'] ?? ''),
        ];

        if ($userId <= 0 || in_array('', $payload, true)) {
            return false;
        }

        $stmt = $this->db->prepare(
            'UPDATE users
             SET name = :name, mail = :mail, ort = :ort, plz = :plz, str = :strasse
             WHERE user_id = :user_id'
        );

        return $stmt->execute([
            ':name' => $payload['name'],
            ':mail' => $payload['mail'],
            ':ort' => $payload['ort'],
            ':plz' => $payload['plz'],
            ':strasse' => $payload['str'],
            ':user_id' => $userId,
        ]);
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
