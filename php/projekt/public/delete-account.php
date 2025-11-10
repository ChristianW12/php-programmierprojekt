<?php // Refactored
// Service für Benutzeraktionen laden
require __DIR__ . '/../src/UserService.php';

// Session und Loginstatus prüfen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Feedbackmeldung initialisieren
$delete_error = '';

// Formulareingabe verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account_submit'])) {
    $password = $_POST['password'] ?? '';
    $user_id = (int) $_SESSION['user_id'];
    $userService = new UserService();
    $imageBasePath = __DIR__ . '/../bilder';

    // Passwort prüfen und ggf. löschen
    if ($userService->verifyPassword($user_id, $password)) {
        if ($userService->deleteUser($user_id, $imageBasePath)) {
            session_destroy();
            header('Location: login.php?account_deleted=true');
            exit;
        }
        $delete_error = 'Das Konto konnte nicht gelöscht werden. Bitte versuchen Sie es erneut.';
    } else {
        $delete_error = 'Das eingegebene Passwort ist falsch.';
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konto löschen</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/delete-account.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main>
        <section class="section">
            <div class="section-text center">
                <h1>Konto löschen</h1>
                <p>Bestätigen Sie die Löschung Ihres Kontos, indem Sie Ihr Passwort eingeben. Diese Aktion kann nicht rückgängig gemacht werden.</p>
                <?php if (!empty($delete_error)): ?>
                    <!-- Fehlermeldung ausgeben -->
                    <p class="error"><?php echo $delete_error; ?></p>
                <?php endif; ?>
                <form action="delete-account.php" method="post" class="delete-account-form">
                    <div>
                        <label for="password">Passwort</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div>
                        <button type="submit" class="primary-action" name="delete_account_submit">Konto endgültig löschen</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>
