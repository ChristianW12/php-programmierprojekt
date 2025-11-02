<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'php-code/db-connection.php';
require_once 'php-code/Db.php';
require_once 'php-code/neuesAngebotEdit.php';

$db = mitDBverbinden();

if (isset($_POST['angebotErstellen']) & isset($_SESSION['loggedin'])) {
    $angebotNeu = new neuesAngebotEdit($db);
    // 1. Angebot erstellen und die neue ID in einer Variable speichern
    $neue_angebot_id = $angebotNeu->angebotErstellen($_SESSION['user_id'], $_POST['titel'], $_POST['beschreibung'], (float)$_POST['startpreis'], $_POST['enddatum'], $db);

    // --- BILD-VERARBEITUNG ---

    // 2. Upload-Verzeichnis definieren und ggf. erstellen
    $upload_dir = __DIR__ . '/bilder/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // 3. Cover-Bild und weitere Bilder über die Methoden der Klasse verarbeiten
    $angebotNeu->bildVerarbeiten($_FILES['cover_bild'], $neue_angebot_id, $upload_dir, true);
    $angebotNeu->bilderVerarbeiten($_FILES['angebot_bilder'], $neue_angebot_id, $upload_dir);


    // 4. Weiterleitung nach erfolgreicher Erstellung
    header('Location: angebote.php');
    exit;

} else if (!isset($_SESSION['loggedin'])) {     // Prüfen ob der Nutzer eingeloggt ist, sonst Weiterleitung.
    header('Location: login.php');
    exit;
}


?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Neues Angebot</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/neuesAngebot.css">
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main>
    <section class="angebot-erstellen">
        <h1>Neues Angebot erstellen</h1>
        <hr>
        </br>
        <form action="neuesAngebot.php" method="post" class="angebot-formular" enctype="multipart/form-data">
            <div class="form-gruppe">
                <label for="titel">Titel des Angebots:</label>
                <input type="text" id="titel" name="titel" placeholder="z.B. Samsung A52" required>
            </div>

            <div class="form-gruppe">
                <label for="cover_bild">Cover-Bild:</label>
                <input type="file" id="cover_bild" name="cover_bild" accept="image/*">
            </div>

            <div class="form-gruppe">
                <label for="angebot_bilder">Weitere Bilder:</label>
                <input type="file" id="angebot_bilder" name="angebot_bilder[]" accept="image/*" multiple>
            </div>

            <div class="form-gruppe">
                <label for="beschreibung">Beschreibung:</label>
                <textarea id="beschreibung" name="beschreibung" rows="5" placeholder="Beschreibe dein Produkt..." required></textarea>
            </div>

            <div class="form-gruppe">
                <label for="startpreis">Startpreis (€):</label>
                <input type="number" id="startpreis" name="startpreis" step="0.01" min="0" max="99999999.99" required>
            </div>

            <div class="form-gruppe">
                <label for="enddatum">Enddatum:</label>
                <input type="datetime-local" id="enddatum" name="enddatum" required  min="<?php echo date('Y-m-d\TH:i', strtotime('+1 hour')); ?>">
            </div>

            <div class="form-actions">
                <button type="submit" name="angebotErstellen">Angebot erstellen</button>
            </div>
        </form>
    </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
