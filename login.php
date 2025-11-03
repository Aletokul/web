<?php
session_start();
require_once "database.php";

$db = new Database();

if (!isset($_SESSION['user']) && isset($_COOKIE['remember_me'])) {
    $user = $db->getUserByToken($_COOKIE['remember_me']);
    if ($user) {
        $_SESSION['user'] = $user;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember = isset($_POST["remember"]);

    $user = $db->login($username, $password);

    if ($user) {
        $_SESSION["user"] = $user;

        if ($remember) {
            // Generisanje nasumičnog token-a
            $token = bin2hex(random_bytes(16));

            // Čuvanje tokena u bazi
            $db->storeRememberToken($user['id'], $token);

            // Postavljanje cooke-a
            setcookie("remember_me", $token, time() + (30*24*60*60), "/", "", false, true);
        }

        header("Location: index.php");
        exit;
    } else {
        $error = "Pogrešno korisničko ime ili lozinka!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<form method="POST" action="">
    <h2>Prijava</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <label>Korisničko ime:</label>
    <input type="text" name="username" required>

    <label>Lozinka:</label>
    <input type="password" name="password" required>
    <label for="remember" class="checkbox-label">Zapamti me</label>
    <input type="checkbox" id="remember" name="remember">
    <br><br>

    <a href="index.php" class="btn">Nazad</a>
    <button type="submit">Uloguj se</button>
</form>
</body>
</html>
