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
    // Variable setzen in der vorbereiteten SQL Abfrage um später einzufügen und SQL Injektion zu vermeiden
    $stmt = $db->prepare("SELECT * FROM user WHERE login = :login");
    $stmt->execute([':login' => $user_input]);
    $user_from_db = $stmt->fetch(Db::FETCH_ASSOC);

    // Passwort überprüfen.
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login zur Shoutbox</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f0f2f5;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            }
            .login-container {
                background-color: #fefefe; /* Etwas dunkleres Weiß */
                padding: 2rem 3rem;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); /* Schwebe-Effekt */
                width: 100%;
                max-width: 400px;
                box-sizing: border-box;
            }
            h2 {
                text-align: center;
                margin-bottom: 1.5rem;
                color: #333;
            }
            .form-group {
                margin-bottom: 1.5rem;
            }
            label {
                display: block;
                margin-bottom: 0.5rem;
                color: #555;
            }
            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 0.8rem;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-sizing: border-box;
                font-size: 1rem;
            }
            input[type="submit"] {
                width: 100%;
                padding: 0.9rem;
                border: none;
                border-radius: 5px;
                background-color: #007bff;
                color: white;
                font-size: 1.1rem;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            input[type="submit"]:hover {
                background-color: #0056b3;
            }
            .error {
                color: #d93025;
                text-align: center;
                margin-bottom: 1rem;
            }
        </style>
    </head>
    <body>
    <div class="login-container">
        <h2>Bitte melde dich an</h2>
        <?php if (isset($login_error)) { echo '<p class="error">' . htmlspecialchars($login_error) . '</p>'; } ?>
        <form action="shoutbox.php" method="post">
            <div class="form-group">
                <label for="login">Benutzername:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" name="login_submit" value="Anmelden">
        </form>
    </div>
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
if (!empty($_REQUEST['content'])) {
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
    $shout->saveInDB($db, $_SESSION['user'], $_REQUEST['content']);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ShoutBox</title>

    <style>
        body {
            background-color: #f0f2f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            color: #333;
        }
        .header {
            background-color: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .user-info {
            color: #555;
        }
        .header .user-info strong {
            color: #000;
        }
        .header a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .card {
            background-color: #fefefe;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        input[type="submit"] {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .header-cookie-info {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }
        /* Styling for the shout table */
        .shout-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }
        .shout-table th, .shout-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        .shout-table th {
            background-color: #fcfcfc;
            font-weight: 600;
            color: #555;
        }
        .shout-table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .shout-table .user-cell {
            font-weight: bold;
            width: 150px;
        }
        .shout-table .date-cell {
            width: 160px;
            font-size: 0.9em;
            color: #777;
            white-space: nowrap;
        }
        .no-shouts {
            text-align: center;
            color: #777;
            padding: 2rem;
        }
    </style>
</head>
<body>

<header class="header">
    <div class="user-info">
        Angemeldet als: <strong><?php echo htmlspecialchars($username); ?></strong>
    </div>
    <div class="header-cookie-info">
        <?php
        $cookie_parts = [];
        if (isset($_COOKIE['last_visit'])) {
            $cookie_parts[] = "Letzter Besuch: " . htmlspecialchars($_COOKIE['last_visit']);
        }
        if (isset($_COOKIE['shout_count'])) {
            $cookie_parts[] = "Anzahl Shouts: " . htmlspecialchars($_COOKIE['shout_count']);
        }
        echo implode(' | ', $cookie_parts);
        ?>
    </div>
    <a href="shoutbox.php?logout=1">Abmelden</a>
</header>

<div class="container">
    <div class="card">
        <h2>Neue Nachricht</h2>
        <form action="shoutbox.php" method="post">
            <div class="form-group">
                <label for="user">Name:</label>
                <input type="text" id="user" name="user" value="<?php echo htmlspecialchars($username); ?>" disabled />
            </div>
            <div class="form-group">
                <label for="content">Inhalt:</label>
                <input type="text" id="content" name="content" value="" autofocus />
            </div>
            <input type="submit" name="shout" value="Senden" />
        </form>
    </div>

    <div class="card">
        <h2>Shoutbox</h2>
        <?php
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
    </div>
</div>

</body>
</html>
