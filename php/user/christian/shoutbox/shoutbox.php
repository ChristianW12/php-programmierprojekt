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
if( !empty($_REQUEST['user']) && !empty ($_REQUEST['content']) ) {
    $bgColor  = '#3399CC';
    echo ' 
    <table cellspacing="2" align="center" width="350"> 
      <tr> 
        <td bgcolor="'.$bgColor.'">'.$_REQUEST['user'].'</td> 
        <td bgcolor="'.$bgColor.'">'.$_REQUEST['content'].'</td> 
      </tr> 
    </table>';
}
?>
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
</body>
</html>