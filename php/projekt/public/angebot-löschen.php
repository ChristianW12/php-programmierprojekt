<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../src/db-connection.php';

//Angebots-ID aus der URL holen und validieren
$angebot_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$angebot_id) {
    header('Location: angebote.php');
    exit;
}

// Kontrolle, ob Benutzer angemeldet ist
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = mitDBverbinden();

// Angebotsdetails abfragen, um den Besitzer zu überprüfen
$stmt_angebot = $db->prepare('SELECT user_id FROM offers WHERE offer_id = :id');
$stmt_angebot->execute([':id' => $angebot_id]);
$angebot = $stmt_angebot->fetch(PDO::FETCH_ASSOC);

if (!$angebot) {
    // Angebot nicht gefunden
    header('Location: angebote.php');
    exit;
}

// Weitere Prüfung: Ist der Benutzer der Ersteller oder ein Admin?
$is_owner = ($_SESSION['user_id'] == $angebot['user_id']);
$is_admin = (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1);

if (!$is_owner && !$is_admin) {
    // Nicht berechtigt
    header('Location: aktuelles-angebot.php?id=' . $angebot_id);
    exit;
}

// Löschvorgang durchführen
try {
    $db->beginTransaction();

    // Gebote löschen, falls vorhanden
    $stmt_delete_bids = $db->prepare('DELETE FROM bids WHERE offer_id = :id');
    $stmt_delete_bids->execute([':id' => $angebot_id]);

    // Bilder löschen
    $stmt_images = $db->prepare('SELECT path FROM offer_pic WHERE offer_id = :id');
    $stmt_images->execute([':id' => $angebot_id]);
    $images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

    foreach ($images as $image) {
        $image_path = __DIR__ . '/../bilder/' . $image['path'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $stmt_delete_pics = $db->prepare('DELETE FROM offer_pic WHERE offer_id = :id');
    $stmt_delete_pics->execute([':id' => $angebot_id]);

    // Angebot löschen
    $stmt_delete_offer = $db->prepare('DELETE FROM offers WHERE offer_id = :id');
    $stmt_delete_offer->execute([':id' => $angebot_id]);

    $db->commit();

} catch (Exception $e) {
    $db->rollBack();
    // Optional: Fehler loggen oder eine Fehlerseite anzeigen
    // error_log('Fehler beim Löschen des Angebots: ' . $e->getMessage());
    header('Location: aktuelles-angebot.php?id=' . $angebot_id . '&error=delete_failed');
    exit;
}

// Weiterleitung zur Angebotsübersicht
header('Location: angebote.php?status=deleted');
exit;
?>
