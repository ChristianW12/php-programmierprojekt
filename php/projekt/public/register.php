<?php //Refactored
// Session sauber initialisieren, um User-Kontext und Nachrichtenstatus zu kennen.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../src/UserService.php';
try {
   $userService = new UserService();
} catch (\Throwable $th) {
    echo "Fehler: " . $th->getMessage();
}

$registration_error = '';

// Formular-Submit entgegennahmen und über den Service registrieren.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_submit'])) {
    $payload = [
        'name' => $_POST['name'] ?? '',
        'mail' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'password_repeat' => $_POST['password_repeat'] ?? '',
        'plz' => $_POST['plz'] ?? '',
        'str' => $_POST['str'] ?? '',
        'ort' => $_POST['ort'] ?? '',
    ];

    $result = $userService->registerUser($payload);

    if ($result['success']) {
        header('Location: login.php');
        exit;
    }

    $registration_error = $result['message'] ?? 'Registrierung fehlgeschlagen. Bitte erneut versuchen.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Registrieren</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<main>
    <section class="section">
        <div class="section-text center">
            <h1>Registrieren</h1>
            <?php if (!empty($registration_error)): ?>
                <p class="error"><?php echo $registration_error; ?></p>
            <?php else: ?>
                <p>Erstellen Sie ein neues Konto.</p>
            <?php endif; ?>
            <form action="register.php" method="post" class="register-form">
                <div>
                    <label for="name">Benutzername</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="email">E-Mail-Adresse</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="password">Passwort</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <label for="password_repeat">Passwort wiederholen</label>
                    <input type="password" id="password_repeat" name="password_repeat" required>
                </div>
                <div>
                    <label for="plz">Postleitzahl</label>
                    <input type="text" id="plz" name="plz" required>
                </div>
                <div>
                    <label for="str">Straße</label>
                    <input type="text" id="str" name="str" required>
                </div>
                <div>
                    <label for="ort">Ort</label>
                    <input type="text" id="ort" name="ort" required>
                </div>
                <div>
                    <button type="submit" class="primary-action" name="register_submit">Registrieren</button>
                </div>
            </form>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
<script src="scripts/app.js"></script>
</body>
</html>
