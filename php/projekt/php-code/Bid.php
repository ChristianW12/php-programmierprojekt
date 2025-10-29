<?php
require_once __DIR__ . '/php-code/db-connection.php';
    class Bid {
        private $dbconnection;
        private int $offerId;
        private float $amount;
        private string $bidderEmail;
        private DateTime $bidTime;

        public function __construct(int $offerId, float $amount, string $bidderEmail) {
            $this->offerId = $offerId;
            $this->amount = $amount;
            $this->bidderEmail = $bidderEmail;
            $this->bidTime = new DateTime();
            $this->dbconnection = mitDBverbinden();
        }

        public function getOfferId(): int {
            return $this->offerId;
        }

        public function getAmount(): float {
            return $this->amount;
        }

        public function getBidderEmail(): string {
            return $this->bidderEmail;
        }

        public function getBidTime(): DateTime {
            return $this->bidTime;
        }

        public function saveBid() {
            $query = 'INSERT INTO bids (offer_id,user_id, price, bid_time) VALUES (:offer_id, :amount, :bidder_email, :bid_time)';
            $stmt = $this->dbconnection->prepare($query);
            $stmt->bindValue(':offer_id', $this->offerId, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount);
            $stmt->bindValue(':bidder_email', $this->bidderEmail);
            $stmt->bindValue(':bid_time', $this->bidTime->format('Y-m-d H:i:s'));
            return $stmt->execute();
        }
    }