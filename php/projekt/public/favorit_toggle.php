<?php // Refactored
// Session nur starten, wenn noch keine aktive besteht
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DB-Verbindung und Favoriten-Utility laden
require __DIR__ . '/../src/db-connection.php';
require __DIR__ . '/../src/Favorit.php';

// Zugriff nur für eingeloggte User erlauben
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// User- und Angebots-ID sauber extrahieren und validieren
$user_id = (int)$_SESSION['user_id'];
$offer_id = filter_input(INPUT_POST, 'offer_id', FILTER_VALIDATE_INT);

if ($user_id <= 0 || !$offer_id || $offer_id <= 0) {
    header('Location: angebote.php?error=invalid_id');
    exit;
}
// Favorit-Helfer instanziieren
try {
    $favorit = new Favorit($db);
} catch (\Throwable $th) {
    echo "Fehler: " . $th->getMessage();
}

// Toggle-Logik: prüfen, ob Angebot schon favorisiert ist
if ($favorit->isFavorite($user_id, $offer_id)) {
    // Favorit existiert -> entfernen und passendes Feedback setzen
    if ($favorit->remove($user_id, $offer_id)) {
        header('Location: aktuelles-angebot.php?id=' . $offer_id . '&success=favourite_removed');
    } else {
        header('Location: aktuelles-angebot.php?id=' . $offer_id . '&error=favourite_remove_failed');
    }
} else {
    // Favorit existiert nicht -> hinzufügen und Erfolg/Fehler melden
    if ($favorit->add($user_id, $offer_id)) {
        header('Location: aktuelles-angebot.php?id=' . $offer_id . '&success=favourite_added');
    } else {
        header('Location: aktuelles-angebot.php?id=' . $offer_id . '&error=favourite_failed');
    }
}
exit;
