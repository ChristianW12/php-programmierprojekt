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

    public function saveBid(): array {
        $this->dbconnection->beginTransaction();
        try {
            // 1. Notwendige Daten holen (inkl. Sperre der Angebotszeile gegen Race Conditions)
            $offerStmt = $this->dbconnection->prepare(
                'SELECT u.mail AS creator_mail, o.startpreis 
                 FROM offers o JOIN users u ON o.user_id = u.user_id 
                 WHERE o.offer_id = ? FOR UPDATE'
            );
            $offerStmt->execute([$this->offerId]);
            $offerData = $offerStmt->fetch(PDO::FETCH_ASSOC);

            if (!$offerData) { throw new Exception("Angebot wurde nicht gefunden."); }

            // Das aktuelle höchste Maximalgebot aus der `bids` Tabelle holen
            $highestBidStmt = $this->dbconnection->prepare(
                'SELECT price FROM bids WHERE offer_id = ? AND highest_price = 1'
            );
            $highestBidStmt->execute([$this->offerId]);
            $current_highest_max_bid = $highestBidStmt->fetchColumn();

            // 2. Validierung
            if ($this->bidderEmail === $offerData['creator_mail']) {
                throw new Exception("Sie können nicht auf Ihr eigenes Angebot bieten.");
            }
            if ($this->bidderPrice < $offerData['startpreis']) {
                throw new Exception("Ihr Gebot muss mindestens so hoch wie der Startpreis sein.");
            }
            
            $new_max_bid = $this->bidderPrice;

            // --- Proxy-Bidding Logik ---

            // Fall A: Dies ist das erste Gebot für den Artikel.
            if ($current_highest_max_bid === false) {
                $new_current_price = (float)$offerData['startpreis'];
                $this->insertBid(1); // Das neue Gebot als höchstes einfügen.
                $this->updateOfferPrice($new_current_price); // Der sichtbare Preis ist der Startpreis.

                $this->dbconnection->commit();
                return ['success' => true, 'message' => 'Glückwunsch, Sie sind Höchstbietender!'];
            }

            // Fall B: Es gibt bereits Gebote.
            $current_highest_max_bid = (float)$current_highest_max_bid;

            // Wenn das neue Maximalgebot NICHT HÖHER ist als das bisherige.
            if ($new_max_bid <= $current_highest_max_bid) {
                // Der sichtbare Preis steigt auf das Gebot des unterlegenen Bieters.
                $new_current_price = $new_max_bid;
                $this->insertBid(0); // Das neue Gebot als NICHT höchstes einfügen.
                $this->updateOfferPrice($new_current_price);

                $this->dbconnection->commit();
                return ['success' => true, 'message' => 'Ihr Gebot wurde angenommen, aber ein anderer Bieter hat ein höheres Maximalgebot.'];
            
            // Wenn das neue Maximalgebot HÖHER ist als das bisherige.
            } else {
                // Der neue sichtbare Preis ist das alte Höchstgebot + 1€.
                $new_current_price = $current_highest_max_bid + 1.00;
                
                $this->resetHighestBids(); // Alle alten Gebote als "nicht höchstes" markieren.
                $this->insertBid(1); // Das neue Gebot als HÖCHSTES einfügen.
                $this->updateOfferPrice($new_current_price); // Den sichtbaren Preis aktualisieren.

                $this->dbconnection->commit();
                return ['success' => true, 'message' => 'Glückwunsch, Sie sind neuer Höchstbietender!'];
            }

        } catch (Exception $e) {
            $this->dbconnection->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Hilfsmethoden zur Verbesserung der Lesbarkeit von saveBid()

    private function insertBid(int $isHighest): void {
        $stmt = $this->dbconnection->prepare('INSERT INTO bids (offer_id, mail, price, highest_price) VALUES (?, ?, ?, ?)');
        $stmt->execute([$this->offerId, $this->bidderEmail, $this->bidderPrice, $isHighest]);
    }

    private function updateOfferPrice(float $price): void {
        $stmt = $this->dbconnection->prepare('UPDATE offers SET hoechstpreis = ? WHERE offer_id = ?');
        $stmt->execute([$price, $this->offerId]);
    }

    private function resetHighestBids(): void {
        $stmt = $this->dbconnection->prepare('UPDATE bids SET highest_price = 0 WHERE offer_id = ?');
        $stmt->execute([$this->offerId]);
    }
}
