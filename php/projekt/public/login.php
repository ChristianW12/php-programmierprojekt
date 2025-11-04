<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/src/Db.php';
require 'src/db-connection.php';

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

    // DB Verbindung aufbauen
    $db = mitDBverbinden();

    // Benutzer in der Datenbank suchen
    $stmt = $db->prepare("select* from users where mail = :mail");
    $stmt->execute([':mail' => $user_mail]);
    // Daten vom User in einem Array speichern
    $user_from_db = $stmt->fetch(Db::FETCH_ASSOC);

    // Passwort überprüfen
    if($user_from_db) {
        // Kontrolle, ob Passwort gehashed ist
        if (password_needs_rehash($user_from_db['password'], PASSWORD_DEFAULT)) {
            // wenn nicht gehashed, direkt vergleichen
            if ($user_password === $user_from_db['password']) {
                // Hash und Update des Passworts in der DB
                $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
                $update_stmt = $db->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
                $update_stmt->execute([':password' => $hashed_password, ':user_id' => $user_from_db['user_id']]);
                // Login erfolgreich
                $_SESSION['loggedin'] = true;
                $_SESSION['user_mail'] = $user_from_db['mail'];
                $_SESSION['user_id'] = $user_from_db['user_id'];
                $_SESSION['is_admin'] = $user_from_db['is_admin'];

                // Switch zum zurückkommen auf die letzte besuchte Seite
                switch ($_SESSION['last_site']) {
                case 'profil':
                    header("Location: profile.php");
                    exit();
                    break;
                case 'angebot erstellen':
                    header('Location: neuesAngebot.php');
                    exit();
                    break;
                default:  
                    header("Location: index.php");
                    exit();
                    break;
                }
            } else {
                $login_error = "Ungültiger Benutzername oder Passwort. Versuchen Sie es nochmal.";
            }
        } else {
            // wenn schon gehashed, normales Passwort-Verify
            if (password_verify($user_password, $user_from_db['password'])) {
                // Login successful
                $_SESSION['loggedin'] = true;
                $_SESSION['user_mail'] = $user_from_db['mail'];
                $_SESSION['user_id'] = $user_from_db['user_id'];
                $_SESSION['is_admin'] = $user_from_db['is_admin'];

                // Switch zum zurückkommen auf die letzte besuchte Seite
                switch ($_SESSION['last_site']) {
                case 'profil':
                    header("Location: profile.php");
                    exit();
                    break;
                case 'angebot erstellen':
                    header('Location: neuesAngebot.php');
                    exit();
                    break;
                case 'angebot-bieten':
                    header('Location: ' . $_SESSION['URL']);
                    exit();
                    break;
                case 'meine angebote':
                    header('Location: angebote.php?sort=meineAngebote');
                    exit();
                    break;
                default:  
                    header("Location: index.php");
                    exit();
                    break;
                }
            } else {
                $login_error = "Ungültiger Benutzername oder Passwort. Versuchen Sie es nochmal.";
            }
        }
    } else {
        $login_error = "Ungültiger Benutzername oder Passwort. Versuchen Sie es nochmal.";
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
<?php require __DIR__ . '/partials/header.php'; ?>
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
<?php require __DIR__ . '/partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
