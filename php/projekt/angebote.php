<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/php-code/Db.php';
require __DIR__ . '/php-code/Filter.php';

$dsn = 'mysql:dbname=auktion;host=db;port=3306';
$dataRows = [];

try {
    $db = new Db($dsn, 'root', '');
    $filter = new Filter($db);

    $dataRows = $filter->getData();

    if (isset($_POST['neuesAngebot'])) {
        if($_SESSION['loggedin']) {
            header("Location: neuesAngebot.php");
            exit;
        } else {
            $_SESSION['last_site'] = 'angebote';
            header("Location: login.php");
            exit;
        }

    }


    if (!isset($_SESSION['sort_neueste_aktiv'])) {
        $_SESSION['sort_neueste_aktiv'] = false;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['min-preis']) && !empty($_POST['max-preis'])) {
            $minPreis = floatval($_POST['min-preis']);
            $maxPreis = floatval($_POST['max-preis']);
            $dataRows = $filter->nachPreisspanne($minPreis, $maxPreis);
        }

        if (isset($_POST['sort'])) {
            $sortOption = $_POST['sort'];
            if ($sortOption === 'neueste') {
                $_SESSION['sort_neueste_aktiv'] = !$_SESSION['sort_neueste_aktiv'];
            }
        }
    }

    if ($_SESSION['sort_neueste_aktiv']) {
        $dataRows = $filter->nachNeuste();
    }

} catch (PDOException $e) {
    echo 'Verbindungsfehler: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
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
                <button type="submit"
                        name="sort"
                        value="neueste">
                    Neueste
                </button>

                <button type="submit" name="sort" value="beliebteste">Beliebteste</button>
            </div>

            <div class="price-filter">
                <div class="price-input-group">
                    <label for="min-preis">Min. Preis:</label>
                    <input type="number" id="min-preis" name="min-preis" placeholder="0"
                           value="<?= htmlspecialchars($_POST['min-preis'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="price-input-group">
                    <label for="max-preis">Max. Preis:</label>
                    <input type="number" id="max-preis" name="max-preis" placeholder="1000"
                           value="<?= htmlspecialchars($_POST['max-preis'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <button type="submit" name="preisfilter" value="1">Preisspanne anwenden</button>
                <button type="submit" name="neuesAngebot">Angebot erstellen</button>
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
                        <div class="angebot-card-wrapper">
                            <?php require __DIR__ . '/partials/angebot-card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="keine-angebote">Derzeit sind keine Angebote verfügbar.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
