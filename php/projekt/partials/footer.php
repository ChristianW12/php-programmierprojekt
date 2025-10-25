<?php
$homeHrefPrefix = $homeHrefPrefix ?? '';
?>
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
            <li><a href="<?= $homeHrefPrefix ?>#about">Über uns</a></li>
            <li><a href="<?= $homeHrefPrefix ?>#auctions">Auktionen</a></li>
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
