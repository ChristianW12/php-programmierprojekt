<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Übung 2</title>
</head>
<body>
<?php
    $arr = [];
    $arr['marelyn'] = 'Marleyn Monroe';
    $arr['jonny'] = 'Jonny Depp';
    $arr['tom'] = 'Tom Cruise';

    $string = implode(', ', $arr);
    echo $string, '<br />';

    foreach($arr as $key => $name) {
        echo $key . '=>' . $name . '<br />';
    }

    reset ($arr);
    while ($value = current($arr)) {
        echo key($arr) . '=' . $value . '<br />';
        next ($arr);
    }

    reset($arr);
    for ($i = 0; $i < count($arr); ++$i) {
        echo key($arr) . '=' . current($arr) . '<br />';
        next($arr);
    }
?>

</body>
</html>