<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../src/db-connection.php';
require __DIR__ . '/../src/Favorit.php';

// Kontrolle, ob User angemeldet ist
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// IDs validieren
$user_id = (int)$_SESSION['user_id'];
$offer_id = filter_input(INPUT_POST, 'offer_id', FILTER_VALIDATE_INT);

if ($user_id <= 0 || !$offer_id || $offer_id <= 0) {
    header('Location: angebote.php?error=invalid_id');
    exit;
}

$db = mitDBverbinden();
$favorit = new Favorit($db);

// Überprüfen, ob das Angebot bereits als Favorit markiert ist
if ($favorit->isFavorite($user_id, $offer_id)) {
    // Wenn es ein Favorit ist, entfernen
    if ($favorit->remove($user_id, $offer_id)) {
        header('Location: aktuelles-angebot.php?id=' . $offer_id . '&success=favourite_removed');
    } else {
        header('Location: aktuelles-angebot.php?id=' . $offer_id . '&error=favourite_remove_failed');
    }
} else {
    // Wenn nicht als Favorit, hinzufügen
    if ($favorit->add($user_id, $offer_id)) {
        header('Location: aktuelles-angebot.php?id=' . $offer_id . '&success=favourite_added');
    } else {
        header('Location: aktuelles-angebot.php?id=' . $offer_id . '&error=favourite_failed');
    }
}
exit;
