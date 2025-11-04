<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../src/Db.php';
require __DIR__ . '/../src/db-connection.php';

$registration_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_submit'])) {
    $user_name = $_POST['name'] ?? '';
    $user_mail = $_POST['email'] ?? '';
    $user_password = $_POST['password'] ?? '';
    $user_password_repeat = $_POST['password_repeat'] ?? '';

    if ($user_password !== $user_password_repeat) {
        $registration_error = "Die Passwörter stimmen nicht überein.";
    } else {
        $db = mitDBverbinden();
        $stmt = $db->prepare("SELECT * FROM users WHERE mail = :mail");
        $stmt->execute(['mail' => $user_mail]);
        if ($stmt->fetch()) {
            $registration_error = "Ein Benutzer mit dieser E-Mail-Adresse existiert bereits.";
        } else {
            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (name, mail, password, plz, str, ort) VALUES (:name, :mail, :password, :plz, :str, :ort)");
            $stmt->execute([
                ':name' => $user_name,
                ':mail' => $user_mail,
                ':password' => $hashed_password,
                ':plz' => $_POST['plz'],
                ':str' => $_POST['str'],
                ':ort' => $_POST['ort']
            ]);
            header('Location: login.php');
            exit();
        }
    }
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
