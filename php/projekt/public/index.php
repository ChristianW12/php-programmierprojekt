<?php // Refactored
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
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main>
        <section class="section accent" id="welcome">
            <div class="content-grid">
                <div class="section-text">
                    <div class="section-intro">Willkommen!</div>
                    <h1>Entdecken Sie Ihr nächstes Lieblingsstück</h1>
                    <p>
                        Treten Sie ein in eine Welt, in der jedes Objekt eine Geschichte erzählt. Bei Auktify bringen wir leidenschaftliche Sammler, anspruchsvolle Käufer
                        und engagierte Verkäufer zusammen. Entdecken Sie eine sorgfältig kuratierte Auswahl an Kunst, Antiquitäten, Designklassikern und seltenen Fundstücken.
                        Erleben Sie den Nervenkitzel einer Auktion und finden Sie das eine Stück, das Ihre Sammlung vervollständigt oder Ihrem Zuhause eine persönliche Note verleiht.
                        Ihr nächstes Lieblingsstück ist nur ein Gebot entfernt.
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
                    <h2>Unsere Leidenschaft, Ihre Plattform</h2>
                    <p>
                        Auktify wurde aus einer tiefen Faszination für das Besondere gegründet. Wir sind mehr als nur eine Plattform – wir sind ein Team von Enthusiasten, das
                        die Geschichten hinter wertvollen Objekten liebt und teilt. Unsere Mission ist es, das klassische Auktionserlebnis mit den Vorteilen der digitalen Welt
                        zu verbinden: zugänglich, transparent und benutzerfreundlich. Wir schaffen einen vertrauensvollen Marktplatz, der die Freude am Sammeln und Handeln in den
                        Mittelpunkt stellt und eine Gemeinschaft von Gleichgesinnten verbindet.
                    </p>
                </div>
            </div>
        </section>

        <section class="section accent" id="auctions">
            <div class="content-grid reversed">
                <div class="section-text">
                    <div class="section-intro">Unser Versprechen an Sie</div>
                    <h2>Transparenz und Fairness bei jeder Auktion</h2>
                    <p>
                        Wir sind überzeugt, dass Vertrauen die Grundlage jeder erfolgreichen Auktion ist. Deshalb verpflichten wir uns zu absoluter Transparenz und Fairness. Von klaren
                        Artikelbeschreibungen über nachvollziehbare Gebotsschritte bis hin zu sicheren und geschützten Zahlungsprozessen – wir sorgen für einen reibungslosen Ablauf.
                        Ob Sie als Käufer ein neues Schmuckstück suchen oder als Verkäufer Ihre Schätze einem neuen Publikum präsentieren: Bei Auktify können Sie sich auf einen integren
                        und professionellen Partner verlassen.
                    </p>
                    <br>
                    <a href="register.php" class="primary-action">Jetzt registrieren</a>
                </div>
                <figure class="image-placeholder has-image">
                    <img src="bilder/Transaktion.png" alt="Zwei Personen schließen eine Auktionstransaktion ab">
                </figure>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>
