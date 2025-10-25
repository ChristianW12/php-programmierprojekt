<?php
$homeHrefPrefix = 'index.php';
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
