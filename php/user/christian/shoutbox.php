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
// Die Datei mit der Funktion einbinden
require 'Shout.php';
$shout = new Shout();
// Abfrage ob einer der Beiden Parameter gesetzt ist
if (!empty($_REQUEST['user']) && !empty($_REQUEST['content'])) {
    $shout->save($_REQUEST['user'], $_REQUEST['content']);
}
$shout->shoutAusgeben();
?>
</body>
</html>
