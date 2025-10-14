<?php
session_start(); // Session starten

date_default_timezone_set('Europe/Berlin');
require 'Db.php';
require 'Shout.php';

// --- Login-Logik ---

// Logout-Verarbeitung
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: shoutbox.php");
    exit();
}

// Anmeldeversuch verarbeiten
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_submit'])) {
    $user_input = $_POST['login'] ?? '';
    $password_input = $_POST['password'] ?? '';

    // Datenbankverbindung herstellen
    $dsn = 'mysql:dbname=shoutbox;host=db;port=3306';
    try {
        $db = new Db($dsn, 'root', '');
    } catch (PDOException $e) {
        exit('Connect failed: ' . $e->getMessage());
    }

    // Benutzer aus der Datenbank abfragen.
    // Die Tabelle heißt `user` und die Spalten sind `login` (Benutzername) und `passwd` (Passwort).
    $stmt = $db->prepare("SELECT * FROM user WHERE login = :login");
    $stmt->execute([':login' => $user_input]);
    $user_from_db = $stmt->fetch(PDO::FETCH_ASSOC);

    // Passwort überprüfen.
    // WICHTIG: In einer echten Anwendung sollten Passwörter gehasht gespeichert
    // und mit password_verify() überprüft werden.
    if ($user_from_db && $password_input === $user_from_db['passwd']) {
        // Anmeldedaten korrekt, Session setzen
        $_SESSION['loggedin'] = true;
        $_SESSION['user'] = $user_from_db['login'];
        header("Location: shoutbox.php");
        exit();
    } else {
        $login_error = "Ungültiger Benutzername oder Passwort.";
    }
}

// Prüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // --- Wenn nicht angemeldet, Login-Formular anzeigen ---
    ?>
    <!doctype html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Login zur Shoutbox</title>
    </head>
    <body>
        <h2>Bitte melde dich an</h2>
        <?php if (isset($login_error)) { echo '<p style="color:red;">' . htmlspecialchars($login_error) . '</p>'; } ?>
        <form action="shoutbox.php" method="post">
            <table align="center" width="300">
                <tr>
                    <td>Benutzername:</td>
                    <td><input type="text" name="login" required /></td>
                </tr>
                <tr>
                    <td>Passwort:</td>
                    <td><input type="password" name="password" required /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" name="login_submit" value="Anmelden" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
    </html>
    <?php
    exit(); // Skript beenden, da der Rest nur für angemeldete Benutzer ist
}

// --- Ab hier nur für angemeldete Benutzer ---

$shout = new Shout();

// Cookie für den letzten Besuch setzen
setcookie('last_visit', date('d.m.Y H:i:s'), time() + (86400 * 30), "/");

// Neuen Shout verarbeiten
if (!empty($_REQUEST['user']) && !empty($_REQUEST['content'])) {
    // Shout-Zähler-Cookie hochzählen
    $shout_count = ($_COOKIE['shout_count'] ?? 0) + 1;
    setcookie('shout_count', $shout_count, time() + (86400 * 30), "/");

    // Datenbankverbindung
    $dsn = 'mysql:dbname=shoutbox;host=db;port=3306';
    try {
        $db = new Db($dsn, 'root', '');
    } catch (PDOException $e) {
        exit('Connect failed: ' . $e->getMessage());
    }

    // Shout in der DB speichern
    $shout->saveInDB($db, $_REQUEST['user'], $_REQUEST['content']);
    unset($db);

    // Umleitung, um erneutes Senden bei Reload zu verhindern
    header("Location: shoutbox.php");
    exit();
}

// Benutzername aus der Session holen
$username = $_SESSION['user'];
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ShoutBox</title>
</head>
<body>
    <p>Angemeldet als: <strong><?php echo htmlspecialchars($username); ?></strong> | <a href="shoutbox.php?logout=1">Abmelden</a></p>
<?php
// Infos aus Cookies anzeigen
if (isset($_COOKIE['last_visit'])) {
    echo "Dein letzter Besuch war am: " . htmlspecialchars($_COOKIE['last_visit']) . "<br>";
}
if (isset($_COOKIE['shout_count'])) {
    echo "Anzahl deiner Shouts: " . htmlspecialchars($_COOKIE['shout_count']) . "<br>";
}
?>
<br />
<form action="shoutbox.php" method="post">
    <table align="center" width="350">
        <tr>
            <td>Name:</td>
            <td><input type="text" name="user" value="<?php echo htmlspecialchars($username); ?>" /></td>
        </tr>
        <tr>
            <td>Inhalt:</td>
            <td><input type="text" name="content" value="" autofocus /></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="shout" value="senden" />
            </td>
        </tr>
    </table>
</form>
<?php
/*
 * ============================================================
 */
// Datenbankverbindung für die Anzeige der Shouts
$dsn = 'mysql:dbname=shoutbox;host=db;port=3306';
try {
    $db = new Db($dsn, 'root', '');
} catch (PDOException $e) {
    exit('Connect failed: ' . $e->getMessage());
}
// Alle Shouts aus der Datenbank anzeigen
$shout->outputShoutDB($db);
unset($db);
?>
</body>
</html>