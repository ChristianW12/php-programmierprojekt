<?php
/**
 * Template für eine Angebotskarte.
 *
 * Erwartet eine Variable $angebot (array) im Scope mit den Keys:
 * title, beschreibung, startpreis, start, ende
 */

if (!isset($angebot)) {
    return;
}

// --- Anfang: Bildabfrage für Cover-Bild ---
// Stellt eine Verbindung zur Datenbank her. `require_once` verhindert, dass die Datei mehrfach geladen wird.
require_once __DIR__ . '/../src/db-connection.php';
$db = mitDBverbinden();

$coverImage = null;
$angebotId = isset($angebot['offer_id']) ? (int) $angebot['offer_id'] : null;

if ($angebotId) {
    // Bereitet die SQL-Abfrage vor, um das Cover-Bild zu finden (is_cover = 1).
    $stmt_image = $db->prepare('SELECT path FROM offer_pic WHERE offer_id = :id AND is_cover = 1');
    // Führt die Abfrage sicher mit der Angebots-ID aus.
    $stmt_image->execute(['id' => $angebotId]);
    // Holt das Ergebnis. fetch() wird verwendet, da wir nur ein Bild erwarten.
    $coverImage = $stmt_image->fetch(PDO::FETCH_ASSOC);
}
// --- Ende: Bildabfrage ---

$titel = htmlspecialchars($angebot['title'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$beschreibung = htmlspecialchars($angebot['beschreibung'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$startpreisWert = isset($angebot['startpreis']) ? (float) $angebot['startpreis'] : null;
$startpreis = $startpreisWert !== null
    ? number_format($startpreisWert, 2, ',', '.') . ' €'
    : 'Preis unbekannt';

$angebotId = isset($angebot['offer_id']) ? (int) $angebot['offer_id'] : null;

$startDatum = 'Startdatum offen';
if (!empty($angebot['start'])) {
    try {
        $startDatum = (new DateTime($angebot['start']))->format('d.m.Y H:i');
    } catch (Exception $exception) {
        $startDatum = htmlspecialchars($angebot['start'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$endeDatum = 'Enddatum offen';
if (!empty($angebot['ende'])) {
    try {
        $endeDatum = (new DateTime($angebot['ende']))->format('d.m.Y H:i');
    } catch (Exception $exception) {
        $endeDatum = htmlspecialchars($angebot['ende'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$angebotBesitzerId = isset($angebot['user_id']) ? (int) $angebot['user_id'] : null;
$istEigenerAnbieter = isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] === $angebotBesitzerId;
?>
<style>
    /* Stil für das Cover-Bild in der Angebotskarte */
    .angebot-card .angebot-image {
        width: 100%; 
        height: 200px;
        object-fit: cover; 
    }
</style>
<article class="angebot-card">
    <h3 class="angebot-title">
        <a href="aktuelles-angebot.php?id=<?= $angebotId ?>"><?= $titel ?></a>
    </h3>
    <?php if ($coverImage && !empty($coverImage['path'])):
        // Zeigt das Bild nur an, wenn ein Pfad in der Datenbank gefunden wurde
    ?>
        <img src="bilder/<?php echo htmlspecialchars($coverImage['path']); ?>" alt="Cover-Bild für <?php echo $titel; ?>" class="angebot-image">
    <?php endif; ?>
    <dl class="angebot-meta">
        <div class="meta-item price-item">
            <dt>Startpreis</dt>
            <dd><?= $startpreis ?></dd>
        </div>
        <div class="meta-item date-item">
            <dt>Start</dt>
            <dd><?= $startDatum ?></dd>
        </div>
        <div class="meta-item date-item">
            <dt>Ende</dt>
            <dd><?= $endeDatum ?></dd>
        </div>
    </dl>
    <?php if ($istEigenerAnbieter): ?>
        <form method="get" action="angebot-ändern.php">
            <?php if ($angebotId !== null): ?>
                <input type="hidden" name="offer_id" value="<?= htmlspecialchars((string) $angebotId, ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; ?>
            <button type="submit" class="edit-button">Angebot ändern</button>
        </form>
    <?php endif; ?>
    <form method="get" action="angebot-bieten.php">
        <?php if ($angebotId !== null): ?>
            <input type="hidden" name="offer_id" value="<?= htmlspecialchars((string) $angebotId, ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>
    </form>
</article>
