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
<br />
<form action="shoutbox.php" method="post">
    <table align="center" width="350">
        <tr>
            <td>Name:</td>
            <td><input type="text" name="user" value="" /></td>
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
require 'Db.php';
require 'Shout.php';
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
echo 'Connection established!';

$shout = new Shout();

// Abfrage ob einer der Beiden Parameter gesetzt ist
if (!empty($_REQUEST['user']) && !empty($_REQUEST['content'])) {
    // saveInDB aufrufen und das DB-Objekt übergeben
    $shout->saveInDB($db, $_REQUEST['user'], $_REQUEST['content']);
}

$shout->outputShoutDB($db);
unset($db);
// close DB connection
// if not needed anymore
/*
 * ============================================================
// Die Datei mit der Funktion einbinden
// Erstellen eines Objekts
$shout = new Shout();
// Abfrage ob einer der Beiden Parameter gesetzt ist
if (!empty($_REQUEST['user']) && !empty($_REQUEST['content'])) {
    $shout->saveInTxt($_REQUEST['user'], $_REQUEST['content']);
}
// Ausgabe des Inhalts der shouts.txt Datei mit Hilfe der shoutAusgeben Methode
$shout->shoutAusgebenTxt();
 */
?>
</body>
</html>
