<?php
require __DIR__ . '/php-code/Bid.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$offerId = isset($_GET['offer_id']) ? (int) $_GET['offer_id'] : null;
$startpreis = isset($_GET['startpreis']) && is_numeric($_GET['startpreis']) ? (float) $_GET['startpreis'] : null;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitOffer'])) {
    $bidAmount = isset($_POST['bid_amount']) ? (float) $_POST['bid_amount'] : null;
    $bidEmail = isset($_POST['bid_email']) ? filter_var($_POST['bid_email'], FILTER_VALIDATE_EMAIL) : null;

    if($bidAmount !== null && $bidEmail !== null && $offerId !== null && $startpreis !== null && $bidAmount > $startpreis) {
        $bid = new Bid($offerId, $bidAmount, $bidEmail);
        $res = $bid->saveBid();
        if($res === true) {
            header("Location: angebote.php");
            exit;
        } else {
            echo "Fehler beim Speichern des Gebots.";
        }
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
<?php require __DIR__ . '/partials/header.php'; ?>
<main>
    <section class="section">
        <div class="section-text center profile-container">
            <h1><strong>Gebot abgeben</strong></h1>
            <hr>
            <form method="post" class="bid-form">
                <div class="form-group">
                    <label for="bid-amount">Gebotsbetrag</label>
                    <input 
                        type="number" 
                        id="bid-amount" 
                        name="bid_amount" 
                        min="<?= $startpreis ? $startpreis + 1 : 1 ?>"
                        step="1" 
                        placeholder="z.B. <?= $startpreis ? $startpreis + 1.99 : 1 ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="bid-email">E-Mail-Adresse</label>
                    <input type="email" id="bid-email" name="bid_email" placeholder="name@example.com" required>
                </div>
                <div class="profile-actions">
                    <button type="submit" name="submitOffer" class="btn">Gebot absenden</button>
                </div>
            </form>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
