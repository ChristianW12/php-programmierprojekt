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
<form action="shoutbox.php" method="get" style="margin-top: 20px;">
    Name: <input type="text" name="user" value="" /><br />
    <input type="submit" name="shout" value="rufen" />
<?php
if (!empty($_REQUEST['user'])) {
    echo '<br /> Request: '.$_REQUEST['user'];
}elseif (!empty($_POST['shout'])) {
    echo '<br /> Post: ' . $_POST["user"] ;
}
elseif (!empty($_GET['user'])) {
    echo '<br /> Get: '. $_GET["user"];
}
?>
</form>
<form action="shoutbox.php" method="get" style="margin-top: 20px;">
    Name: <input type="text" name="content" value="" /><br />
    <input type="submit" name="shout" value="rufen" />
</form>

<?php
if(!empty($_GET['content'])) {
    echo '<table border="2" style="margin-top: 20px;">
            <tr>
                <th>Inhalt</th>
            </tr>
            <tr>
                <td>' . $_GET['content'] . '</td>
            </tr>
          </table>';
}
?>
</body>
</html>