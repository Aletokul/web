<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$db = new Database();
$user_id = $_SESSION['user']['id'];
$msg = "";

// Otkazivanje rezervacije
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $cancel_id = (int)$_POST['cancel_id'];
    if ($db->deleteReservation($cancel_id, $user_id)) {
        $msg = "Rezervacija otkazana.";
    } else {
        $msg = "Greška pri otkazivanju.";
    }
}


$myReservations = $db->getUserReservations($user_id);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Moje rezervacije</title>
    <link rel="stylesheet" href="css/myres.css">
</head>
<body>
    <h2>Moje rezervacije</h2>
    <?php if ($msg): ?>
        <p class="msg"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <?php if (empty($myReservations)): ?>
        <p>Nema rezervacija.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Teren</th>
                <th>Datum</th>
                <th>Početak</th>
                <th>Kraj</th>
                <th>Akcija</th>
            </tr>
            <?php foreach ($myReservations as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['date']) ?></td>
                    <td><?= htmlspecialchars($r['start_time']) ?></td>
                    <td><?= htmlspecialchars($r['end_time']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="cancel_id" value="<?= $r['id'] ?>">
                            <button type="submit">Otkaži</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
