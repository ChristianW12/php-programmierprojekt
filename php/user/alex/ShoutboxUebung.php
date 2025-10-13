<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<?php

        require_once('functions.php');
        $shout = new Shout('user', 'content');
        if(!empty($_REQUEST['content']) && !empty($_REQUEST['user'])) {
        $shout->saveInTxt($_REQUEST['user'], $_REQUEST['content']);
    }
        Shout::listshouts();
?>

<form action="ShoutboxUebung.php" method="post">
    <table align="center" width="350">
        <tr>
            <td>Name: </td>
            <td><input type="text" name="user" value=""/></td>
        </tr>
        <tr>
            <td>Inhalt: </td>
            <td><input type="text" name="content" value=""></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="shout" value="rufen"/>
            </td>
        </tr>
    </table>
</form>
</body>
</html>