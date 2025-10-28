<?php
if (session_start() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['loggedin']) ){
    header('Location: index.php');
    exit;
}

require 'php-code/db-connection.php';
require_once 'php-code/Db.php';

$db = mitDBverbinden();

$stmt = $db->prepare("select* from users where mail = :mail");
$stmt->execute([':mail' => $_SESSION['user_mail']]);
// Daten vom User in einem Array speichern
$user_from_db = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $ort = $_POST['ort'];
    $plz = $_POST['plz'];
    $strasse = $_POST['strasse'];

    $updateStmt = $db->prepare("UPDATE users SET name = :name, mail = :mail, ort = :ort, plz = :plz, str = :strasse WHERE user_id = :user_id");
    $updateStmt->execute([
        ':name' => $name,
        ':mail' => $mail,
        ':ort' => $ort,
        ':plz' => $plz,
        ':strasse' => $strasse,
        ':user_id' => $user_from_db['user_id']
    ]);

    $_SESSION['user_mail'] = $mail; // Update session email if it was changed

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
                    <p><strong>Name: </strong> <input type="text" name="name" value="<?php echo htmlspecialchars($user_from_db['name']); ?>"></p>
                    <p><strong>E-Mail: </strong> <input type="email" name="mail" value="<?php echo htmlspecialchars($user_from_db['mail']); ?>"></p>
                    <hr>
                    <h3><strong>Adresse</strong></h3>
                    <p><strong>Ort: </strong><input type="text" name="ort" value="<?php echo htmlspecialchars($user_from_db['ort']) ?>"></p>
                    <p><strong>Postleitzahl: </strong><input type="text" name="plz" value="<?php echo htmlspecialchars($user_from_db['plz']) ?>"></p>
                    <p><strong>Straße: </strong> <input type="text" name="strasse" value="<?php echo htmlspecialchars($user_from_db['str']) ?>"></p>
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