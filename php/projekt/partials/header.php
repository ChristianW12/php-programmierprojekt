<?php
$verlinkungHomepage = $verlinkungHomepage ?? 'index.php';
$verlinkungAngebot = $verlinkungAngebot ?? 'angebote.php';
$verlinkungProfil = $verlinkungProfil ?? 'profile.php';
?>
<header class="site-header">
    <div class="brand">
        <div class="brand-icon" aria-hidden="true">A</div>
        <span class="brand-name">Auktify</span>
    </div>
    <nav class="main-nav" aria-label="Hauptnavigation">
        <a href="<?= $verlinkungHomepage ?>#about">Über uns</a>
        <a href="<?= $verlinkungAngebot ?>">Angebote</a>
    </nav>
    <form class="search" action="#">
        <label class="sr-only" for="search">Suche</label>
        <input id="search" type="search" name="q" placeholder="Suche" autocomplete="off">
        <button type="submit">Suchen</button>
    </form>
    <div class="user-actions">
        <div class="user-menu">
            <button type="button" class="icon-button user-toggle" id="user-menu-toggle">
                <span>👤</span>
            </button>
            <div class="user-menu-panel" id="user-menu-panel">
                <ul class="user-menu-list">
                    <li><a href="#">Meine Auktionen</a></li>
                    <li><a href="#">Meine Angebote</a></li>
                    <li><a href="#">Meine Favoriten</a></li>
                    <li><a href="<?= $verlinkungProfil ?>">Profil</a></li>
                </ul>
                <button type="button" class="logout-button">Abmelden</button>
            </div>
        </div>
    </div>
</header>
