<?php // Refactored
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../src/UserService.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Überprüfen ob Request POST ist und ob der Button Anmelden gedrückt wurde
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])){
    // Mail Adresse und Passwort aus dem Formular holen
    $user_mail = $_POST['email'] ?? '';
    $user_password = $_POST['password'] ?? '';

    $userService = new UserService();
    $loginResult = $userService->loginUser($user_mail, $user_password);

    if ($loginResult['success'] ?? false) {
        $userData = $loginResult['user'] ?? [];

        $_SESSION['loggedin'] = true;
        $_SESSION['user_mail'] = $userData['mail'] ?? $user_mail;
        $_SESSION['user_id'] = $userData['user_id'] ?? null;
        $_SESSION['is_admin'] = $userData['is_admin'] ?? 0;

        // Switch zum zurückkommen auf die letzte besuchte Seite
        switch ($_SESSION['last_site'] ?? '') {
        case 'profil':
            header("Location: profile.php");
            exit();
            break;
        case 'angebot erstellen':
            header('Location: neuesAngebot.php');
            exit();
            break;
        case 'angebot-bieten':
            header('Location: ' . ($_SESSION['URL'] ?? 'angebote.php'));
            exit();
            break;
        case 'meine angebote':
            header('Location: angebote.php?sort=meineAngebote');
            exit();
            break;
        case 'favoriten':
            header('Location: angebote.php?sort=favoriten');
            exit();
            break;
        case 'nutzer-bewerten':
            header('Location: ' . ($_SESSION['URL_Bewertungen'] ?? 'nutzer-bewertungen.php'));
            exit();
            break;
        default:  
            header("Location: index.php");
            exit();
            break;
        }
    } else {
        $login_error = $loginResult['message'] ?? "Ungültiger Benutzername oder Passwort. Versuchen Sie es nochmal.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Login</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<main>
    <section class="section">
        <div class="section-text center">
            <h1>Login</h1>
            <?php if (isset($login_error)): ?>
                <p class="error"><?php echo $login_error; ?></p>
            <?php else: ?>
                <p>Bitte melden Sie sich an, um fortzufahren.</p>
            <?php endif; ?>
            <form action="login.php" method="post" class="login-form">
                <div>
                    <label for="email">E-Mail-Adresse</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="password">Passwort</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <button type="submit" class="primary-action" name="login_submit">Anmelden</button>
                </div>
            </form>
            <p>Neu hier? <a href="register.php">Konto erstellen</a></p>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
