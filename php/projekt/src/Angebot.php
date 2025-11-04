<?php
require_once __DIR__ . '/db-connection.php';

class Angebot {
    private $dbconnection;
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
   