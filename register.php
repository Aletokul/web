<?php
session_start();
require_once "database.php";

$db = new Database();
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirm_password = trim($_POST["confirm_password"] ?? "");

    
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Sva polja su obavezna!";
    } elseif ($password !== $confirm_password) {
        $error = "Lozinke se ne poklapaju!";
    } else {
        if ($db->register($username, $password)) {
            header("Location: login.php");
        } else {
            $error = "Korisničko ime već postoji!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registracija</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<form method="POST" action="">
    <h2>Registracija</h2>

    <?php if (isset($message)) echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <input type="text" name="username" placeholder="Korisničko ime" required>
    <input type="password" name="password" placeholder="Lozinka" required>
    <input type="password" name="confirm_password" placeholder="Potvrdi lozinku" required>

    <button type="submit">Registruj se</button>
    <br><br>
    <a href="login.php">Već imate nalog? Prijavite se</a>
</form>

</body>
</html>
