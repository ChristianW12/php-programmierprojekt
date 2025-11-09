<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dateien für DB-Verbindung, DB-Hilfsfunktionen einbinden, und VerkaeuferBewerten-Klasse einbinden
require __DIR__ . '/../src/db-connection.php';
require_once __DIR__ . '/../src/Db.php';
require_once __DIR__ . '/../src/VerkaeuferBewerten.php';

// DB-Verbindung herstellen
$db = mitDBverbinden();

// VerkaeuferBewerten-Objekt erstellen
$verkaeuferBewerten = new VerkaeuferBewerten($db);

// Verkäufer anhand der ID aus der DB laden, falls dieser nicht existiert, zurück zur Startseite
$verkaeufer_from_db = $verkaeuferBewerten->verkaeuferLaden($_GET['id'] ?? 0);

if(!$verkaeufer_from_db){
    header('Location: index.php');
    exit;
}

// Letzte besuchte Seite in der Session speichern für Weiterleitung nach dem Login
$_SESSION['last_site'] = 'nutzer-bewerten';
$_SESSION['URL_Bewertungen'] = 'nutzer-bewertungen.php?id=' . $verkaeufer_from_db['user_id'];

// Bewertungen für den Verkäufer in der DB speichern
$verkaeuferBewerten->bewertungenSpeichern($verkaeufer_from_db);

// Bewertungen aus der DB laden
$bewertungen = $verkaeuferBewerten->bewertungenLaden($verkaeufer_from_db);

// Durchschnittliches Rating für die Sterne berechnen
$avgRating = $verkaeuferBewerten->durchschnittlichesRating($bewertungen);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Verkäuferprofil</title>
    <link rel="stylesheet" href="styles/verkaeufer.css">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main>
    <div class="verkaeufer-container">

        <h1>Profil von <?php echo htmlspecialchars($verkaeufer_from_db['name']) ?></h1>

        <section class="info-block">
            <h2>Verkäufer-Informationen</h2>
            <label>Name</label>
            <div class="info-field"> <?php echo htmlspecialchars($verkaeufer_from_db['name']) ?></div>

            <label>E-Mail</label>
            <div class="info-field"> <?php echo htmlspecialchars($verkaeufer_from_db['mail']) ?></div>
        </section>

        <section class="rating-section">
            <h2>Bewertungen</h2>
            <div class="rating-summary">
                <?php
                if ($avgRating === null) {
                    echo 'Keine Bewertungen vorhanden.';
                } ?>
                <div class="stars">
                <?php
                if ($avgRating !== null) {
                    echo str_repeat('★', (int)round($avgRating)) . str_repeat('☆', 5 - (int)round($avgRating));
                } ?>
                </div>
                <?php
                if ($avgRating !== null) {
                    echo number_format($avgRating, 1, ',') . ' / 5 (' . count($bewertungen) . ')';
                }
                ?>
            </div>
            <?php if($bewertungen !== null): ?>
            <ul class="rating-list">
                <?php foreach ($bewertungen as $bew): ?>
                        <li>
                            <div class="rating-line">
                                <?= str_repeat('★', $bew['rating']) . str_repeat('☆', 5 - $bew['rating']) ?>
                                <span class="rating-date"><?php echo (new DateTime($bew['created_at']))->format('d.m.Y H:i') ?></span>
                            </div>
                            <div class="rating-author"> von <?php echo htmlspecialchars($bew['erstellt_von']) ?></div>
                            <p><?php  echo nl2br(htmlspecialchars(wordwrap($bew['text'], 40, "\n", true))) //Bei zu langen Wörtern/Texten zeilenumbruch einfügen ?></p>
                        </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <form method="post" class="rating-form">
                <label for="rating">Bewertung:</label>
                <select name="rating" id="rating" required>
                    <option value="">Bitte wählen</option>
                    <option value="5">★★★★★ (5)</option>
                    <option value="4">★★★★☆ (4)</option>
                    <option value="3">★★★☆☆ (3)</option>
                    <option value="2">★★☆☆☆ (2)</option>
                    <option value="1">★☆☆☆☆ (1)</option>
                </select>
                <label for="comment">Rezension (optional):</label>
                <textarea name="comment" id="comment" rows="3" maxlength="200" style="resize: none;" placeholder="Wie war Ihre Erfahrung?"></textarea>
                <button type="submit" name="bewertungAbsenden">Bewertung abgeben</button>
            </form>
        </section>
        <p><a href="angebote.php">← Zurück zu den Angeboten</a></p>
    </div>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
