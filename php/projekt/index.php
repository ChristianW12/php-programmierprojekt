<?php
$homeHrefPrefix = '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Auktionen entdecken</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <?php require __DIR__ . '/partials/header.php'; ?>
    <main>
        <section class="section accent" id="welcome">
            <div class="content-grid">
                <div class="section-text">
                    <div class="section-intro">Willkommen!</div>
                    <h1>Entdecke dein nächstes Lieblingsstück</h1>
                    <p>
                        Stöbere durch kuratierte Auktionen und sichere dir besondere Fundstücke.
                        Von Vintage bis Moderne – wir bringen Anbieter und Liebhaber auf einer
                        Plattform zusammen.
                    </p>
                </div>
                <figure class="image-placeholder has-image">
                    <img src="bilder/Vase.png" alt="Kunstvolle Vase aus einer Auktion">
                </figure>
            </div>
        </section>

        <section class="section" id="about">
            <div class="content-grid single">
                <div class="section-text center">
                    <h2>Über uns</h2>
                    <p>
                        Wir sind ein motiviertes Team aus Sammlerinnen und Sammlern, das
                        besondere Geschichten hinter einzigartigen Gegenständen sichtbar macht.
                        Unsere Mission ist es, das Auktions-Erlebnis ins digitale Zeitalter zu holen,
                        ohne den Charme klassischer Auktionen zu verlieren.
                    </p>
                </div>
            </div>
        </section>

        <section class="section accent" id="auctions">
            <div class="content-grid reversed">
                <div class="section-text">
                    <div class="section-intro">Unser Ziel</div>
                    <h2>Transparente Auktionen für alle</h2>
                    <p>
                        Wir glauben, dass Auktionen fair, zugänglich und transparent sein sollten.
                        Deshalb bieten wir klare Abläufe, sichere Zahlungen und hilfreiche Tools,
                        damit du jede Auktion mit einem guten Gefühl abschließen kannst.
                    </p>
                    <button class="primary-action" type="button">Jetzt loslegen</button>
                </div>
                <figure class="image-placeholder has-image">
                    <img src="bilder/Transaktion.png" alt="Zwei Personen schließen eine Auktionstransaktion ab">
                </figure>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>
