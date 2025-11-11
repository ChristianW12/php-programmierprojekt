<?php // Refactored
// Session initialisieren und benötigte Klassen laden
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// neuesAngebotEdit-Klasse einbinden

require_once __DIR__ . '/../src/neuesAngebotEdit.php';

// Prüfen ob das Formular abgeschickt wurde und der Nutzer eingeloggt ist
if (isset($_POST['angebotErstellen']) & isset($_SESSION['loggedin'])) {
    // Neues AngebotEdit-Objekt erstellen
    $angebotNeu = new neuesAngebotEdit();
    // 1. Angebot erstellen und die neue ID in einer Variable speichern
    $kategorie = !empty($_POST['kategorie']) ? $_POST['kategorie'] : null; // Kategorie ist optional, daher Inhalt prüfen und ggf. auf Null setzen

    $neue_angebot_id = $angebotNeu->angebotErstellen($_SESSION['user_id'], $_POST['titel'], $_POST['beschreibung'], $kategorie, (float)$_POST['startpreis'], $_POST['enddatum'], $db);

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

    // Prüfen ob der Nutzer eingeloggt ist, sonst Weiterleitung.
} else if (!isset($_SESSION['loggedin'])) {     
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
<?php require __DIR__ . '/../partials/header.php'; ?>
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
                <label for="kategorie">Kategorie:</label>
                <select id="kategorie" name="kategorie" required>
                    <option value="">-- Keine Kategorie --</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Computer & Zubehör">Computer & Zubehör</option>
                    <option value="Haushalt & Küche">Haushalt & Küche</option>
                    <option value="Möbel & Wohnen">Möbel & Wohnen</option>
                    <option value="Kleidung & Accessoires">Kleidung & Accessoires</option>
                    <option value="Filme & Musik">Filme & Musik</option>
                    <option value="Bücher & Comics">Bücher & Comics</option>
                    <option value="Sport & Freizeit">Sport & Freizeit</option>
                    <option value="Spielzeug & Modelle">Spielzeug & Modelle</option>
                    <option value="Sammeln & Antiquitäten">Sammeln & Antiquitäten</option>
                    <option value="Fahrzeuge & Zubehör">Fahrzeuge & Zubehör</option>
                    <option value="Musik & Instrumente">Musik & Instrumente</option>
                    <option value="Tierbedarf">Tierbedarf</option>
                    <option value="Reisen & Gepäck">Reisen & Gepäck</option>
                    <option value="Sonstiges">Sonstiges</option>
                </select>
            </div>

            <div class="form-gruppe">
                <label for="startpreis">Startpreis (€):</label>
                <input type="number" id="startpreis" name="startpreis" step="0.01" min="0" max="99999999.99" required>
            </div>

            <div class="form-gruppe">
                <label for="enddatum">Enddatum:</label>
                <input type="datetime-local" id="enddatum" name="enddatum" required
                       min="<?php echo date('Y-m-d\TH:i', strtotime('+1 hour')); ?>" <!-- Enddatum mindestens 1 Stunde in der Zukunft -->
                       max="<?php echo date('Y-m-d\TH:i', strtotime('+1 year')); ?>"> <!-- Enddatum maximal 1 Jahr in der Zukunft -->
            </div>

            <div class="form-actions">
                <button type="submit" name="angebotErstellen">Angebot erstellen</button>
            </div>
        </form>
    </section>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
