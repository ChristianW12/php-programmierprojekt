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
$file = fopen(__FILE__, 'r');

echo '<pre>';
while (!feof($file)) {
    // eine Zeile ausgeben
    $zeile = fgets($file);
    echo htmlentities($zeile);
}
echo '</pre>';
?>
</body>
</html>