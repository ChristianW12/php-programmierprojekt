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
                <h3>Gebote abgeben</h3>
                <p>Für das Abgeben eines Gebotes müssen Sie sich nicht einloggen. Sie müssen lediglich eine Mail sowie einen Preis eintragen. 
                    Über die Mail-Adresse kann der Ersteller beim Ablaufen des Angebots sich bei Ihnen melden.  </p>
                <p>Das Bieten funktioniert nach dem Ebay-Biet-Verfahren. Sie geben also einen Preis ein, den sie höchstens bereit wären zu zahlen. 
                    Wenn sie Wenn Sie der Höchstbietende sind, müssen Sie nur den Preis des Zweithöchstbietenden + 1 € zahlen.</p>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-trigger">Profil</h2>
            <div class="accordion-panel">
                <p>Im Profil können Sie ihre Daten eingeben, von der Registrierung und bei Bedarf auch ändern. Dort können Sie sich ebenfalls auch abmelden oder das Konto ganz löschen.</p>
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
