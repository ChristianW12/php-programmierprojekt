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
    <header class="site-header">
        <div class="brand">
            <div class="brand-icon" aria-hidden="true">A</div>
            <span class="brand-name">Auktify</span>
        </div>
        <nav class="main-nav" aria-label="Hauptnavigation">
            <a href="index.html#about">Über uns</a>
            <a href="index.html#auctions">Auktionen</a>
            <a href="angebote.php">Angebote</a>
        </nav>
        <form class="search" action="#">
            <label class="sr-only" for="search">Suche</label>
            <input id="search" type="search" name="q" placeholder="Suche" autocomplete="off">
            <button type="submit">Suchen</button>
        </form>
        <div class="user-actions">
            <div class="user-menu">
                <button type="button" class="icon-button user-toggle" aria-haspopup="true" aria-expanded="false" aria-controls="user-menu-panel">
                    <span aria-hidden="true">👤</span>
                </button>
                <div class="user-menu-panel" id="user-menu-panel">
                    <ul class="user-menu-list">
                        <li><a href="#">Meine Auktionen</a></li>
                        <li><a href="#">Meine Angebote</a></li>
                        <li><a href="#">Meine Favoriten</a></li>
                        <li><a href="#">Einstellungen</a></li>
                    </ul>
                    <button type="button" class="logout-button">Abmelden</button>
                </div>
            </div>
        </div>
    </header>

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

    <footer class="site-footer">
        <div class="footer-brand">
            <span class="brand-name">Auktify</span>
            <address>
                Rotebühlplatz 31<br>
                70178<br>
                Stuttgart
            </address>
        </div>
        <div class="footer-info">
            <strong>Info</strong>
            <ul>
                <li><a href="index.html#about">Über uns</a></li>
                <li><a href="index.html#auctions">Auktionen</a></li>
                <li>Hilfe</li>
            </ul>
        </div>
        <div class="footer-contact">
            <strong>Kontakt</strong>
            <ul>
                <li>Mail?</li>
                <li>Support</li>
            </ul>
        </div>
    </footer>
</body>
</html>
