<?php // Refactored
// Session initialisieren, damit wir Benutzerkontext und Form-Abgaben verfolgen können.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Zugriff nur für eingeloggte Nutzer erlauben.
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/../src/UserService.php';

try {
   $userService = new UserService();
} catch (\Throwable $th) {
    echo "Fehler: " . $th->getMessage();
}

$verlinkungHomepage = 'index.php';
$profileError = null;

// Aktuellen Nutzer per Mail laden – ohne Datensatz keine Bearbeitung möglich.
$userMail = $_SESSION['user_mail'] ?? '';
$currentUser = $userService->getUserByMail($userMail);

if (!$currentUser) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Formular-Submit verarbeiten und Profildaten aktualisieren.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formInput = [
        'name' => $_POST['name'] ?? '',
        'mail' => $_POST['mail'] ?? '',
        'plz' => $_POST['plz'] ?? '',
        'str' => $_POST['strasse'] ?? '',
        'ort' => $_POST['ort'] ?? '',
    ];

    // Trim angewendet, damit unnötige Leerzeichen nicht gespeichert werden.
    $normalizedInput = array_map('trim', $formInput);

    if ($userService->updateUserProfile((int)$currentUser['user_id'], $normalizedInput)) {
        $_SESSION['user_mail'] = $normalizedInput['mail'];
        header('Location: profile.php');
        exit;
    }

    // Bei Fehlern Werte im Formular erhalten und Hinweis anzeigen.
    $profileError = 'Profil konnte nicht gespeichert werden. Bitte prüfen Sie Ihre Daten.';
    $currentUser = array_merge($currentUser, $normalizedInput);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auktify | Profil bearbeiten</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main>
        <section class="section">
            <div class="section-text center profile-container">
                <h1><strong>Profil bearbeiten</h1>
                <hr>
                <?php if ($profileError): ?>
                    <p class="error"><?php echo htmlspecialchars($profileError); ?></p>
                <?php endif; ?>
                <form action="profile-edit.php" method="post">
                    <h3><strong>Persönliche Informationen</strong></h3>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" name="name" placeholder="Ihr Name" required value="<?php echo htmlspecialchars($currentUser['name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="mail">E-Mail</label>
                        <input id="mail" type="email" name="mail" placeholder="Ihre E-Mail-Adresse" required value="<?php echo htmlspecialchars($currentUser['mail'] ?? ''); ?>">
                    </div>
                    <hr>
                    <h3><strong>Adresse</strong></h3>
                    <div class="form-group">
                        <label for="strasse">Straße</label>
                        <input id="strasse" type="text" name="strasse" placeholder="Ihre Straße und Hausnummer" required value="<?php echo htmlspecialchars($currentUser['str'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="ort">Ort</label>
                        <input id="ort" type="text" name="ort" placeholder="Ihr Wohnort" required value="<?php echo htmlspecialchars($currentUser['ort'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="plz">Postleitzahl</label>
                        <input id="plz" type="text" name="plz" placeholder="Ihre Postleitzahl" required value="<?php echo htmlspecialchars($currentUser['plz'] ?? ''); ?>">
                    </div>
                    <div class="profile-actions">
                        <button type="submit" class="btn">Speichern</button>
                        <a href="profile.php" class="btn btn-danger">Abbrechen</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script src="scripts/app.js"></script>
</body>
</html>
