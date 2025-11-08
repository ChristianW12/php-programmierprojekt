<?php
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
                <p>Um ein Angebot zu erstellen, müssen Sie eingeloggt sein. Ob sie eingeloggt sind, können sie überprüfen, wenn sie auf das Person-Icon klicken rechts oben in der Ecke. </p>
                <p>Sie können den Angeboten Bilder hochladen, sowie ein Cover Bild, welches auf der <a href="angebote.php">Seite für die Angebot</a> angezeigt wird </p>
                <p>Jedem Angebot kann wahlweise auch eine Kategorie zugewiesen werden, damit eine einfachere Suche und Einteilung der Angebot durch die User beim Suchen möglich ist.</p>
                <h3>Gebote abgeben</h3>
                <p>Für das Abgeben eines Gebotes müssen Sie sich nicht einloggen. Sie müssen lediglich eine Mail sowie einen Preis eintragen. 
                    Über die Mail-Adresse kann der Ersteller beim Ablaufen des Angebots sich bei Ihnen melden.  </p>
                <p>Das Bieten funktioniert nach dem Ebay-Biet-Verfahren. Sie geben also einen Preis ein, den sie höchstens bereit wären zu zahlen. 
                    Wenn sie Wenn Sie der Höchstbietende sind, müssen Sie nur den Preis des Zweithöchstbietenden + 1 € zahlen.</p>
                <h3>Angebotsende</h3>
                <p>Wenn ein Angebot zu Ende geht, wir der Ersteller benachrichtigt mit dem Preis und der Email vom Höchstbietenden.</p>
                <p>Hat ein User ein Account und beim Bieten die selben Email verwendet, wie beim Erstellen des Accounts, wird auch dieser eine Benachrichtigungen erhalten.
                    In dieser steht ebenfalls der zuzahlende Preis sowie der Titel des Angebots.</p>
                <h3>Filter</h3>
                <p>Der Filter kann nach verschiedenen Präferenzen eingestellt werden. Wenn man eingeloggt ist, kann man Angebot auch als Favorit speichern. 
                    Diese können dann über den Filter angezeigt werden.</p>
                <!-- -->
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) : ?>
                    <h3>Features als Admin</h3>
                    <p>Als Admin kann man nicht auf Angebote bieten. Man ist für die Administration der Seite zuständig.
                        Deswegen kann man als Admin jedes Angebot bearbeiten und auch löschen.</p>
                    <p>Falls man als Admin auf ein Angebot bieten möchte, so muss man den Account wechseln oder sich ausloggen.</p>
                <?php else : ?>
                    <h3>Admin-Zugang</h3>
                    <p>Wenn Sie Administrator werden möchten, wenden Sie sich bitte an das IT-Team. Die Freischaltung als Administrator ist nur durch das IT-Team möglich und kann nicht selbstständig bei der Accounterstellung vorgenommen werden.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-trigger">Profil</h2>
            <div class="accordion-panel">
                <p>Im Profil können Sie ihre Daten eingeben, von der Registrierung und bei Bedarf auch ändern. Dort können Sie sich ebenfalls auch abmelden oder das Konto ganz löschen.</p>
                <h3>Benachrichtigungen</h3>
                <p>Auf der linken Seite haben Sie verschiedene Benachrichtigungen. Diese können folgendes Anzeigen:  
                <ul>
                    <li>Benachrichtigungen für den Ersteller, wenn ein Gebot auf eines seiner Angebote abgegeben wurde.</li>
                    <li>Benachrichtigungen an den User, wenn er überboten wurde. (<b>WARNUNG!</b> Funktioniert nur, wenn man die gleiche Mail zum Bieten verwendet, wie für die Anmeldung beim Account)</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<script src="scripts/help.js" defer></script>

<?php
require_once '../partials/footer.php';
?> 
