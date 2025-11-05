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
        // Eine Transaktion wird gestartet, um die Datenintegrität zu sichern.
        $this->dbconnection->beginTransaction();

        try {
            // --- KORREKTUR: Alle benötigten Daten werden zu Beginn gelesen, um Fehler zu vermeiden. ---
            $stmt = $this->dbconnection->prepare(
                'SELECT u.mail AS creator_mail, o.hoechstpreis, o.startpreis 
                 FROM offers o JOIN users u ON o.user_id = u.user_id 
                 WHERE o.offer_id = ?'
            );
            $stmt->execute([$this->offerId]);
            $offerData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$offerData) {
                throw new Exception("Angebot wurde nicht gefunden.");
            }

            // Die gelesenen Daten werden Variablen zugewiesen.
            $creatorMail = $offerData['creator_mail'];
            $aktuellerHoechstpreis = $offerData['hoechstpreis']; // Kann null sein.
            $aktuellerStartpreis = $offerData['startpreis'];

            // --- DEINE LOGIK, ÜBERARBEITET UND KORRIGIERT ---

            // Abfrage, ob der Bieter der Ersteller des Angebots ist.
            if ($this->bidderEmail === $creatorMail) {
                throw new Exception("Sie können nicht auf Ihr eigenes Angebot bieten.");
            }

            // Fall 1: Es gab noch kein Gebot (hoechstpreis in offers ist NULL).
            if ($aktuellerHoechstpreis === null) {
                if ($this->bidderPrice < $aktuellerStartpreis) {
                    throw new Exception("Ihr Gebot muss mindestens so hoch wie der Startpreis sein.");
                }

                // Der Preis in `offers` wird auf das Gebot gesetzt.
                $updateStmt = $this->dbconnection->prepare('UPDATE offers SET hoechstpreis = ? WHERE offer_id = ?');
                $updateStmt->execute([$this->bidderPrice, $this->offerId]);
                
                // Das Gebot wird als erstes und höchstes in die `bids`-Tabelle eingetragen.
                $insertStmt = $this->dbconnection->prepare('INSERT INTO bids (offer_id, mail, price, highest_price) VALUES (?, ?, ?, 1)');
                $insertStmt->execute([$this->offerId, $this->bidderEmail, $this->bidderPrice]);

            // Fall 2: Es gibt bereits Gebote.
            } else {
                // Das neue Gebot muss höher sein als das aktuelle Höchstgebot.
                if ($this->bidderPrice > $aktuellerHoechstpreis) {
                    // Der Preis in `offers` wird auf das neue Gebot aktualisiert.
                    $updateStmt = $this->dbconnection->prepare('UPDATE offers SET hoechstpreis = ? WHERE offer_id = ?');
                    $updateStmt->execute([$this->bidderPrice, $this->offerId]);

                    // Alle anderen Gebote für dieses Angebot werden als "nicht höchstes" markiert.
                    $resetStmt = $this->dbconnection->prepare('UPDATE bids SET highest_price = 0 WHERE offer_id = ?');
                    $resetStmt->execute([$this->offerId]);

                    // Das neue Gebot wird als höchstes eingetragen.
                    $insertStmt = $this->dbconnection->prepare('INSERT INTO bids (offer_id, mail, price, highest_price) VALUES (?, ?, ?, 1)');
                    $insertStmt->execute([$this->offerId, $this->bidderEmail, $this->bidderPrice]);
                } else {
                    // Das Gebot ist nicht hoch genug. Es wird als nicht-höchstes Gebot gespeichert.
                    $insertStmt = $this->dbconnection->prepare('INSERT INTO bids (offer_id, mail, price, highest_price) VALUES (?, ?, ?, 0)');
                    $insertStmt->execute([$this->offerId, $this->bidderEmail, $this->bidderPrice]);
                    
                    // Transaktion bestätigen und spezielle Nachricht zurückgeben.
                    $this->dbconnection->commit();
                    return ['success' => true, 'message' => 'Gebot gespeichert, Sie sind aber nicht Höchstbietender.'];
                }
            }

            // Wenn alles erfolgreich war, wird die Transaktion bestätigt.
            $this->dbconnection->commit();
            return ['success' => true, 'message' => 'Glückwunsch, Sie sind Höchstbietender!'];

        } catch (Exception $e) {
            // Bei einem Fehler werden alle Änderungen zurückgerollt.
            $this->dbconnection->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
