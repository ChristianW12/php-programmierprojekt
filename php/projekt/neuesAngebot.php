<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
        <form action="angebotSpeichern.php" method="post" class="angebot-formular">
            <div class="form-gruppe">
                <label for="titel">Titel des Angebots:</label>
                <input type="text" id="titel" name="titel" placeholder="z. B. iPhone 14 Pro" required>
            </div>

            <div class="form-gruppe">
                <label for="beschreibung">Beschreibung:</label>
                <textarea id="beschreibung" name="beschreibung" rows="5" placeholder="Beschreibe dein Produkt..." required></textarea>
            </div>

            <div class="form-gruppe">
                <label for="startpreis">Startpreis (€):</label>
                <input type="number" id="startpreis" name="startpreis" step="0.01" min="0" required>
            </div>

            <div class="form-gruppe">
                <label for="startdatum">Startdatum:</label>
                <input type="datetime-local" id="startdatum" name="startdatum" required>
            </div>

            <div class="form-gruppe">
                <label for="endedatum">Endedatum:</label>
                <input type="datetime-local" id="endedatum" name="endedatum" required>
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
