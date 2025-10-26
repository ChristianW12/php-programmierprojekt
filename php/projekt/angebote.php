<?php
session_start();
require __DIR__ . '/php-code/Db.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$homeHrefPrefix = 'index.php';

$dsn = 'mysql:dbname=auktion;host=db;port=3306';

try{
    $db = new Db($dsn, 'root', '');
}catch(PDOException $e){
    echov'Verbindungsfehler: ' .$e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Aktuelle Angebote</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/angebote.css">
</head>
<body>
    <?php require __DIR__ . '/partials/header.php'; ?>
    <main>
        <section class="section">
            <div class="section-text center">
                <h1>Aktuelle Angebote</h1>
                <p>Hier sehen Sie eine Liste der aktuellsten Angebote, die von unseren Benutzern erstellt wurden.</p>
            </div>
            <div class="angebote-grid">
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
