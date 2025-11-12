<?php //Refactored
require_once __DIR__ . '/db-connection.php';
class VerkaeuferBewerten
{
    // Datenbankverbindung
    public $db;

    // Konstruktor zur Initialisierung der Datenbankverbindung
    public function __construct() {
        $this->db = mitDbverbinden();
    }

    /**
     * Lädt einen Verkäufer anhand seiner ID.
     *
     * @param int $id
     * @return array<string, mixed>|false
     */
    public function verkaeuferLaden(int $id)
    {
        // Verkäufer anhand der ID aus der DB laden
        $stmt = $this->db->prepare("SELECT * from users where user_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Verkäufer-Daten als assoziatives Array zurückgeben
    }

    /**
     * Speichert eine Bewertung für den übergebenen Verkäufer anhand von POST-Daten.
     *
     * @param array<string, mixed> $verkaeufer_from_db
     * @return void
     */
    public function bewertungenSpeichern(array $verkaeufer_from_db)
    {
        // Prüfen, ob das Bewertungsformular abgeschickt wurde und der Nutzer eingeloggt ist
        if (isset($_POST['bewertungAbsenden']) && isset($_SESSION['loggedin'])) {
            $bewertung  = (int)($_POST['rating'] ?? 0);
            $kommentar    = $_POST['comment'] ?? '';
            $kommentarErsteller = $_SESSION['user_id'];
            // Bewertung nur speichern, wenn sie im gültigen Bereich liegt und der Ersteller nicht der Verkäufer ist
            if ($bewertung >= 1 && $bewertung <= 5 && $kommentarErsteller !== $verkaeufer_from_db['user_id']) {
                // Neue Bewertung in die Datenbank einfügen per Prepared Statement
                $query = $this->db->prepare("INSERT INTO user_comment (creator_id, target_id, text, rating) VALUES (:creator, :target, :text, :rating)");
                $query->execute([':creator' => $kommentarErsteller, ':target' => $verkaeufer_from_db['user_id'], ':text' => $kommentar, ':rating'  => $bewertung]);
                header("Location: nutzer-bewertungen.php?id=" . $verkaeufer_from_db['user_id']);
                exit;
            }
        }
        // Falls der Nutzer nicht eingeloggt ist, zur Login-Seite weiterleiten
        elseif (!isset($_SESSION['loggedin']) && isset($_POST['bewertungAbsenden'])) {
            header('Location: login.php');
            exit;
        }
    }
    /**
     * Lädt alle Bewertungen eines Verkäufers.
     *
     * @param array<string, mixed> $verkaeufer_from_db
     * @return array<int, array<string, mixed>>
     */
    public function bewertungenLaden(array $verkaeufer_from_db): array
    {
        // Alle Bewertungen für den Verkäufer aus der Datenbank laden
        $stmtBewertungen = $this->db->prepare("
            SELECT com.rating, com.text, com.created_at, users.name AS erstellt_von
            FROM user_comment com, users 
            WHERE com.creator_id = users.user_id AND com.target_id = :target
            ORDER BY com.created_at DESC");
        $stmtBewertungen->execute([':target' => $verkaeufer_from_db['user_id']]);
        return $stmtBewertungen->fetchAll(PDO::FETCH_ASSOC); // Alle Bewertungen als Array zurückgeben
    }
    /**
     * Berechnet das durchschnittliche Rating über alle Bewertungen.
     *
     * @param array<int, array<string, mixed>> $bewertungen
     * @return float|null
     */
    public function durchschnittlichesRating(array $bewertungen)
    {
        // Falls Bewertungen vorhanden sind, Durchschnitt berechnen
        if ($bewertungen) {
            $sum = array_sum(array_column($bewertungen, 'rating'));
            return $sum / count($bewertungen);
        } else {
            return null; // Wenn keine Bewertungen vorhanden sind, null zurückgeben
        }
    }
}
