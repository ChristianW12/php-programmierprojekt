<?php
// Stellt den Seitentitel und zusätzliche CSS-Dateien bereit
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Auktify') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles/styles.css">

    <style>
        h1 {
            font-family: 'Arvo', serif;
        }
    </style>

    <?php if (isset($extraCss) && is_array($extraCss)):
        foreach ($extraCss as $cssFile):
            ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($cssFile) ?>">
        <?php
        endforeach;
    endif; ?>
</head>
<body>
