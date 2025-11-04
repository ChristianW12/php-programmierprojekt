<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'php-code/db-connection.php';

// 1. Angebots-ID aus der URL holen und validieren
$angebot_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$angebot_id) {
    header('Location: angebote.php');
    exit;
}

$db = mitDBverbinden();

// 2. Angebotsdetails aus der `offers`-Tabelle abfragen
$stmt_angebot = $db->prepare('SELECT * FROM offers WHERE offer_id = :id');
$stmt_angebot->execute([':id' => $angebot_id]);
$angebot = $stmt_angebot->fetch(PDO::FETCH_ASSOC);

if (!$angebot) {
    $error_message = "Das angeforderte Angebot konnte nicht gefunden werden.";
} else {
    // 3. Bilder zum Angebot aus der Datenbank abfragen (Tabelle `offer_pic`)
    $stmt_images = $db->prepare('SELECT * FROM offer_pic WHERE offer_id = :id ORDER BY is_cover DESC');
    $stmt_images->execute([':id' => $angebot_id]);
    $images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);
}

// Helper-Funktion zum Formatieren von Daten
function formatDate($dateString) {
    if (empty($dateString)) return 'N/A';
    try {
        return (new DateTime($dateString))->format('d.m.Y H:i') . ' Uhr';
    } catch (Exception $e) {
        return 'Ungültiges Datum';
    }
}

// Helper-Funktion zum Formatieren von Preisen
function formatPrice($price) {
    if ($price === null || $price === '') return 'N/A';
    return number_format((float)$price, 2, ',', '.') . ' €';
}

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auktify | <?php echo isset($angebot['title']) ? htmlspecialchars($angebot['title']) : 'Angebot nicht gefunden'; ?></title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/angebote.css">
    <link rel="stylesheet" href="styles/aktuelles-angebot.css">
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="angebot-detail-container">
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php elseif ($angebot): ?>
            <div class="detail-wrapper">
                <div class="detail-content">
                    <div class="angebot-details">
                        <h1><?php echo htmlspecialchars($angebot['title']); ?></h1>

                        <p class="beschreibung">
                            <?php echo nl2br(htmlspecialchars($angebot['beschreibung'])); ?>
                        </p>

                        <dl class="angebot-meta">
                            <div class="meta-item">
                                <dt>Startpreis</dt>
                                <dd><?php echo formatPrice($angebot['startpreis']); ?></dd>
                            </div>
                            <div class="meta-item">
                                <dt>Auktionsstart</dt>
                                <dd><?php echo formatDate($angebot['start']); ?></dd>
                            </div>
                            <div class="meta-item">
                                <dt>Auktionsende</dt>
                                <dd><?php echo formatDate($angebot['ende']); ?></dd>
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
                                <form method="get" action="angebot-bieten.php">
                                    <input type="hidden" name="offer_id" value="<?= htmlspecialchars((string) $angebot['offer_id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="bieten-button">Jetzt bieten</button>
                                </form>
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
    </div>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
