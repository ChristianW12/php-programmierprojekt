<?php // Refactored
// Session initialisieren und benötigte Klassen laden
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $_SESSION['last_site'] = 'angebot-bearbeiten';
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/Angebot.php';

// Default-Werte für Formularfelder initialisieren
$angebotTitel = '';
$angebotBeschreibung = '';
$angebotPreis = '';
$angebotEndDatum = '';
$angebotKategorie = '';

// Angebots-ID aus GET/POST lesen
$angebotId = $_GET['id'] ?? ($_POST['offer_id'] ?? null);
$currentUserId = (int)($_SESSION['user_id'] ?? 0);
$isAdmin = isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1;
$angebotBesitzerId = null;

// Angebotsdaten laden, um Formular vorzufüllen
try {
    $angebot = new Angebot((int)$angebotId);
    $angebotDaten = $angebotId ? $angebot->getOfferWithId() : null;
    if ($angebotDaten) {
        // Formularfelder mit bestehenden Daten füllen
        $angebotTitel = $angebotDaten['title'] ?? '';
        $angebotBeschreibung = $angebotDaten['beschreibung'] ?? '';
        $angebotPreis = $angebotDaten['startpreis'] ?? '';
        $angebotEndDatum = str_replace(' ', 'T', $angebotDaten['ende'] ?? '');
        $angebotKategorie = $angebotDaten['kategorie'] ?? '';
        $angebotBesitzerId = (int)($angebotDaten['user_id'] ?? 0);
    }
} catch (Exception $e) {
    error_log("Fehler beim Laden des Angebots: " . $e->getMessage());
}

if (!$angebotDaten) {
    header('Location: angebote.php');
    exit;
}

if (!$isAdmin && $angebotBesitzerId !== $currentUserId) {
    header('Location: aktuelles-angebot.php?id=' . urlencode((string)$angebotId));
    exit;
}

// Formular-Submit verarbeiten und Änderungen speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['offer_id']) && !empty($_POST['offer_id'])) {
    $offerId = (int)$_POST['offer_id'];
    $title = $_POST['title'] ?? '';
    $beschreibung = $_POST['beschreibung'] ?? '';
    $preis = (float)($_POST['preis'] ?? 0);
    $ende = $_POST['ende'] ?? '';

    try {
        // Angebot aktualisieren über die Angebot-Klasse
        $res = $angebot->updateOffer($offerId, $title, $beschreibung, $preis, $ende);
        if ($res) {
            header("Location: angebote.php");
            exit();
        } else {
            echo "Fehler beim Aktualisieren des Angebots.";
        }
    } catch (Exception $e) {
        error_log("Fehler beim Aktualisieren des Angebots: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Angebot bearbeiten</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main>
        <section class="section">
            <div class="section-text center profile-container">
                <h1><strong>Angebot bearbeiten</strong></h1>
                <hr>
                <form action="angebot-ändern.php" method="post">
                    <input type="hidden" name="offer_id"
                        value="<?php echo htmlspecialchars($angebotId, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-group">
                        <label for="title">Titel</label>
                        <input id="title" type="text" name="title" placeholder="Titel des Angebots" value="<?php echo htmlspecialchars($angebotTitel, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="beschreibung">Beschreibung</label>
                        <textarea id="beschreibung" name="beschreibung" required rows="5" placeholder="Beschreiben Sie Ihr Angebot ausführlich"><?php echo htmlspecialchars($angebotBeschreibung, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <hr>
                    <h3><strong>Preis &amp; Laufzeit</strong></h3>
                    <div class="form-group">
                        <label for="preis">Preis</label>
                        <input id="preis" type="number" step="0.01" min="0" max="99999999.99" name="preis" placeholder="Preis in Euro" value="<?php echo htmlspecialchars($angebotPreis, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="ende">Ende</label>
                        <input id="ende" type="datetime-local" name="ende" required
                               value="<?php echo htmlspecialchars($angebotEndDatum, ENT_QUOTES, 'UTF-8'); ?>"
                               min="<?php echo date('Y-m-d\TH:i', strtotime('+1 hour')); ?>"
                               max="<?php echo date('Y-m-d\TH:i', strtotime('+1 year')); ?>">
                    </div>
                    <div class="profile-actions">
                        <button type="submit" class="btn">Änderungen speichern</button>
                        <a href="angebote.php" class="btn btn-danger">Abbrechen</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>
