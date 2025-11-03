<?php
session_start();
require 'config.php';

// sigurnosna provera role-a
$role = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : null;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rezervacija sportskih terena</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header>   
    <nav>
        <div class="logo">
            <h1>Rezervacija terena</h1>
        </div>
        <ul>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <li><?php echo ucfirst($_SESSION['user']['username']); ?></li>
                    <li><a href="manage_fields.php">Upravljanje terenima</a></li>
                    <li><a href="logout.php">Odjava</a></li>

            <?php elseif (isset($_SESSION['user'])): ?>
                <?php echo htmlspecialchars(ucfirst($_SESSION['user']['username']), ENT_QUOTES, 'UTF-8'); ?>
                <li><a href="logout.php">Odjava</a></li>
                <li><a href="myres.php">Moje rezervacije</a></li>
                <li><a href="fields.php">Pregled terena</a></li>

            <?php else: ?>
                <li><a href="register.php">Registracija</a></li>
                <li><a href="login.php">Prijava</a></li>
                <li><a href="fields.php">Pregled terena</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    </header>

    <section>
        <h2>Dobrodošli na sistem za rezervaciju sportskih terena</h2>
        <p>Brzo i jednostavno rezervišite fudbal, tenis ili košarku online.</p>
    </section>
</body>
</html>
