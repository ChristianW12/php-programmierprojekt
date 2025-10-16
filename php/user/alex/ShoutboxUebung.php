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
        require_once('Db.php');
        require_once('functions.php');
        $dsn = 'mysql:dbname=shoutbox;host=db;port=3306;';
        $db = new Db($dsn, 'root', '');

        session_start();


        if (!empty($_REQUEST['login']) && !empty($_REQUEST['password'])) {
            $query = 'SELECT * FROM user WHERE login = "'.$_REQUEST['login'].'" AND passwd = "'.$_REQUEST['password'].'" ';
            $res = $db->query($query);
            $res->setFetchMode(PDO::FETCH_OBJ);
            $row = $res->fetch();
            if($row->passwd == $_REQUEST['password']) {
                $_SESSION['user'] = $row->login;
        }
        }

        if(empty($_SESSION['user'])) {
            echo '
            <form method="post">
            Login / Passwort: <br/>
            <input type="text" name="login"/><br/>
            <input type="password" name="password"/><br/>
            <input type="submit" value="login"/>
            </form>';
            exit;
        }



        if (!isset($_COOKIE['besucht'])) {
            setcookie('besucht', 'Noch nie besucht');
        }
        else {
            setcookie('besucht', date('d.m.Y H:i:s'));
        }
        $shouts = $_COOKIE['shouts'] ?? 0;
        $lastUser = !empty($_COOKIE['lastUser']) ? $_COOKIE['lastUser'] : null;




        if(!empty($_REQUEST['content']) && !empty($_REQUEST['user'])) {
            $shout = new Shout_klasse('user', 'content', $db);
            setcookie('shouts', ++$shouts);
            setcookie('lastUser', $_REQUEST['user']);
            $lastUser = $_REQUEST['user'];

            $shout->save($_REQUEST['user'], $_REQUEST['content'], $db);
        }
        Shout_klasse::listshouts($db);

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
            <td>Letzter Besuch: </td>
            <td><?php echo 'Hallo '.$lastUser. '  dein letzter Besuch war am: '.$_COOKIE['besucht']?></td>
        </tr>
        <tr>
            <td>Shouts: </td>
            <td><?php echo $shouts?></td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <input type="submit" name="shout" value="rufen"/>
            </td>
        </tr>
    </table>
</form>
</body>
</html>