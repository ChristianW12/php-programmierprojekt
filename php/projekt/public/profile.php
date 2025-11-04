<?php
if (session_start() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['loggedin']) ){
    header('Location: index.php');
    exit;
}

// Dateien für DB-Verbindung und DB-Hilfsfunktionen einbinden
require 'src/db-connection.php';
require_once 'src/Db.php';

$db = mitDBverbinden();

// Nutzer anhand der E-Mail aus der Session laden
$stmt = $db->prepare("select* from users where mail = :mail");
$stmt->execute([':mail' => $_SESSION['user_mail']]);
// Daten vom User in einem Array speichern
$user_from_db = $stmt->fetch(PDO::FETCH_ASSOC);

$verlinkungHomepage = 'index.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Profil</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <?php require __DIR__ . '/partials/header.php'; ?>
    <main>
        <section class="section">
            <div class="section-text center profile-container">
                <h1><strong>Mein Profil</h1>
                <hr>
                <h3><strong>Persönliche Informationen</strong></h3>
                <div class="form-group-display">
                    <label>Name</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['name']); ?></div>
                </div>
                <div class="form-group-display">
                    <label>E-Mail</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['mail']); ?></div>
                </div>
                <hr>
                <h3><strong>Adresse</strong></h3>
                <div class="form-group-display">
                    <label>Straße</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['str']); ?></div>
                </div>
                <div class="form-group-display">
                    <label>Ort</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['ort']); ?></div>
                </div>
                <div class="form-group-display">
                    <label>Postleitzahl</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['plz']); ?></div>
                </div>
                <div class="profile-actions">
                    <a href="profile-edit.php" class="btn">Profil bearbeiten</a>
                    <a href="logout.php" class="btn btn-danger">Abmelden</a>
                    <a href="delete-account.php" class="btn btn-danger">Konto löschen</a>
                </div>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>