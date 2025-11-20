<?php // Refactored
$extraCss = ['styles/help.css'];
require_once '../partials/head.php';
require_once '../partials/header.php';
?>

<main>
    <div class="help-container">
        <h1>Hilfe & FAQ</h1>
        <p>Hier finden Sie Antworten auf häufig gestellte Fragen und Unterstützung.</p>

        <div class="accordion-item">
            <h2 class="accordion-trigger">Auktion</h2>
            <div class="accordion-panel">
                <h3>Angebote erstellen</h3>
                <p>Um ein Angebot zu erstellen, müssen Sie eingeloggt sein. Ob Sie eingeloggt sind, können Sie überprüfen, indem Sie auf das Person-Icon oben rechts in der Ecke klicken.</p>
                <p>Sie können den Angeboten Bilder hinzufügen, sowie ein Cover-Bild, welches auf der <a href="angebote.php">Angebotsseite</a> angezeigt wird.</p>
                <p>Jedem Angebot kann wahlweise auch eine Kategorie zugewiesen werden, damit eine einfachere Suche und Einteilung der Angebote durch die Nutzer beim Suchen möglich ist.</p>
                <h3>Gebote abgeben</h3>
                <p>Für das Abgeben eines Gebotes müssen Sie sich nicht einloggen. Sie müssen lediglich eine E-Mail-Adresse sowie einen Preis eintragen. 
                    Über die E-Mail-Adresse kann sich der Ersteller beim Ablaufen des Angebots bei Ihnen melden.</p>
                <p>Das Bieten funktioniert nach dem Ebay-Biet-Verfahren. Sie geben also einen Preis ein, den Sie höchstens bereit wären zu zahlen. 
                    Wenn Sie der Höchstbietende sind, müssen Sie nur den Preis des Zweithöchstbietenden + 1 € zahlen.</p>
                <h3>Angebot ändern</h3>
                <p>Der Ersteller kann jederzeit seine Angebote bearbeiten. Falls sich der Ersteller beim Preis vertan hat, so muss das gesamte Angebot gelöscht werden.</p>
                <h3>Angebotsende</h3>
                <p>Wenn ein Angebot zu Ende geht, wird der Ersteller über den Preis und die E-Mail des Höchstbietenden benachrichtigt.</p>
                <p>Hat ein Nutzer ein Konto und beim Bieten dieselbe E-Mail-Adresse verwendet, die er auch bei der Kontoerstellung angegeben hat, erhält er ebenfalls eine Benachrichtigung.
                    In dieser Benachrichtigung steht ebenfalls der zu zahlende Preis sowie der Titel des Angebots.</p>
                <h3>Filter</h3>
                <p>Der Filter kann nach verschiedenen Präferenzen eingestellt werden. Wenn man eingeloggt ist, kann man Angebote auch als Favoriten speichern. 
                    Diese können dann über den Filter angezeigt werden.</p>
                <!-- -->
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) : ?>
                    <h3>Features als Admin</h3>
                    <p>Als Admin können Sie nicht auf Angebote bieten. Sie sind für die Administration der Seite zuständig.
                        Deswegen können Sie als Admin jedes Angebot bearbeiten und auch löschen.</p>
                    <p>Falls Sie als Admin auf ein Angebot bieten möchten, so müssen Sie das Konto wechseln oder sich ausloggen.</p>
                <?php else : ?>
                    <h3>Admin-Zugang</h3>
                    <p>Wenn Sie Administrator werden möchten, wenden Sie sich bitte an das IT-Team. Die Freischaltung als Administrator ist nur durch das IT-Team möglich und kann nicht selbstständig bei der Kontoerstellung vorgenommen werden.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-trigger">Profil</h2>
            <div class="accordion-panel">
                <p>Im Profil können Sie Ihre bei der Registrierung angegebenen Daten einsehen und bei Bedarf ändern. Dort können Sie sich ebenfalls abmelden oder das Konto ganz löschen.</p>
                <h3>Benachrichtigungen</h3>
                <p>Auf der linken Seite haben Sie verschiedene Benachrichtigungen. Diese können Folgendes anzeigen:
                <ul>
                    <li>Benachrichtigungen für den Ersteller, wenn ein Gebot auf eines seiner Angebote abgegeben wurde.</li>
                    <li>Benachrichtigungen an den Nutzer, wenn er überboten wurde. (<b>WARNUNG!</b> Funktioniert nur, wenn Sie dieselbe E-Mail-Adresse zum Bieten verwenden, die Sie auch für die Anmeldung bei Ihrem Konto nutzen.)</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<script src="scripts/help.js" defer></script>

<?php
require_once '../partials/footer.php';
?> 
