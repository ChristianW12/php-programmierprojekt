<?php
require_once __DIR__ . '/../src/Bid.php';
require_once __DIR__ . '/../src/Messages.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Titel für die H1-Überschrift initialisieren und eine Variable für Erfolgs-Styling.
$pageTitle = 'Gebot abgeben';
$isSuccess = false;

// Angebot ID und Startpreis aus der URL holen und Variable Standard-Mail initialisieren
$offerId = isset($_GET['offer_id']) ? (int) $_GET['offer_id'] : null;
$startpreis = isset($_GET['startpreis']) && is_numeric($_GET['startpreis']) ? (float) $_GET['startpreis'] : null;
$standardMail = '';

// Speichern der letzten besuchten Seite in der Session
$_SESSION['last_site'] = 'angebot-bieten';
$_SESSION['URL'] = $_SERVER['REQUEST_URI'];

// Wenn der Nutzer eingeloggt ist, seine E-Mail als Standardwert setzen
if (isset($_SESSION['loggedin']) && isset($_SESSION['user_mail'])) {
    $standardMail = $_SESSION['user_mail'];
}

// Formular-Submit verarbeiten
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitOffer'])) {
    $bidAmount = isset($_POST['bid_amount']) ? (float) $_POST['bid_amount'] : null;
    $bidEmail = isset($_POST['bid_email']) ? filter_var($_POST['bid_email'], FILTER_VALIDATE_EMAIL) : null;

    // Gebot speichern über die Bid-Klasse
    if($bidAmount !== null && $bidEmail !== null && $offerId !== null) {
        $bid = new Bid($offerId, $bidAmount, $bidEmail);
        $result = $bid->saveBid();
        $pageTitle = htmlspecialchars($result['message']);
        $isSuccess = $result['success'];
        // Bei Erfolg Nachricht an Angebotsbesitzer senden
        if($isSuccess) {
            try {
                $messages = new Messages();
                $req = $messages->sendMessageWhenBidding($bidEmail, $offerId);
            } catch (\Throwable $th) {
                echo "Fehler beim Senden der Nachricht: " . $th->getMessage();
            }
        }

    } else {
        $pageTitle = 'Eingabe ungültig. Bitte prüfen Sie Ihr Gebot.';
        $isSuccess = false;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Gebot abgeben</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<main>
    <section class="section">
        <div class="section-text center profile-container">

            <!-- Titel wird IMMER angezeigt -->
            <h1 class="<?= $isSuccess ? 'success-message' : 'error-message' ?>">
                <strong><?= $pageTitle ?></strong>
            </h1>

            <!-- Nur bei Erfolg: Hinweis + Redirect -->
            <?php if ($isSuccess): ?>
                <p class="redirect-message">Sie werden in <strong>2 Sekunden</strong> zu Ihren Angeboten weitergeleitet...</p>
                <script>
                    setTimeout(function() {
                        window.location.href = 'angebote.php';
                    }, 2000);
                </script>
            <?php endif; ?>

            <hr>

            <form method="post" class="bid-form">
                <div class="form-group">
                    <label for="bid-amount">Gebotsbetrag</label>
                    <input 
                        type="number" 
                        id="bid-amount" 
                        name="bid_amount" 
                        min="<?= $startpreis ? htmlspecialchars($startpreis + 1) : 1 ?>"
                        step="1" 
                        placeholder="z.B. <?= $startpreis ? htmlspecialchars($startpreis + 1.99) : 1 ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="bid-email">E-Mail-Adresse</label>
                    <input 
                        type="email" 
                        id="bid-email" 
                        name="bid_email" 
                        placeholder="name@example.com" 
                        value="<?= htmlspecialchars($standardMail) ?>" 
                        required>
                </div>
                <div class="profile-actions">
                    <button type="submit" name="submitOffer" class="btn">Gebot absenden</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
