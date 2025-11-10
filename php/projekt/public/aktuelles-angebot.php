<?php
// Session initialisieren und benötigte Klassen laden
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../src/Angebot.php';
require __DIR__ . '/../src/Helper.php';

// Angebots-ID einlesen und Basisvariablen vorbereiten
$angebot_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$angebot = null;
$images = [];
$error_message = null;

// Ungültige IDs sofort zurück zur Übersicht schicken
if (!$angebot_id) {
    header('Location: angebote.php');
    exit;
}

// Angebotsdaten aus der Datenbank laden
try {
    $angebotService = new Angebot($angebot_id);
    $angebot = $angebotService->getOfferWithId();
} catch (\Throwable $th) {
    error_log('Fehler beim Laden des Angebots: ' . $th->getMessage());
}

$angebotTitle = is_array($angebot) ? ($angebot['title'] ?? null) : null;

// Favoritenstatus nur für eingeloggte Nutzer abfragen
$is_favorit = false;
if ($angebot && isset($_SESSION['user_id'])) {
    try {
        $is_favorit = $angebotService->isFavoritForUser((int)$_SESSION['user_id']);
    } catch (\Throwable $th) {
        error_log('Fehler beim Prüfen des Favoritenstatus: ' . $th->getMessage());
    }
}

// Link zum Verkäuferprofil vorbereiten
$anbieterProfilLink = ($angebot && isset($angebot['user_id']))
    ? 'nutzer-bewertungen.php?id=' . $angebot['user_id']
    : 'nutzer-bewertungen.php';

// Bilder zum Angebot laden, falls Daten vorhanden sind
if (!$angebot) {
    $error_message = "Das angeforderte Angebot konnte nicht gefunden werden.";
} else {
    try {
        $images = $angebotService->getOfferImages();
    } catch (\Throwable $th) {
        error_log('Fehler beim Laden der Angebotsbilder: ' . $th->getMessage());
        $images = [];
    }
}

// Startpreis für Darstellungen vorbereiten
$startpreisWert = (is_array($angebot) && isset($angebot['startpreis']))
    ? (float) $angebot['startpreis']
    : null;
$startpreis = $startpreisWert !== null
    ? number_format($startpreisWert, 2, ',', '.') . ' €'
    : 'Preis unbekannt';

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auktify | <?php echo $angebotTitle ? htmlspecialchars($angebotTitle) : 'Angebot nicht gefunden'; ?></title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/angebote.css">
    <link rel="stylesheet" href="styles/aktuelles-angebot.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main>
    <div class="angebot-detail-container">
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php elseif ($angebot): ?>
            <div class="detail-wrapper">
                <div class="detail-content">
                    <div class="angebot-details">
                        <div class="title-row">
                            <h1><?php echo htmlspecialchars($angebot['title']); ?></h1>
                            <a href="<?= $anbieterProfilLink ?>" class="icon-button" aria-label="Verkäuferprofil anzeigen">👤</a>
                        </div>

                        <p class="beschreibung">
                            <?php echo nl2br(htmlspecialchars($angebot['beschreibung'])); ?>
                        </p>

                        <dl class="angebot-meta">
                            <div class="meta-item">
                                <dt>Startpreis</dt>
                                <dd><?php echo Helper::formatPrice($angebot['startpreis']); ?></dd>
                            </div>
                            <div class="meta-item">
                                <dt>Auktionsstart</dt>
                                <dd><?php echo Helper::formatDate($angebot['start']); ?></dd>
                            </div>
                            <div class="meta-item">
                                <dt>Auktionsende</dt>
                                <dd><?php echo Helper::formatDate($angebot['ende']); ?></dd>
                            </div>
                        </dl>
                        <!-- Überprüfung ob der User der Ersteller ist oder ob er Admin ist, wenn eins zutrifft, darf er löschen oder bearbeiten--> 
                        <?php if ((isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $angebot['user_id'])): ?>
                            <div class="owner-actions">
                                <a href="angebot-ändern.php?id=<?php echo $angebot['offer_id']; ?>" class="button">Angebot bearbeiten</a>
                                <a href="angebot-löschen.php?id=<?php echo $angebot['offer_id']; ?>" class="button button-danger" onclick="return confirm('Sind Sie sicher, dass Sie dieses Angebot wirklich löschen möchten?');">Angebot löschen</a>
                            </div>
                        <?php else: ?>
                            <div class="bidding-action">
                                <form method="get" action="angebot-bieten.php" style="display: inline-block;">
                                    <input type="hidden" name="offer_id" value="<?= htmlspecialchars((string) $angebot['offer_id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="startpreis" value="<?= htmlspecialchars(number_format($startpreisWert, 2, '.', ''), ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="bieten-button">Jetzt bieten</button>
                                </form>
                                <?php if ($is_favorit): ?>
                                    <form method="post" action="favorit_toggle.php" style="display: inline-block;">
                                        <input type="hidden" name="offer_id" value="<?= htmlspecialchars((string) $angebot['offer_id'], ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit" class="favourite-button-remove">★ Aus Favoriten</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" action="favorit_toggle.php" style="display: inline-block;">
                                        <input type="hidden" name="offer_id" value="<?= htmlspecialchars((string) $angebot['offer_id'], ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit" class="favourite-button">⭐ Zu Favoriten</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($images)): ?>
                    <div class="detail-images">
                        <h3>Bilder</h3>
                        <?php foreach ($images as $image): ?>
                            <img src="bilder/<?php echo htmlspecialchars($image['path']); ?>" alt="Bild für <?php echo htmlspecialchars($angebot['title']); ?>">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div style="margin-bottom: 20px; padding: 10px;">
            <a href="angebote.php" class="back-link" style="text-decoration: none; color: #898989; font-weight: bold;">← Zurück zur Übersicht</a>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
