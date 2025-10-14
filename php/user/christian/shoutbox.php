<?php
date_default_timezone_set('Europe/Berlin');
require 'Db.php';
require 'Shout.php';

$shout = new Shout();

// Setzt bei jedem Laden der Seite ein Cookie für die letzte Besuchszeit.
// Der Wert ist beim *nächsten* Laden der Seite verfügbar.
setcookie('last_visit', date('d.m.Y H:i:s'), time() + (86400 * 30), "/");

//
if (!empty($_REQUEST['user']) && !empty($_REQUEST['content'])) {
    // Set username cookie for future visits
    setcookie('username', $_REQUEST['user'], time() + (86400 * 30), "/");

    // Hochzählen der Shouts für diesen User
    $shout_count = ($_COOKIE['shout_count'] ?? 0) + 1;
    setcookie('shout_count', $shout_count, time() + (86400 * 30), "/");
    // Verbindung zur Datenbank
    $dsn = 'mysql:dbname=shoutbox;host=db;port=3306';
    try {
        $db = new Db( $dsn, 'root', '' );
    } catch ( PDOException $e ) {
        exit( 'Connect failed: '.$e->getMessage() );
    }

    //Speichern in der DB von User und Content
    $shout->saveInDB($db, $_REQUEST['user'], $_REQUEST['content']);
    unset($db);
    //Redirect auf die shoutbox.php um ein erneutes Senden vom Nutzer und Content
    //beim Reload der Seite zu verhindern
    header("Location: shoutbox.php");
    exit();
}

// Get username from cookie to pre-fill the form
$username = $_COOKIE['username'] ?? '';
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
<?php
// Display info from cookies
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
            <td><input type="text" name="content" value="" /></td>
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
// Skript für Datenbankverbindung
$dsn = 'mysql:dbname=shoutbox;host=db;port=3306';
try {
    // Nicht mehr POD sondern Db da wir von dieser erben
    // und in dieser nun Error Handling machen
    $db = new Db( $dsn, 'root', '' );
} catch ( PDOException $e ) {
    exit( 'Connect failed: '.$e->getMessage() );
}
// Display all shouts from the database
$shout->outputShoutDB($db);
unset($db);
//
// close DB connection
//if not needed anymore
//
//Die Datei mit der Funktion einbinden
//Erstellen eines Objekts
//$shout = new Shout();
//if (!empty($_REQUEST['user']) && !empty($_REQUEST['content'])) {
//    $shout->saveInTxt($_REQUEST['user'], $_REQUEST['content']);
//}
//Ausgabe des Inhalts der shouts.txt Datei mit Hilfe der shoutAusgeben Methode
//$shout->shoutAusgebenTxt();

?>
</body>
</html>