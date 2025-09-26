<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-top: 20px;
        }
        label, input, button {
            display: block;
            margin-bottom: 10px;
        }
    </style>
    <title>Aufgabe 1</title>
</head>
<body>
<p>Aufgabe 1</p>
<?php
    $arr = [];
    $arr[] = "Cristian";
    $arr[] = "Melina";
    $arr[] = "Luca";
    $arr[] = "Alex";
    $anzahl = count($arr);

    echo "<p>Das Array enthält $anzahl Elemente.</p>";

    $index = isset($_POST['index']) ? (int)$_POST['index'] : null;
?>
<form method="post">
    <label for="index">Welches Element (0 bis <?php echo $anzahl-1; ?>) soll ausgegeben werden?</label>
    <input type="number" id="index" name="index" min="0" max="<?php echo $anzahl-1; ?>" required>
    <button type="submit">Ausgeben</button>
</form>
<?php
    if ($index !== null) {
        if ($index >= 0 && $index < $anzahl) {
            echo "<p>Element $index: " . htmlspecialchars($arr[$index]) . "</p>";
        } else {
            echo "<p>Ungültiger Index!</p>";
        }
    }
?>
</body>
</html>