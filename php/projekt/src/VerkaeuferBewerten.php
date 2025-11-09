<?php

class VerkaeuferBewerten
{
    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function verkaeuferLaden(int $id)
    {
        // Verkäufer anhand der ID aus der DB laden
        $stmt = $this->db->prepare("SELECT * from users where user_id = :id");
        $stmt->execute([':id' => $id]);
        // Daten vom Verkäufer in einem Array speichern
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function bewertungenSpeichern(array $verkaeufer_from_db)
    {
        if (isset($_POST['bewertungAbsenden']) && isset($_SESSION['loggedin'])) {
            $bewertung  = (int)($_POST['rating'] ?? 0);
            $kommentar    = $_POST['comment'] ?? '';
            $kommentarErsteller = $_SESSION['user_id'];

            if ($bewertung >= 1 && $bewertung <= 5 && $kommentarErsteller !== $verkaeufer_from_db['user_id']) {
                $query = $this->db->prepare("INSERT INTO user_comment (creator_id, target_id, text, rating) VALUES (:creator, :target, :text, :rating)");
                $query->execute([':creator' => $kommentarErsteller, ':target' => $verkaeufer_from_db['user_id'], ':text' => $kommentar, ':rating'  => $bewertung]);
                header("Location: nutzer-bewertungen.php?id=" . $verkaeufer_from_db['user_id']);
                exit;
            }
        }
        elseif (!isset($_SESSION['loggedin']) && isset($_POST['bewertungAbsenden'])) {
            header('Location: login.php');
            exit;
        }
    }

    public function bewertungenLaden(array $verkaeufer_from_db)
    {
        $stmtBewertungen = $this->db->prepare("
            SELECT com.rating, com.text, com.created_at, users.name AS erstellt_von
            FROM user_comment com, users 
            WHERE com.creator_id = users.user_id AND com.target_id = :target
            ORDER BY com.created_at DESC");
        $stmtBewertungen->execute([':target' => $verkaeufer_from_db['user_id']]);
        return $stmtBewertungen->fetchAll(PDO::FETCH_ASSOC);
    }

    public function durchschnittlichesRating(array $bewertungen)
    {
        if ($bewertungen) {
            $sum = array_sum(array_column($bewertungen, 'rating'));
            return $sum / count($bewertungen);
        } else {
            return null;
        }
    }
}