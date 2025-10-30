<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/php-code/Db.php';
require __DIR__ . '/php-code/Filter.php';

$dsn = 'mysql:dbname=auktion;host=db;port=3306';
$dataRows = [];

// Get active sort option from URL, default to 'neueste'
$activeSort = $_GET['sort'] ?? 'neueste';

try {
    $db = new Db($dsn, 'root', '');
    $filter = new Filter($db);

    // Handle filter application
    if (isset($_GET['preisfilter'])) {
        $minPreis = !empty($_GET['min-preis']) ? floatval($_GET['min-preis']) : 0;
        $maxPreis = !empty($_GET['max-preis']) ? floatval($_GET['max-preis']) : PHP_INT_MAX;
        $dataRows = $filter->nachPreisspanne($minPreis, $maxPreis);
    } else {
        // Apply sorting
        if ($activeSort === 'beliebteste') {
            $dataRows = $filter->nachBeliebteste();
        } else { // Default to 'neueste'
            $dataRows = $filter->nachNeuste();
        }
    }

    if (isset($_GET['neuesAngebot'])) {
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            header("Location: neuesAngebot.php");
            exit;
        } else {
            $_SESSION['last_site'] = 'angebote';
            header("Location: login.php");
            exit;
        }
    }

    if(isset($_GET['q']) && !empty(trim($_GET['q']))) {
        $suchbegriff = trim($_GET['q']);
        $dataRows = $filter->nachSuche($suchbegriff);
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

        <div class="sort-buttons">
            <?php
            // Preserve other GET parameters when changing sort order
            $queryParams = $_GET;
            $queryParams['sort'] = 'neueste';
            unset($queryParams['preisfilter']); // Avoid re-applying price filter on sort click
            $neuesteUrl = '?' . http_build_query($queryParams);
            $queryParams['sort'] = 'beliebteste';
            $beliebtesteUrl = '?' . http_build_query($queryParams);
            ?>
            <a href="<?= $neuesteUrl ?>" class="btn-sort <?= $activeSort === 'neueste' ? 'active' : '' ?>">Neueste</a>
            <a href="<?= $beliebtesteUrl ?>" class="btn-sort <?= $activeSort === 'beliebteste' ? 'active' : '' ?>">Beliebteste</a>
        </div>

        <form id="filter-form" method="get">
            <?php if ($activeSort): ?>
                <input type="hidden" name="sort" value="<?= htmlspecialchars($activeSort) ?>">
            <?php endif; ?>
            <div class="price-filter">
                <div class="price-input-group">
                    <label for="min-preis">Min. Preis:</label>
                    <input type="number" id="min-preis" name="min-preis" placeholder="0"
                           value="<?= htmlspecialchars($_GET['min-preis'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="price-input-group">
                    <label for="max-preis">Max. Preis:</label>
                    <input type="number" id="max-preis" name="max-preis" placeholder="1000"
                           value="<?= htmlspecialchars($_GET['max-preis'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <button type="submit" name="preisfilter" value="1">Preisspanne anwenden</button>
            </div>
        </form>
        <form method="get" class="neues-angebot-form">
            <button type="submit" name="neuesAngebot" class="angebot-erstellen-btn">+</button>
        </form>
    </aside>

    <main>
        <section class="section">
            <div class="section-text center">
                <h1>Aktuelle Angebote</h1>
            </div>
            <div class="angebote-grid">
                <?php if (!empty($dataRows)):
                    foreach ($dataRows as $angebot):
                        require __DIR__ . '/partials/angebot-card.php';
                    endforeach;
                else:
                    echo '<p class="keine-angebote">Keine Angebote entsprechen Ihren Kriterien.</p>';
                endif; ?>
            </div>
        </section>
    </main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
