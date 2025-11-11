<?php //Refactored
// Session sauber initialisieren, um User-Kontext und Nachrichtenstatus zu kennen.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nur eingeloggte Nutzer dürfen das Profil sehen.
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/../src/UserService.php';
require __DIR__ . '/../src/Messages.php';

$userService = new UserService();
$messagesService = new Messages();
$userMail = $_SESSION['user_mail'] ?? '';
$userMessages = [];
$unreadMessages = 0;

// Benutzer inklusive Adressdaten aus der DB holen; ohne Datensatz zurück zum Login.
$currentUser = $userService->getUserByMail($userMail);
if (!$currentUser) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$userId = (int)($currentUser['user_id'] ?? 0);

// Nachrichtenübersicht füllen (Fehler werden geloggt, die Seite bleibt aber nutzbar).
try {
    $userMessages = $messagesService->getMessagesForUser($userId);
    $unreadMessages = $messagesService->countUnreadMessages($userMessages);
} catch (\Throwable $th) {
    error_log('Nachrichten konnten nicht geladen werden: ' . $th->getMessage());
}

// POST-Aktion: alle Nachrichten als gelesen markieren.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Read-All'])) {
    try {
        if ($messagesService->markAllAsRead($userId)) {
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    } catch (\Throwable $th) {
        error_log('Markieren als gelesen fehlgeschlagen: ' . $th->getMessage());
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
                            <span class="message-badge"><?php echo $unreadMessages ?></span>
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
                                    <li class="message-item <?php echo $isUnread ? 'is-unread' : '' ?>">
                                        <div class="message-item__status" aria-hidden="true"></div>
                                        <div class="message-item__body">
                                            <p class="message-title"><?php echo htmlspecialchars($message['title'] ?? ''); ?></p>
                                            <p class="message-preview"><?php echo htmlspecialchars($message['preview'] ?? ''); ?></p>
                                            <span class="message-time"><?php echo $timestamp; ?></span>
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
                    <div class="form-control-display"><?php echo htmlspecialchars($currentUser['name'] ?? ''); ?></div>
                </div>
                <div class="form-group-display">
                    <label>E-Mail</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($currentUser['mail'] ?? ''); ?></div>
                </div>
                <hr>
                <h3><strong>Adresse</strong></h3>
                <div class="form-group-display">
                    <label>Straße</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($currentUser['str'] ?? ''); ?></div>
                </div>
                <div class="form-group-display">
                    <label>Ort</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($currentUser['ort'] ?? ''); ?></div>
                </div>
                <div class="form-group-display">
                    <label>Postleitzahl</label>
                    <div class="form-control-display"><?php echo htmlspecialchars($currentUser['plz'] ?? ''); ?></div>
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
