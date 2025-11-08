<?php
if (session_start() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['loggedin']) ){
    header('Location: login.php');
    exit;
}

// Dateien für DB-Verbindung und DB-Hilfsfunktionen einbinden
require __DIR__ . '/../src/db-connection.php';
require_once __DIR__ . '/../src/Db.php';
require_once __DIR__ . '/../src/Messages.php';

try {
    $db = mitDBverbinden();
    $stmt = $db->prepare("SELECT * FROM users WHERE mail = :mail");
    $stmt->execute([':mail' => $_SESSION['user_mail']]);
    $user_from_db = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    $user_from_db = [];
    
}

try {
    $messagesService = new Messages($db);
    $userMessages = $messagesService->getMessagesForUser((int) ($user_from_db['user_id'] ?? 0));
    $unreadMessages = $messagesService->countUnreadMessages($userMessages);
} catch (Exception $ex) {
    $userMessages = [];
    $unreadMessages = 0;
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Read-All'])) {
    try {
        $updatedMessages = $messagesService->markAllAsRead((int) ($user_from_db['user_id'] ?? 0));

        // ✅ Wenn erfolgreich, Seite neu laden:
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit; // ganz wichtig: beendet das Skript nach dem Redirect!

    } catch (Exception $ex) {
        error_log("Fehler beim Markieren der Nachrichten als gelesen: " . $ex->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Profil</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main>
        <section class="section">
            <div class="section-text center profile-container">
                <div class="profile-messages" aria-live="polite">
                    <button 
                        type="button" 
                        class="message-toggle" 
                        aria-label="Nachrichten anzeigen" 
                        aria-expanded="false" 
                        data-message-toggle
                    >
                        <span class="bell-icon" aria-hidden="true">🔔</span>
                        <?php if ($unreadMessages > 0): ?>
                            <span class="message-badge"><?= $unreadMessages ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="message-panel" data-message-panel>
                        <header class="message-panel__header">
                            <div>
                                <p class="message-panel__eyebrow">Posteingang</p>
                                <h2>Deine Nachrichten</h2>
                            </div>
                            <button type="button" class="message-close" aria-label="Nachrichten schließen" data-message-close>&times;</button>
                        </header>
                        <ul class="message-list">
                            <?php if (empty($userMessages)): ?>
                                <li class="message-item is-empty">
                                    <div class="message-item__body">
                                        <p class="message-title">Keine Nachrichten</p>
                                        <p class="message-preview">Sobald es Neuigkeiten gibt, erscheinen sie hier.</p>
                                    </div>
                                </li>
                            <?php else: ?>
                                <?php foreach ($userMessages as $message): ?>
                                    <?php
                                        $isUnread = (int)($message['read'] ?? 0) === 0;
                                        $timestamp = null;
                                        try {
                                            $timestamp = (new DateTime($message['time']))->format('d.m.Y H:i');
                                        } catch (Exception $e) {
                                            $timestamp = htmlspecialchars($message['time'] ?? '', ENT_QUOTES, 'UTF-8');
                                        }
                                    ?>
                                    <li class="message-item <?= $isUnread ? 'is-unread' : '' ?>">
                                        <div class="message-item__status" aria-hidden="true"></div>
                                        <div class="message-item__body">
                                            <p class="message-title"><?= htmlspecialchars($message['title'] ?? ''); ?></p>
                                            <p class="message-preview"><?= htmlspecialchars($message['preview'] ?? ''); ?></p>
                                            <span class="message-time"><?= $timestamp; ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <form method="post">
                            <button type="submit" name="Read-All" class="message-action">Alle als gelesen markieren</button>
                        </form>
                    </div>
                </div>

                <h1><strong>Mein Profil</h1>
                <hr>
                <h3><strong>Persönliche Informationen</strong></h3>
                <div class="form-group-display">
                    <label>Name</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['name']); ?></div>
                </div>
                <div class="form-group-display">
                    <label>E-Mail</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['mail']); ?></div>
                </div>
                <hr>
                <h3><strong>Adresse</strong></h3>
                <div class="form-group-display">
                    <label>Straße</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['str']); ?></div>
                </div>
                <div class="form-group-display">
                    <label>Ort</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['ort']); ?></div>
                </div>
                <div class="form-group-display">
                    <label>Postleitzahl</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($user_from_db['plz']); ?></div>
                </div>
                <div class="profile-actions">
                    <a href="profile-edit.php" class="btn">Profil bearbeiten</a>
                    <a href="logout.php" class="btn btn-danger">Abmelden</a>
                    <a href="delete-account.php" class="btn btn-danger">Konto löschen</a>
                </div>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script src="scripts/profile.js"></script>
</body>
</html>
