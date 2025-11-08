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
}
