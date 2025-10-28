<?php
// Session starten, falls noch nicht geschehen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Standard-Links definieren
$verlinkungHomepage = $verlinkungHomepage ?? 'index.php';
$verlinkungAngebot = $verlinkungAngebot ?? 'angebote.php';

// Ziellink für das Benutzer-Icon basierend auf dem Login-Status festlegen
$userIconLink = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'profile.php' : 'login.php';
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
        <a href="<?= $userIconLink ?>" class="icon-button" aria-label="Benutzerprofil oder Login">
            <span>👤</span>
        </a>
    </div>
</header>
