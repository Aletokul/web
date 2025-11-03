<?php
session_start();
require 'config.php';
require 'database.php';

$db = new Database();
$fields = $db->getFields();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pregled terena</title>
    <link rel="stylesheet" href="css/fields.css">
</head>
<body>
<header>
    <h1>Pregled dostupnosti terena</h1>
</header>
<div class="container">
<?php foreach($fields as $field): ?>
    <div class="tereni">
        <?php
        // format slike sport_tip
        $image = strtolower($field['sport_type']) . '_' . strtolower($field['type']) . '.jpg';
        ?>
        <img src="css/images/<?php echo $image; ?>">
        <h3><?php echo $field['name']; ?></h3>
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="login.php" class="btn">Prijavi se da bi rezervisao</a>
        <?php else: ?>
            <a href="reservation.php?field_id=<?= $field['id'] ?>" class="btn">Rezerviši</a>
        <?php endif ?>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>
