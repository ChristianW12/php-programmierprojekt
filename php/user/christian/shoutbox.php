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
                    <input type="submit" name="shout" value="rufen" />
                </td>
            </tr>
        </table>
    </form>
<?php
require_once 'functions.php';
if (isset($_POST['shout'])) {
    save($_POST['user'], $_POST['content']);
}
?>
<?php
$file = fopen('shouts.txt', 'r');
if ($file) {
    while (!feof($file)) {
        $zeile = fgets($file);
        echo $zeile;
    }
    fclose($file);
} else {
    echo 'Datei nicht lesbar!';
}
?>
</body>
</html>