<?php // Halbwegs Refactored (Christian nochmal eigenen Code anschauen)
require_once __DIR__ . '/db-connection.php';
class AngebotErsteller
{
    // Datenbankverbindung
    public $db;

    // Konstruktor zur Initialisierung der Datenbankverbindung
    public function __construct() {
        $this->db = mitDbverbinden();
    }

    /**
     * Legt ein neues Angebot inkl. Basisdaten an und gibt dessen ID zurück.
     *
     * @param int $user_id
     * @param string $titel
     * @param string $beschreibung
     * @param string|null $kategorie
     * @param float $startpreis
     * @param string $ende
     * @param \PDO $db
     * @return int|string
     */
    public function angebotErstellen($user_id, $titel, $beschreibung, $kategorie, $startpreis, $ende)
    {
        $query = 'INSERT INTO offers (user_id, title, beschreibung, startpreis, ende, kategorie) VALUES(?,?,?,?,?,?)'; // SQL-Query zum Einfügen eines neuen Angebots
        //Query vorbereiten, Parameter binden und Query ausführen
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $titel, PDO::PARAM_STR);
        $stmt->bindParam(3, $beschreibung, PDO::PARAM_STR);
        $stmt->bindParam(4, $startpreis, PDO::PARAM_STR);
        $stmt->bindParam(5, $ende, PDO::PARAM_STR);
        $stmt->bindParam(6, $kategorie, PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId(); // Rückgabe der ID des neu erstellten Angebots
    }

    /**
     * Speichert ein einzelnes hochgeladenes Bild samt Cover-Flag zum Angebot.
     *
     * @param array $file_input
     * @param int $neue_angebot_id
     * @param string $upload_dir
     * @param bool $is_cover
     * @return void
     */
    public function bildVerarbeiten($file_input, $neue_angebot_id, $upload_dir, $is_cover = false)
    {
        // Überprüfen, ob eine Datei hochgeladen wurde
        try {
            if (isset($file_input) && $file_input['error'] === UPLOAD_ERR_OK) {
            $dateiendung = pathinfo($file_input['name'], PATHINFO_EXTENSION);
            $prefix = $is_cover ? 'cover_' : 'image_';
            $neuer_dateiname = 'angebot_' . $neue_angebot_id . '_' . $prefix . time() . '.' . $dateiendung;
            $ziel_pfad = $upload_dir . $neuer_dateiname;

            if (move_uploaded_file($file_input['tmp_name'], $ziel_pfad)) {
                // Speichere den Bildpfad in der Datenbank
                $stmt = $this->db->prepare('INSERT INTO offer_pic (offer_id, path, is_cover) VALUES (?, ?, ?)');
                $stmt->execute([$neue_angebot_id, $neuer_dateiname, (int)$is_cover]);
            }
        }
        }
        catch (\Throwable $th) {
            echo "Fehler beim Hochladen des Bildes: " . $th->getMessage();
        }
    }

    /**
     * Persistiert mehrere zusätzliche Angebotsbilder.
     *
     * @param array $files_input
     * @param int $neue_angebot_id
     * @param string $upload_dir
     * @return void
     */
    public function bilderVerarbeiten($files_input, $neue_angebot_id, $upload_dir)
    {
        if (isset($files_input)) {
            foreach ($files_input['tmp_name'] as $key => $tmp_name) {
                if ($files_input['error'][$key] === UPLOAD_ERR_OK) {
                    $dateiendung = pathinfo($files_input['name'][$key], PATHINFO_EXTENSION);
                    $neuer_dateiname = 'angebot_' . $neue_angebot_id . '_image_' . time() . '_' . $key . '.' . $dateiendung;
                    $ziel_pfad = $upload_dir . $neuer_dateiname;

                    if (move_uploaded_file($tmp_name, $ziel_pfad)) {
                        // Speichere den Bildpfad in der Datenbank (is_cover = 0)
                        $stmt = $this->db->prepare('INSERT INTO offer_pic (offer_id, path, is_cover) VALUES (?, ?, ?)');
                        $stmt->execute([$neue_angebot_id, $neuer_dateiname, 0]);
                    }
                }
            }
        }
    }
}

?>
