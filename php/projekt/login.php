<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/php-code/Db.php';

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

    // Datenbankverbindung herstellen
    $dsn = 'mysql:dbname=auktion;host=db;port=3306';

    // Sichergehen das eine Datenbankvebindung hergestellt werden kann
    try{
        $db = new Db($dsn, 'root', '');
    } catch (PDOException $e){
        echo 'Verbindungsfehler: ' . $e->getMessage();
        exit;
    }

    // Benutzer in der Datenbank suchen
    $stmt = $db->prepare("select* from users where mail = :mail");
    $stmt->execute([':mail' => $user_mail]);
    // Daten vom User in einem Array speichern
    $user_from_db = $stmt->fetch(Db::FETCH_ASSOC);

    // Passwort überprüfen
    if($user_from_db && $user_password === $user_from_db['password'] ){
        // Cookies richtig setzen für weiteres verwenden
        $_SESSION['loggedin'] = true;
        $_SESSION['user_mail'] = $user_from_db['mail'];

        // Zurückführen auf die zuletzt besuchte Seite
        if(!isset($_SESSION['last_site'])){
            header('Location: index.php');
            exit();
        }else{
            exit();
        }

    }else{
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
    <style>
        .login-form {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            text-align: left;
        }
        .login-form div {
            display: flex;
            flex-direction: column;
        }
        .login-form label {
            margin-bottom: 0.5rem;
        }
        .login-form input {
            padding: 0.5rem;
            border: 1px solid var(--mint-dark);
            border-radius: 0.25rem;
        }
        .error {
            color: #d93025;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
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
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
