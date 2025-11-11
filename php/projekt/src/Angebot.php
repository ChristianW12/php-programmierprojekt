<?php
require_once __DIR__ . '/db-connection.php';

class Angebot {
    private \PDO $dbconnection;
    private int $offerId;

    public function __construct(int $offerId) {
        $this->offerId = $offerId;
        $this->dbconnection = mitDBverbinden();
    }

    /**
     * Gibt das aktuelle Angebot als Array zurück.
     *
     * @return array<string, mixed>|false
     */
    public function getOfferWithId(){
        if(!isset($this->offerId)){
            throw new InvalidArgumentException("Offer ID is not set.");
        }
        $query = 'SELECT * FROM offers WHERE offer_id = :offer_id';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Prüft, ob das Angebot als Favorit für den angegebenen Nutzer existiert.
     *
     * @param int $userId
     * @return bool
     */
    public function isFavoritForUser(int $userId): bool {
        $query = 'SELECT 1 FROM favourites WHERE user_id = :user_id AND offer_id = :offer_id';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Lädt alle Bilder zum Angebot, Cover zuerst.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getOfferImages(): array {
        $query = 'SELECT * FROM offer_pic WHERE offer_id = :offer_id ORDER BY is_cover DESC';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    /**
     * Aktualisiert Kerndaten des Angebots.
     *
     * @param int $offerId
     * @param string $title
     * @param string $beschreibung
     * @param float $startpreis
     * @param string $ende
     * @return bool
     */
    public function updateOffer($offerId, $title, $beschreibung, $startpreis, $ende): bool {
        $query = 'UPDATE offers 
                  SET title = :title, beschreibung = :beschreibung, startpreis = :startpreis, ende = :ende 
                  WHERE offer_id = :offer_id';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':beschreibung', $beschreibung, PDO::PARAM_STR);
        $stmt->bindValue(':startpreis', $startpreis);
        $stmt->bindValue(':ende', $ende, PDO::PARAM_STR);
        $stmt->bindValue(':offer_id', $offerId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Löscht das Angebot inklusive Geboten/Bildern nach Berechtigungsprüfung.
     *
     * @param int $currentUserId
     * @param bool $isAdmin
     * @param string $imageBasePath
     * @return bool
     */
    public function deleteOffer(int $currentUserId, bool $isAdmin = false, string $imageBasePath = ''): bool
    {
        $stmt = $this->dbconnection->prepare('SELECT user_id FROM offers WHERE offer_id = :offer_id');
        $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
        $stmt->execute();
        $offer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$offer) {
            throw new RuntimeException('Angebot wurde nicht gefunden.');
        }

        if (!$isAdmin && (int)$offer['user_id'] !== $currentUserId) {
            throw new RuntimeException('Keine Berechtigung zum Löschen dieses Angebots.');
        }

        $imageBasePath = $imageBasePath !== ''
            ? rtrim($imageBasePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            : '';

        try {
            $this->dbconnection->beginTransaction();

            // Alle Gebote zum Angebot entfernen
            $stmtDeleteBids = $this->dbconnection->prepare('DELETE FROM bids WHERE offer_id = :offer_id');
            $stmtDeleteBids->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
            $stmtDeleteBids->execute();

            // Bildpfade laden, Dateien löschen und Einträge entfernen
            $stmtImages = $this->dbconnection->prepare('SELECT path FROM offer_pic WHERE offer_id = :offer_id');
            $stmtImages->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
            $stmtImages->execute();
            $images = $stmtImages->fetchAll(PDO::FETCH_ASSOC) ?: [];

            foreach ($images as $image) {
                if ($imageBasePath === '') {
                    break; // ohne Pfadangabe keine Dateien löschen
                }
                $imagePath = $imageBasePath . $image['path'];
                if (is_file($imagePath)) {
                    @unlink($imagePath);
                }
            }

            $stmtDeletePics = $this->dbconnection->prepare('DELETE FROM offer_pic WHERE offer_id = :offer_id');
            $stmtDeletePics->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
            $stmtDeletePics->execute();

            // Angebot entfernen
            $stmtDeleteOffer = $this->dbconnection->prepare('DELETE FROM offers WHERE offer_id = :offer_id');
            $stmtDeleteOffer->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
            $stmtDeleteOffer->execute();

            $this->dbconnection->commit();
            return true;
        } catch (\Throwable $th) {
            $this->dbconnection->rollBack();
            throw $th;
        }
    }
}
   
