<?php
require_once __DIR__ . '/db-connection.php';

class Bid {
    private $dbconnection;
    private int $offerId;
    private float $bidderPrice;
    private string $bidderEmail;

    public function __construct(int $offerId, float $bidderPrice, string $bidderEmail) {
        $this->offerId = $offerId;
        $this->bidderPrice = $bidderPrice;
        $this->bidderEmail = $bidderEmail;
        $this->dbconnection = mitDBverbinden();
    }

    public function getOfferId(): int {
        return $this->offerId;
    }

    public function getBidderPrice(): float {
        return $this->bidderPrice;
    }

    public function getBidderEmail(): string {
        return $this->bidderEmail;
    }

    public function saveBid(): bool {
        $is_highest_price = $this->is_highest_price();

        $query = 'INSERT INTO bids (offer_id, mail, price, highest_price)
                  VALUES (:offer_id, :bidder_email, :bid_price, :highest_price)';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
        $stmt->bindValue(':bidder_email', $this->bidderEmail, PDO::PARAM_STR);
        $stmt->bindValue(':bid_price', $this->bidderPrice);
        $stmt->bindValue(':highest_price', $is_highest_price, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    private function is_highest_price(): bool {
        $query = 'SELECT MAX(price) AS highest_price FROM bids WHERE offer_id = :offer_id';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false || $result['highest_price'] === null) {
            return true;
        }

        return $this->bidderPrice > (float)$result['highest_price'];
    }
}
