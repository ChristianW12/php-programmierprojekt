<?php

class neuesAngebotEdit
{
    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function angebotErstellen($user_id, $titel, $beschreibung, $startpreis, $ende, $db)
    {
        $query = 'INSERT INTO offers (user_id, title, beschreibung, startpreis, ende) VALUES(?,?,?,?,?)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $titel, PDO::PARAM_STR);
        $stmt->bindParam(3, $beschreibung, PDO::PARAM_STR);
        $stmt->bindParam(4, $startpreis, PDO::PARAM_STR);
        $stmt->bindParam(5, $ende, PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    // Funktion zum Verarbeiten eines einzelnen Bild-Uploads
    public function bildVerarbeiten($file_input, $neue_angebot_id, $upload_dir, $is_cover = false)
    {
        if (isset($file_input) && $file_input['error'] === UPLOAD_ERR_OK) {
            $dateiendung = pathinfo($file_input['name'], PATHINFO_EXTENSION);
            $prefix = $is_cover ? 'cover_' : 'image_';
            $neuer_dateiname = 'angebot_' . $neue_angebot_id . '_' . $prefix . time() . '.' . $dateiendung;
            $ziel_pfad = $upload_dir . $neuer_dateiname;

            if (move_uploaded_file($file_input['tmp_name'], $ziel_pfad)) {
                // Speichere den Bildpfad in der Datenbank
                $stmt = $this->db->prepare('INSERT INTO offer_images (offer_id, image_path, is_cover) VALUES (?, ?, ?)');
                $stmt->execute([$neue_angebot_id, $neuer_dateiname, (int)$is_cover]);
            }
        }
    }

    // Funktion zum Verarbeiten von mehreren Bild-Uploads
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
                        $stmt = $this->db->prepare('INSERT INTO offer_images (offer_id, image_path, is_cover) VALUES (?, ?, ?)');
                        $stmt->execute([$neue_angebot_id, $neuer_dateiname, 0]);
                    }
                }
            }
        }
    }
}

?>