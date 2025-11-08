<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$verlinkungHomepage = $verlinkungHomepage ?? 'index.php';
$verlinkungAngebot = $verlinkungAngebot ?? 'angebote.php';

$userIconLink = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'profile.php' : 'login.php';
?>
<header class="site-header">
    <div class="brand">
        <span class="brand-name">Auktify</span>
    </div>
    <nav class="main-nav" aria-label="Hauptnavigation">
        <a href="<?= $verlinkungHomepage ?>#about">Über uns</a>
        <a href="<?= $verlinkungAngebot ?>">Angebote</a>
        <a href="#footer">Zum Footer</a>
    </nav>
    <form class="search" action="angebote.php" method="get">
        <label class="sr-only" for="search">Suche</label>
        <input 
            id="search" 
            type="search" 
            name="q" 
            placeholder="Suche" 
            autocomplete="off"
            value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8')?>"
        >
        <button type="submit">Suchen</button>
    </form>
    <div class="user-actions">
        <a href="<?= $userIconLink ?>" class="icon-button" aria-label="Benutzerprofil oder Login">
            <span>👤</span>
        </a>
    </div>
    <script src="scripts/app.js"></script>
</header>
