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

    if(!empty($_REQUEST['content']) && !empty($_REQUEST['user'])) {
        switch(strtolower($_REQUEST['user'])) {
            case 'alex':
                $bgColor = 'red';
                break;
            case 'ahristian':
                $bgColor = 'blue';
                break;
            case 'aabrina':
            case 'alira':
                $bgColor = 'green';
                break;
            default:
                $bgColor = '#3399CC';
        }
        $file = fopen('shoutbox.txt', 'a');
        if($file) {
            $zeile = '<tr>
                        <td bgcolor='.$bgColor.'>'.$_REQUEST['user'].'</td>
                        <td bgcolor='.$bgColor.'>'.$_REQUEST['content'].'</td>
                      </tr>';
            fwrite($file, $zeile);
            fclose($file);
        }}
        echo '<table cellspacing="2" align="center" width="350">';
        $file = fopen('shoutbox.txt', 'r');
        if ($file) {
            while (!feof($file)) {
                $zeile = fgets($file);
                echo $zeile;
            }
            fclose($file);
        }
        echo '</table>';

?>

<form action="ShoutboxUebung.php" method="post">
    <table align="center" width="350">
        <tr>
            <td>Name: </td>
            <td><input type="text" name="user" value=""/></td>
        </tr>
        <tr>
            <td>Inhalt: </td>
            <td><input type="text" name="content" value=""</td>
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