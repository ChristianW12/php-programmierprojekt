<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Array mit Foreach</title>
</head>
<body>
<?php
$arr = [
    'christian' => "Cristian",
    'melina' => "Melina",
    'luca' => "Luca",
    'alex' => "Alex"
];

// 1. Kommaseparierte Ausgabe der Werte
echo implode(', ', $arr) . '<br>';

// 2. Ausgabe mit foreach
foreach ($arr as $key => $value) {
    echo "Index: $key, Wert: $value<br>";
}

// 3. Ausgabe mit for und array_keys
$keys = array_keys($arr);
for ($i = 0; $i < count($arr); $i++) {
    $key = $keys[$i];
    echo "Index: $key, Wert: {$arr[$key]}<br>";
}
?>
</body>
</html>