<?php

// Standard-Links definieren
$verlinkungHomepage = $verlinkungHomepage ?? 'index.php';
$verlinkungAngebot = $verlinkungAngebot ?? 'angebote.php';
$verlinkungHelp = $verlinkungHelp ?? 'help.php';
?>
<footer class="site-footer" id="footer">
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
            <li><a href="<?= $verlinkungHomepage ?>#about">Über uns</a></li>
            <li><a href="<?= $verlinkungAngebot ?>#auctions">Auktionen</a></li>
            <li><a href="<?= $verlinkungHelp ?>">Hilfe</a></li>
        </ul>
    </div>
    <div class="footer-contact">
        <strong>Kontakt</strong>
        <ul>
            <li>auktify@info.de</li>
            <li><a href="mailto:auktify@info.de?subject=Hilfeanfrage">Support</a></li>
        </ul>
    </div>
</footer>
