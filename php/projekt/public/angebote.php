<?php // Refactored
// Session starten und Services laden
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../src/Filter.php';
require __DIR__ . '/../src/Messages.php';

// Standardwerte für Filter/Suche vorbereiten
$activeSort = $_GET['sort'] ?? 'neueste';
$gewaehlteKategorie = $_GET['kategorie'] ?? '';
$minPreis = !empty($_GET['min-preis']) ? floatval($_GET['min-preis']) : null;
$maxPreis = !empty($_GET['max-preis']) ? floatval($_GET['max-preis']) : null;
$suchbegriff = isset($_GET['q']) && !empty(trim($_GET['q'])) ? trim($_GET['q']) : null;

try {
    // Filter- und Nachrichten-Service initialisieren
    $filter = new Filter();
    $messagesService = new Messages();
    $messagesService->sendMessageWhenOfferOver();

    // Die Filter werden nun verketten, anstatt sich gegenseitig auszuschließen
    // Zuerst wird die Basissortierung angewendet
    if ($activeSort === 'beliebteste') {
        $filter->nachBeliebteste();
    } elseif ($activeSort === 'meineAngebote') {
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
            header("Location: login.php");
            $_SESSION['last_site'] = 'meine angebote';
            exit;
        }
        $filter->nachMeineAngebote($_SESSION['user_id']);
    } elseif ($activeSort === 'favoriten') {
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
            header("Location: login.php");
            $_SESSION['last_site'] = 'favoriten';
            exit;
        }
        $filter->nachFavoriten($_SESSION['user_id']);
    } else {
        $filter->nachNeuste();
    }

    // Zusätzliche Filter werden an die Abfrage angehängt
    if (!empty($gewaehlteKategorie)) {
        $filter->nachKategorie($gewaehlteKategorie);
    }

    if (isset($_GET['preisfilter'])) {
        $minPreis = $minPreis ?? 0;
        $maxPreis = $maxPreis ?? PHP_INT_MAX;
        $filter->nachPreisspanne($minPreis, $maxPreis);
    }

    if ($suchbegriff) {
        $filter->nachSuche($suchbegriff);
    }

    // Die Ergebnisse werden am Ende abgerufen, nachdem alle Filter angewendet wurden
    $dataRows = $filter->getResults();

    // Direktlink zum Erstellen eines Angebots behandeln
    if (isset($_GET['neuesAngebot'])) {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            header("Location: neuesAngebot.php");
            exit;
        } else {
            $_SESSION['last_site'] = 'angebot erstellen';
            header("Location: login.php");
            exit;
        }
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

                // "Neueste"
                $queryParams['sort'] = 'neueste';
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
                <?php
                /**
                 * Erzeugt versteckte Eingabefelder für alle aktuellen Query-Parameter
                 * außer `kategorie`, damit beim Wechsel der Kategorie andere Filter erhalten bleiben
                 */
                foreach ($_GET as $key => $value) {
                    if ($key !== 'kategorie') {
                        echo '<input type="hidden" name="' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '">';
                    }
                }
                ?>
                <select id="kategorie" name="kategorie" class="btn-sort" onchange="this.form.submit()">
                    <option value="">Auswählen</option>
                    <option value="Elektronik" <?= ($gewaehlteKategorie === 'Elektronik') ? 'selected' : '' ?>>Elektronik</option>
                    <option value="Computer & Zubehör" <?= ($gewaehlteKategorie === 'Computer & Zubehör') ? 'selected' : '' ?>>Computer & Zubehör</option>
                    <option value="Haushalt & Küche" <?= ($gewaehlteKategorie === 'Haushalt & Küche') ? 'selected' : '' ?>>Haushalt & Küche</n>
                    <option value="Möbel & Wohnen" <?= ($gewaehlteKategorie === 'Möbel & Wohnen') ? 'selected' : '' ?>>Möbel & Wohnen</option>
                    <option value="Kleidung & Accessoires" <?= ($gewaehlteKategorie === 'Kleidung & Accessoires') ? 'selected' : '' ?>>Kleidung & Accessoires</option>
                    <option value="Filme & Musik" <?= ($gewaehlteKategorie === 'Filme & Musik') ? 'selected' : '' ?>>Filme & Musik</option>
                    <option value="Bücher & Comics" <?= ($gewaehlteKategorie === 'Bücher & Comics') ? 'selected' : '' ?>>Bücher & Comics</option>
                    <option value="Sport & Freizeit" <?= ($gewaehlteKategorie === 'Sport & Freizeit') ? 'selected' : '' ?>>Sport & Freizeit</option>
                    <option value="Spielzeug & Modelle" <?= ($gewaehlteKategorie === 'Spielzeug & Modelle') ? 'selected' : '' ?>>Spielzeug & Modelle</option>
                    <option value="Sammeln & Antiquitäten" <?= ($gewaehlteKategorie === 'Sammeln & Antiquitäten') ? 'selected' : '' ?>>Sammeln & Antiquitäten</option>
                    <option value="Fahrzeuge & Zubehör" <?= ($gewaehlteKategorie === 'Fahrzeuge & Zubehör') ? 'selected' : '' ?>>Fahrzeuge & Zubehör</option>
                    <option value="Musik & Instrumente" <?= ($gewaehlteKategorie === 'Musik & Instrumente') ? 'selected' : '' ?>>Musik & Instrumente</option>
                    <option value="Tierbedarf" <?= ($gewaehlteKategorie === 'Tierbedarf') ? 'selected' : '' ?>>Tierbedarf</option>
                    <option value="Reisen & Gepäck" <?= ($gewaehlteKategorie === 'Reisen & Gepäck') ? 'selected' : '' ?>>Reisen & Gepäck</option>
                    <option value="Sonstiges" <?= ($gewaehlteKategorie === 'Sonstiges') ? 'selected' : '' ?>>Sonstiges</option>
                </select>
            </form>
        </div>

        <div class="filter-section">
            <h3 class="filter-title">Preisspanne</h3>
            <form id="filter-form" method="get">
                <?php
                // Fügt aktuelle Query-Parameter als versteckte Felder hinzu,
                // ausgenommen 'min-preis', 'max-preis' und 'preisfilter'
                foreach ($_GET as $key => $value) {
                    if (!in_array($key, ['min-preis', 'max-preis', 'preisfilter'])) {
                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                    }
                }
                ?>
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
