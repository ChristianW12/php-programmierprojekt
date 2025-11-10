<?php
// Session initialisieren und Angebotsservice laden
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../src/Angebot.php';

// Angebots-ID aus der URL lesen und validieren
$angebot_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$angebot_id) {
    header('Location: angebote.php');
    exit;
}

// Löschen nur für eingeloggte Nutzer erlauben
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Berechtigungs- und Pfadinfos vorbereiten
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1;
$imageBasePath = __DIR__ . '/../bilder';

try {
    // Angebotsservice erzeugen und Löschung anstoßen
    $angebotService = new Angebot((int)$angebot_id);
    $angebotService->deleteOffer((int)$_SESSION['user_id'], $isAdmin, $imageBasePath);

    // Erfolgreich: zurück zur Übersicht mit Statusflag
    header('Location: angebote.php?status=deleted');
    exit;
} catch (RuntimeException $e) {
    // Für fehlende Rechte oder nicht vorhandene Angebote zur Detailseite zurück
    header('Location: aktuelles-angebot.php?id=' . $angebot_id);
    exit;
} catch (Throwable $th) {
    // Sonstige Fehler loggen und Fehlermeldung anzeigen
    error_log('Fehler beim Löschen des Angebots: ' . $th->getMessage());
    header('Location: aktuelles-angebot.php?id=' . $angebot_id . '&error=delete_failed');
    exit;
}
