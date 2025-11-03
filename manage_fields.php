<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$msg = "";

// BRISANJE termina
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if ($db->deleteReservation($delete_id)) {
        $msg = "Termin uspešno obrisan!";
    } else {
        $msg = "Greška pri brisanju termina!";
    }
}

// PROMENA termina
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    if ($db->updateReservationTime($edit_id, $start_time, $end_time)) {
        $msg = "Termin uspešno ažuriran!";
    } else {
        $msg = "Greška pri ažuriranju termina!";
    }
}


$reservations = $db->getAllReservations();
$fields = $db->getFields();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upravljanje terminima</title>
    <link rel="stylesheet" href="css/manage_fields.css">
</head>
<body>
<h1>Upravljanje terminima</h1>

<?php if($msg) echo "<p style='color:green;'>" . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . "</p>"; ?>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Teren</th>
        <th>Datum</th>
        <th>Start</th>
        <th>End</th>
        <th>Akcije</th>
    </tr>
    <?php foreach($reservations as $r): ?>
    <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['field_name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($r['date'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($r['start_time'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($r['end_time'], ENT_QUOTES, 'UTF-8') ?></td>
        <td>
            <a href="?delete_id=<?= $r['id'] ?>" onclick="return confirm('Da li ste sigurni da želite da obrišete rezervaciju?')">Obriši</a>
            <form method="post" style="display:inline;">
            <input type="hidden" name="edit_id" value="<?= (int)$r['id'] ?>">
            <input type="time" name="start_time" value="<?= htmlspecialchars($r['start_time'], ENT_QUOTES, 'UTF-8') ?>" required>
            <input type="time" name="end_time" value="<?= htmlspecialchars($r['end_time'], ENT_QUOTES, 'UTF-8') ?>" required>
            <button type="submit">Promeni</button>
        </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
