<?php
if (session_start() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['loggedin']) ){
    header('Location: index.php');
    exit;
}

require 'php-code/db-connection.php';

$db = mitDBverbinden();

$stmt = $db->prepare("select* from users where mail = :mail");
$stmt->execute([':mail' => $_SESSION['user_mail']]);
// Daten vom User in einem Array speichern
$user_from_db = $stmt->fetch(Db::FETCH_ASSOC);

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
                <p><strong>Name: </strong> <?php echo $user_from_db['name']; ?></p>
                <p><strong>E-Mail: </strong> <?php echo $user_from_db['mail']; ?></p>
                <hr>
                <h3><strong>Adresse</strong></h3>
                <p><strong>Ort: </strong><?php echo $user_from_db['ort'] ?></p>
                <p><strong>Postleitzahl: </strong><?php echo $user_from_db['plz'] ?></p>
                <p><strong>Straße: </strong> <?php echo $user_from_db['str'] ?></p>
            </div>
            <div class="profile-actions">
                <button class="btn">Profil bearbeiten</button>
                <button class="btn btn-danger">Abmelden</button>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>
