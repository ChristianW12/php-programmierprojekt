<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$angebotTitel = '';
$angebotBeschreibung = '';
$angebotPreis = '';
$angebotEndDatum = '';
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
    <?php require __DIR__ . '/partials/header.php'; ?>
    <main>
        <section class="section">
            <div class="section-text center profile-container">
                <h1><strong>Angebot bearbeiten</strong></h1>
                <hr>
                <form action="angebot-ändern.php" method="post">
                    <input type="hidden" name="offer_id" value="">
                    <h3><strong>Angebotsdetails</strong></h3>
                    <div class="form-group">
                        <label for="title">Titel</label>
                        <input id="title" type="text" name="title" placeholder="Titel des Angebots" value="<?php echo htmlspecialchars($angebotTitel, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="beschreibung">Beschreibung</label>
                        <textarea id="beschreibung" name="beschreibung" rows="5" placeholder="Beschreiben Sie Ihr Angebot ausführlich"><?php echo htmlspecialchars($angebotBeschreibung, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <hr>
                    <h3><strong>Preis &amp; Laufzeit</strong></h3>
                    <div class="form-group">
                        <label for="preis">Preis</label>
                        <input id="preis" type="number" step="0.01" name="preis" placeholder="Preis in Euro" value="<?php echo htmlspecialchars($angebotPreis, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="ende">Ende</label>
                        <input id="ende" type="datetime-local" name="ende" value="<?php echo htmlspecialchars($angebotEndDatum, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="profile-actions">
                        <button type="submit" class="btn">Änderungen speichern</button>
                        <a href="angebote.php" class="btn btn-danger">Abbrechen</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>
