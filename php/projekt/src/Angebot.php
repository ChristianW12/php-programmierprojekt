<?php
require_once __DIR__ . '/db-connection.php';

class Angebot {
    private \PDO $dbconnection;
    private int $offerId;

    public function __construct(int $offerId) {
        $this->offerId = $offerId;
        $this->dbconnection = mitDBverbinden();
    }

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

    public function isFavoritForUser(int $userId): bool {
        $query = 'SELECT 1 FROM favourites WHERE user_id = :user_id AND offer_id = :offer_id';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function getOfferImages(): array {
        $query = 'SELECT * FROM offer_pic WHERE offer_id = :offer_id ORDER BY is_cover DESC';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }

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
}
   
