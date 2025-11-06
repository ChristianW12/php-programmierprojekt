<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../src/db-connection.php';

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

// Kontrolle, ob Angebot und User existieren
$stmt_offer = $db->prepare('SELECT 1 FROM offers WHERE offer_id = :offer_id');
$stmt_offer->execute([':offer_id' => $offer_id]);
if ($stmt_offer->fetchColumn() === false) {
    header('Location: angebote.php?error=offer_not_found');
    exit;
}

$stmt_user = $db->prepare('SELECT 1 FROM users WHERE user_id = :user_id');
$stmt_user->execute([':user_id' => $user_id]);
if ($stmt_user->fetchColumn() === false) {
    // wenn user nicht existiert, session beenden und zum login weiterleiten
    unset($_SESSION['user_id']);
    unset($_SESSION['loggedin']);
    header('Location: login.php?error=user_mismatch');
    exit;
}

// kontrolle, ob Angebot bereits in Favoriten ist
$stmt_check = $db->prepare('SELECT 1 FROM favourites WHERE user_id = :user_id AND offer_id = :offer_id');
$stmt_check->execute([':user_id' => $user_id, ':offer_id' => $offer_id]);
if ($stmt_check->fetchColumn()) {
    // Potenziell: Angebot ist bereits favorisiert oder so
    header('Location: aktuelles-angebot.php?id=' . $offer_id . '&info=already_favourited');
    exit;
}

// hinzufügen des Favoriten
try {
    $stmt_add = $db->prepare('INSERT INTO favourites (user_id, offer_id) VALUES (:user_id, :offer_id)');
    $stmt_add->execute([':user_id' => $user_id, ':offer_id' => $offer_id]);
} catch (PDOException $e) {
    error_log('Error adding favourite: ' . $e->getMessage());
    header('Location: aktuelles-angebot.php?id=' . $offer_id . '&error=favourite_failed');
    exit;
}

// Umleitung zurück zum Angebot mit Erfolgsmeldung
header('Location: aktuelles-angebot.php?id=' . $offer_id . '&success=favourite_added');
exit;