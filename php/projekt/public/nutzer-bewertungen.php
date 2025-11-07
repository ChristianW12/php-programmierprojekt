<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dateien für DB-Verbindung und DB-Hilfsfunktionen einbinden
require __DIR__ . '/../src/db-connection.php';
require_once __DIR__ . '/../src/Db.php';

$db = mitDBverbinden();

// Verkäufer anhand der ID aus der DB laden
$stmt = $db->prepare("SELECT * from users where user_id = :id");
$stmt->execute([':id' => $_GET['id']]);
// Daten vom Verkäufer in einem Array speichern
$verkäufer_from_db = $stmt->fetch(PDO::FETCH_ASSOC);

// Letzte besuchte Seite in der Session speichern für Weiterleitung nach dem Login
$_SESSION['last_site'] = 'nutzer-bewerten';
$_SESSION['URL_Bewertungen'] = 'nutzer-bewertungen.php?id=' . $verkäufer_from_db['user_id'];

if(!$verkäufer_from_db){
    header('Location: index.php');
    exit;
}

// Bewertungen für den Verkäufer in der DB speichern
if (isset($_POST['bewertungAbsenden']) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $bewertung  = (int)($_POST['rating'] ?? 0);
    $kommentar    = $_POST['comment'] ?? '';
    $kommentarErsteller = $_SESSION['user_id'];

    if ($bewertung >= 1 && $bewertung <= 5 && $kommentarErsteller !== $verkäufer_from_db['user_id']) {
        $query = $db->prepare("INSERT INTO user_comment (creator_id, target_id, text, rating) VALUES (:creator, :target, :text, :rating)");
        $query->execute([':creator' => $kommentarErsteller, ':target' => $verkäufer_from_db['user_id'], ':text' => $kommentar, ':rating'  => $bewertung]);
        header("Location: nutzer-bewertungen.php?id=" . $verkäufer_from_db['user_id']);
        exit;
    }
} elseif (!isset($_SESSION['loggedin']) && isset($_POST['bewertungAbsenden'])) {
    header('Location: login.php');
    exit;
}

// Bewertungen aus der DB laden
$stmtBewertungen = $db->prepare("
    SELECT com.rating, com.text, com.created_at, users.name AS erstellt_von
    FROM user_comment com, users 
    WHERE com.creator_id = users.user_id AND com.target_id = :target
    ORDER BY com.created_at DESC
");
$stmtBewertungen->execute([':target' => $verkäufer_from_db['user_id']]);
$bewertungen = $stmtBewertungen->fetchAll(PDO::FETCH_ASSOC);

// Durchschnitt
$avgRating = null;
if ($bewertungen) {
    $sum = array_sum(array_column($bewertungen, 'rating'));
    $avgRating = $sum / count($bewertungen);
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Verkäuferprofil</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/verkaeufer.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main>
    <div class="verkaeufer-container">

        <h1>Profil von <?php echo htmlspecialchars($verkäufer_from_db['name']) ?></h1>

        <section class="info-block">
            <h2>Verkäufer-Informationen</h2>
            <label>Name</label>
            <div class="info-field"> <?php echo htmlspecialchars($verkäufer_from_db['name']) ?></div>

            <label>E-Mail</label>
            <div class="info-field"> <?php echo htmlspecialchars($verkäufer_from_db['mail']) ?></div>
        </section>

        <section class="rating-section">
            <h2>Bewertungen</h2>
            <div class="rating-summary">
                <?php
                if ($avgRating === null) {
                    echo 'Keine Bewertungen vorhanden.';
                } ?>

                <div class="stars">
                <?php
                if ($avgRating !== null) {
                    echo str_repeat('★', (int)round($avgRating));
                    echo str_repeat('☆', 5 - (int)round($avgRating));
                } ?>
                </div>

                <?php
                if ($avgRating !== null) {
                    echo number_format($avgRating, 1, ',') . ' / 5 (' . count($bewertungen) . ')';
                }
                ?>
            </div>

            <?php if($bewertungen !== null): ?>
            <ul class="rating-list">
                <?php foreach ($bewertungen as $bew): ?>
                        <li>
                            <div class="rating-line">
                                <?= str_repeat('★', $bew['rating']) ?>
                                <?= str_repeat('☆', 5 - $bew['rating']) ?>
                                <span class="rating-date"><?= (new DateTime($bew['created_at']))->format('d.m.Y H:i') ?></span>
                            </div>
                            <div class="rating-author"> von <?php echo htmlspecialchars($bew['erstellt_von']) ?></div>
                            <p><?php echo htmlspecialchars($bew['text']) ?></p>
                        </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <form method="post" class="rating-form">
                <label for="rating">Bewertung:</label>
                <select name="rating" id="rating" required>
                    <option value="">Bitte wählen</option>
                    <option value="5">★★★★★ (5)</option>
                    <option value="4">★★★★☆ (4)</option>
                    <option value="3">★★★☆☆ (3)</option>
                    <option value="2">★★☆☆☆ (2)</option>
                    <option value="1">★☆☆☆☆ (1)</option>
                </select>
                <label for="comment">Rezension (optional):</label>
                <textarea name="comment" id="comment" rows="3" placeholder="Eine sehr positive Erfahrung..."></textarea>
                <button type="submit" name="bewertungAbsenden">Bewertung abgeben</button>
            </form>
        </section>
        <p><a href="angebote.php">← Zurück zu den Angeboten</a></p>
    </div>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
