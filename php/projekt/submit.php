<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    // Hier könnte man die Daten speichern oder weiterverarbeiten
    echo "Danke, $name! Ihre Teilnahme wurde registriert.";
} else {
    echo "Ungültiger Zugriff.";
}
?>