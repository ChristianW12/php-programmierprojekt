<?php
require_once __DIR__ . '/db-connection.php';
class Messages {
     
    private $dbconnection;
    private $messages;

    public function __construct() {
        $this->dbconnection = mitDBverbinden();
        $this->messages = [];
    }

    /**
     * Lädt alle Nachrichten für einen Nutzer (neueste zuerst).
     *
     * @param int $userId
     * @return array<int, array<string, mixed>>
     */
    public function getMessagesForUser(int $userId): array
    {
        $query = 'SELECT message_id, user_id, title, preview, time, `read`
                  FROM messages
                  WHERE user_id = :userId
                  ORDER BY `time` DESC';

        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        $this->messages = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        return $this->messages;
    }

    /**
     * Zählt ungelesene Nachrichten.
     *
     * @param array<int, array<string, mixed>> $messages
     * @return int
     */
    public function countUnreadMessages(array $messages): int
    {
        return array_reduce(
            $messages,
            static fn (int $carry, array $message): int => $carry + ((int)($message['read'] ?? 0) === 0 ? 1 : 0),
            0
        );
    }

    /**
     * Markiert alle Nachrichten eines Nutzers als gelesen.
     *
     * @param int $userId
     * @return bool
     */
    public function markAllAsRead(int $userId): bool
    {
        $query = 'UPDATE messages
                  SET `read` = 1
                  WHERE user_id = :userId AND `read` = 0';

        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Legt eine Nachricht für den Angebotsersteller an, wenn ein Gebot eingeht.
     *
     * @param string $userEmail
     * @param int $offerId
     * @return bool
     */
    public function sendMessageWhenBidding(string $userEmail, int $offerId): bool{
        $query = 'SELECT user_id FROM offers WHERE offer_id = :offerId';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':offerId', $offerId, \PDO::PARAM_INT);
        $stmt->execute();
        $userId = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($userId){
            $insertQuery = 'INSERT INTO messages (user_id, title, preview, time, `read`)
                            VALUES (:userId, :title, :preview, DATE_ADD(NOW(), INTERVAL 1 HOUR), 0)';
            $insertStmt = $this->dbconnection->prepare($insertQuery);
            $insertStmt->bindValue(':userId', $userId['user_id'], \PDO::PARAM_INT);
            $insertStmt->bindValue(':title', 'Neues Gebot erhalten', \PDO::PARAM_STR);
            $insertStmt->bindValue(':preview', 'Ein neues Gebot wurde für Ihr Angebot abgegeben von ' . $userEmail, \PDO::PARAM_STR);
            return $insertStmt->execute();
        }

        return false;
    }

    /**
     * Informiert Verkäufer nach Angebotsende und markiert Angebote als beendet.
     *
     * @return bool
     */
    public function sendMessageWhenOfferOver(): bool {
        $query = 'SELECT o.offer_id,
                         o.user_id,
                         o.title,
                         b.mail   AS highest_bidder_email,
                         b.price  AS highest_bid
                  FROM offers o
                  LEFT JOIN bids b ON b.offer_id = o.offer_id AND b.highest_price = 1
                  WHERE o.isOver = 0 AND o.ende <= NOW()';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->execute();
        $endedOffers = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($endedOffers)) {
            return true;
        }
        $insertMessageStmt = $this->dbconnection->prepare(
            'INSERT INTO messages (user_id, title, preview, time, `read`)
             VALUES (:userId, :title, :preview, DATE_ADD(NOW(), INTERVAL 1 HOUR), 0)'
        );
        $updateOfferStmt = $this->dbconnection->prepare(
            'UPDATE offers SET isOver = 1 WHERE offer_id = :offerId'
        );
        $this->dbconnection->beginTransaction();
        try {
            foreach ($endedOffers as $offer) {
                $offerTitle = $offer['title'] ?? 'Ihr Angebot';
                if (!empty($offer['highest_bidder_email'])) {
                    $price = number_format((float) ($offer['highest_bid'] ?? 0), 2, ',', '.');
                    $preview = sprintf(
                        'Ihr Angebot "%s" wurde beendet. Höchstbietender: %s mit %s €.',
                        $offerTitle,
                        $offer['highest_bidder_email'],
                        $price
                    );
                } else {
                    $preview = sprintf(
                        'Ihr Angebot "%s" ist ohne Gebote beendet worden.',
                        $offerTitle
                    );
                }
                $insertMessageStmt->execute([
                    ':userId' => (int) $offer['user_id'],
                    ':title' => 'Auktion beendet: ' . $offerTitle,
                    ':preview' => $preview,
                ]);
                $updateOfferStmt->execute([
                    ':offerId' => (int) $offer['offer_id'],
                ]);
            }
            $this->dbconnection->commit();
            return true;
        } catch (\Throwable $th) {
            $this->dbconnection->rollBack();
            return false;
        }
    }
}
