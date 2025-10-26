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
    echo'Verbindungsfehler: ' .$e->getMessage();
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
<div class="angebote-container">
    <aside class="filter-bereich">
        <h2>Filter</h2>
        <form id="filter-form">
            <div class="sort-buttons">
                <button type="button">Neuste</button>
                <button type="button">Beliebteste</button>
            </div>
            <div class="price-filter">
                <div class="price-input-group">
                    <label for="min-preis">Min. Preis:</label>
                    <input type="number" id="min-preis" name="min-preis" placeholder="0">
                </div>
                <div class="price-input-group">
                    <label for="max-preis">Max. Preis:</label>
                    <input type="number" id="max-preis" name="max-preis" placeholder="1000">
                </div>
                <button type="submit">Preisspanne anwenden</button>
            </div>
        </form>
    </aside>
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
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
