<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../src/Db.php';
require __DIR__ . '/../src/Filter.php';
require __DIR__ . '/../src/Messages.php';

$dsn = 'mysql:dbname=auktion;host=db;port=3306';
$dataRows = [];
$activeSort = $_GET['sort'] ?? 'neueste';
$gewaehlteKategorie = $_GET['kategorie'] ?? '';

try {
    $db = new Db($dsn, 'root', '');
    $filter = new Filter($db);
    $messagesService = new Messages($db);
    $messagesService->sendMessageWhenOfferOver();

    if (!empty($gewaehlteKategorie)) {
        $dataRows = $filter->nachKategorie($gewaehlteKategorie);
    } elseif (isset($_GET['preisfilter'])) {
        $minPreis = !empty($_GET['min-preis']) ? floatval($_GET['min-preis']) : 0;
        $maxPreis = !empty($_GET['max-preis']) ? floatval($_GET['max-preis']) : PHP_INT_MAX;
        $dataRows = $filter->nachPreisspanne($minPreis, $maxPreis);
    } else {
        if ($activeSort === 'beliebteste') {
            $dataRows = $filter->nachBeliebteste();
        }elseif($activeSort === 'meineAngebote'){
            if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
                header("Location: login.php");
                $_SESSION['last_site'] = 'meine angebote';
                exit;
            }
            $dataRows = $filter->nachMeineAngebote($_SESSION['user_id']);
        } elseif ($activeSort === 'favoriten') {
            if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
                header("Location: login.php");
                $_SESSION['last_site'] = 'favoriten';
                exit;
            }
            $dataRows = $filter->nachFavoriten($_SESSION['user_id']);
        } else {
            $dataRows = $filter->nachNeuste();
        }
    }

    if (isset($_GET['neuesAngebot'])) {
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            header("Location: neuesAngebot.php");
            exit;
        } else {
            $_SESSION['last_site'] = 'angebot erstellen';
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
<?php require __DIR__ . '/../partials/header.php'; ?>
<div class="angebote-container">
    <aside class="filter-bereich">
        <h2>Filter</h2>

        <div class="filter-section">
            <h3 class="filter-title">Sortierung</h3>
            <div class="sort-buttons">
                <?php
                $queryParams = $_GET;

                //Kategorien-Filter immer entfernen
                unset($queryParams['kategorie']);

                // "Neueste"
                $queryParams['sort'] = 'neueste';
                unset($queryParams['preisfilter']); // wie gehabt
                $neuesteUrl = '?' . http_build_query($queryParams);

                // "Beliebteste"
                $queryParams['sort'] = 'beliebteste';
                $beliebtesteUrl = '?' . http_build_query($queryParams);

                // "Meine"
                $queryParams['sort'] = 'meineAngebote';
                $meineUrl = '?' . http_build_query($queryParams);

                // "Favoriten"
                $queryParams['sort'] = 'favoriten';
                $favoritenUrl = '?' . http_build_query($queryParams);

                // "Kategorien"
                $queryParams = $_GET;
                unset($queryParams['kategorie']);

                ?>
                <a href="<?= $neuesteUrl ?>" class="btn-sort <?= $activeSort === 'neueste' ? 'active' : '' ?>">Neueste</a>
                <a href="<?= $beliebtesteUrl ?>" class="btn-sort <?= $activeSort === 'beliebteste' ? 'active' : '' ?>">Beliebteste</a>
                <a href="<?= $meineUrl ?>" class="btn-sort <?= $activeSort === 'meineAngebote' ? 'active' : '' ?>">Meine Angebote</a>
                <a href="<?= $favoritenUrl ?>" class="btn-sort <?= $activeSort === 'favoriten' ? 'active' : '' ?>">Favoriten</a>
            </div>
        </div>

        <div class="filter-section">
            <h3 class="filter-title">Kategorie</h3>
            <form method="get" style="margin:0;">
                <input type="hidden" name="sort" value="neueste">
                <select id="kategorie" name="kategorie" class="btn-sort" onchange="this.form.submit()">
                    <option value="">Auswählen</option>
                    <option value="Elektronik" <?= (($_GET['kategorie'] ?? '') === 'Elektronik') ? 'selected' : '' ?>>Elektronik</option>
                    <option value="Computer & Zubehör" <?= (($_GET['kategorie'] ?? '') === 'Computer & Zubehör') ? 'selected' : '' ?>>Computer & Zubehör</option>
                    <option value="Haushalt & Küche" <?= (($_GET['kategorie'] ?? '') === 'Haushalt & Küche') ? 'selected' : '' ?>>Haushalt & Küche</n>
                    <option value="Möbel & Wohnen" <?= (($_GET['kategorie'] ?? '') === 'Möbel & Wohnen') ? 'selected' : '' ?>>Möbel & Wohnen</option>
                    <option value="Kleidung & Accessoires" <?= (($_GET['kategorie'] ?? '') === 'Kleidung & Accessoires') ? 'selected' : '' ?>>Kleidung & Accessoires</option>
                    <option value="Filme & Musik" <?= (($_GET['kategorie'] ?? '') === 'Filme & Musik') ? 'selected' : '' ?>>Filme & Musik</option>
                    <option value="Bücher & Comics" <?= (($_GET['kategorie'] ?? '') === 'Bücher & Comics') ? 'selected' : '' ?>>Bücher & Comics</option>
                    <option value="Sport & Freizeit" <?= (($_GET['kategorie'] ?? '') === 'Sport & Freizeit') ? 'selected' : '' ?>>Sport & Freizeit</option>
                    <option value="Spielzeug & Modelle" <?= (($_GET['kategorie'] ?? '') === 'Spielzeug & Modelle') ? 'selected' : '' ?>>Spielzeug & Modelle</option>
                    <option value="Sammeln & Antiquitäten" <?= (($_GET['kategorie'] ?? '') === 'Sammeln & Antiquitäten') ? 'selected' : '' ?>>Sammeln & Antiquitäten</option>
                    <option value="Fahrzeuge & Zubehör" <?= (($_GET['kategorie'] ?? '') === 'Fahrzeuge & Zubehör') ? 'selected' : '' ?>>Fahrzeuge & Zubehör</option>
                    <option value="Musik & Instrumente" <?= (($_GET['kategorie'] ?? '') === 'Musik & Instrumente') ? 'selected' : '' ?>>Musik & Instrumente</option>
                    <option value="Tierbedarf" <?= (($_GET['kategorie'] ?? '') === 'Tierbedarf') ? 'selected' : '' ?>>Tierbedarf</option>
                    <option value="Reisen & Gepäck" <?= (($_GET['kategorie'] ?? '') === 'Reisen & Gepäck') ? 'selected' : '' ?>>Reisen & Gepäck</option>
                    <option value="Sonstiges" <?= (($_GET['kategorie'] ?? '') === 'Sonstiges') ? 'selected' : '' ?>>Sonstiges</option>
                </select>
            </form>
        </div>

        <div class="filter-section">
            <h3 class="filter-title">Preisspanne</h3>
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
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="angebote.php" class="reset-link">Filter zurücksetzen</a>
        </div>
    </aside>

    <main>
        <section class="section">
            <div class="section-text center">
                <h1>Aktuelle Angebote</h1>
            </div>
            <div class="angebote-grid">
                <?php if (!empty($dataRows)):
                    foreach ($dataRows as $angebot):
                        require __DIR__ . '/../partials/angebot-card.php';
                    endforeach;
                else:
                    echo '<p class="keine-angebote">Keine Angebote entsprechen Ihren Kriterien.</p>';
                endif; ?>
            </div>
        </section>
    </main>
</div>
<a href="neuesAngebot.php" class="add-offer-fab">+</a>
<?php require __DIR__ . '/../partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
