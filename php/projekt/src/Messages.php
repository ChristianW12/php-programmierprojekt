<?php
class Messages {
     
    private $dbconnection;
    private $messages;

    public function __construct($db) {
        $this->dbconnection = $db;
        $this->messages = [];
    }

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

    public function countUnreadMessages(array $messages): int
    {
        return array_reduce(
            $messages,
            static fn (int $carry, array $message): int => $carry + ((int)($message['read'] ?? 0) === 0 ? 1 : 0),
            0
        );
    }

    public function markAllAsRead(int $userId): bool
    {
        $query = 'UPDATE messages
                  SET `read` = 1
                  WHERE user_id = :userId AND `read` = 0';

        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function sendMessageWhenBidding(string $userEmail, int $offerId): bool{
        $query = 'SELECT user_id FROM offers WHERE offer_id = :offerId';
        $stmt = $this->dbconnection->prepare($query);
        $stmt->bindValue(':offerId', $offerId, \PDO::PARAM_INT);
        $stmt->execute();
        $userId = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($userId){
            $insertQuery = 'INSERT INTO messages (user_id, title, preview, time, `read`)
                            VALUES (:userId, :title, :preview, NOW(), 0)';
            $insertStmt = $this->dbconnection->prepare($insertQuery);
            $insertStmt->bindValue(':userId', $userId['user_id'], \PDO::PARAM_INT);
            $insertStmt->bindValue(':title', 'Neues Gebot erhalten', \PDO::PARAM_STR);
            $insertStmt->bindValue(':preview', 'Ein neues Gebot wurde für Ihr Angebot abgegeben von ' . $userEmail, \PDO::PARAM_STR);
            return $insertStmt->execute();
        }
    }

    // public function sendMessageWhenOfferOver($userId): bool {
    //     $query = 'offer_id,title,ende FROM offers WHERE user_id = :userId AND ende > NOW()';
    //     $stmt = $this->dbconnection->prepare($query);
    //     $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
    //     $stmt->execute();
    //     $offers = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    //     $offers.foreach(function($offer) use ($userId) {
    //         $insertQuery = 'INSERT INTO messages (user_id, title, preview, time, `read`)
    //                         VALUES (:userId, :title, :preview, NOW(), 0)';
    //         $insertStmt = $this->dbconnection->prepare($insertQuery);
    //         $insertStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
    //         $insertStmt->bindValue(':title', 'Angebot beendet', \PDO::PARAM_STR);
    //         $insertStmt->bindValue(':preview', 'Ihr Angebot "' . $offer['title'] . '" ist beendet.', \PDO::PARAM_STR);
    //         $insertStmt->execute();
    //     });
    // }
}
