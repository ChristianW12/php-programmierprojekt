<?php
session_start();
require __DIR__ . '/php-code/Db.php';
require __DIR__ . '/php-code/Filter.php';

$homeHrefPrefix = 'index.php';
$dsn = 'mysql:dbname=auktion;host=db;port=3306';
$dataRows = [];

try{
    $db = new Db($dsn, 'root', '');
    $filter = new Filter($db);
    $dataRows = $filter->getData();
    $angebote = orderPreis($filter);
    if($angebote){
        $dataRows = $angebote;
        foreach($dataRows as $row){
            echo $row['startpreis'];
        }
    }
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
        <form id="filter-form" method="post">
            <div class="sort-buttons">
                <button type="button" name="sort" value="neueste">Neuste</button>
                <button type="button" name="sort" value="beliebteste">Beliebteste</button>
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
                <button type="button" name="preisfilter">Preisspanne anwenden</button>
            </div>
        </form>
    </aside>
    <main>
        <section class="section">
            <div class="section-text center">
                <h1>Aktuelle Angebote</h1>
            </div>
            <div class="angebote-grid">
                <?php if (!empty($dataRows)): ?>
                    <?php foreach ($dataRows as $angebot): ?>
                        <?php require __DIR__ . '/partials/angebot-card.php'; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="keine-angebote">Derzeit sind keine Angebote verfügbar.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
<?php require __DIR__ . '/partials/footer.php'; 
function orderPreis($filter){
    $rows = $filter->nachPreisspanne(10,100);
    return $rows;
}
?>
<script src="scripts/app.js"></script>
</body>
</html>

