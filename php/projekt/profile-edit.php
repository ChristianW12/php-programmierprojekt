<?php
// Session starten: prüfen ob noch keine Session aktiv ist.
// Korrekte Prüfung mit session_status() verwenden (Originalcode hatte session_start() fälschlich in der Bedingung).
if (session_start() === PHP_SESSION_NONE){
    session_start();
}

// Prüfen ob der Nutzer eingeloggt ist, sonst Weiterleitung.
if(!isset($_SESSION['loggedin']) ){
    header('Location: index.php');
    exit;
}

require 'php-code/db-connection.php';
require_once 'php-code/Db.php';

$db = mitDBverbinden();

// Prepared Statement: sichere Abfrage des aktuellen Users anhand der Session-Mail
// Achtung: wenn 'user_mail' nicht gesetzt oder ungültig ist, liefert fetch() false
$stmt = $db->prepare("select* from users where mail = :mail");
$stmt->execute([':mail' => $_SESSION['user_mail']]);
// Daten vom User in einem Array speichern
$user_from_db = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formulardaten aus POST lese
    // Hinweis: Es fehlt hier noch serverseitige Validierung und Bereinigung (z.B. filter_var für E-Mail
    // Längenprüfungen, Pflichtfelder)
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $plz = $_POST['plz'];
    $strasse = $_POST['strasse'];
    $ort = $_POST['ort'];


    // Prepared Statement für das Update; verhindert SQL-Injection
    $updateStmt = $db->prepare("UPDATE users SET name = :name, mail = :mail, ort = :ort, plz = :plz, str = :strasse WHERE user_id = :user_id");
    $updateStmt->execute([
        ':name' => $name,
        ':mail' => $mail,
        ':ort' => $ort,
        ':plz' => $plz,
        ':strasse' => $strasse,
        ':user_id' => $user_from_db['user_id']
    ]);

    // Session-E-Mail aktualisieren, falls der Benutzer seine Mailadresse geändert hat.
    $_SESSION['user_mail'] = $mail;

    header('Location: angebote.php');
    exit;
}

$verlinkungHomepage = 'index.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Profil bearbeiten</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <?php require __DIR__ . '/partials/header.php'; ?>
    <main>
        <section class="section">
            <div class="section-text center profile-container">
                <h1><strong>Profil bearbeiten</h1>
                <hr>
                <form action="profile-edit.php" method="post">
                    <h3><strong>Persönliche Informationen</strong></h3>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" name="name" placeholder="Ihr Name" value="<?php echo htmlspecialchars($user_from_db['name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="mail">E-Mail</label>
                        <input id="mail" type="email" name="mail" placeholder="Ihre E-Mail-Adresse" value="<?php echo htmlspecialchars($user_from_db['mail']); ?>">
                    </div>
                    <hr>
                    <h3><strong>Adresse</strong></h3>
                    <div class="form-group">
                        <label for="strasse">Straße</label>
                        <input id="strasse" type="text" name="strasse" placeholder="Ihre Straße und Hausnummer" value="<?php echo htmlspecialchars($user_from_db['str']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="ort">Ort</label>
                        <input id="ort" type="text" name="ort" placeholder="Ihr Wohnort" value="<?php echo htmlspecialchars($user_from_db['ort']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="plz">Postleitzahl</label>
                        <input id="plz" type="text" name="plz" placeholder="Ihre Postleitzahl" value="<?php echo htmlspecialchars($user_from_db['plz']); ?>">
                    </div>
                    <div class="profile-actions">
                        <button type="submit" class="btn">Speichern</button>
                        <a href="profile.php" class="btn btn-danger">Abbrechen</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>