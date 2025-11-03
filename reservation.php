<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$db = new Database();
$user_id = $_SESSION['user']['id'];

// izabrani teren i datum
$field_id = $_GET['field_id'] ?? 1; 
$date = $_GET['date'] ?? date("Y-m-d");
$msg = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $field_id = $_POST['field_id']; 
    $date = $_POST['date'];

    if (strtotime($date) < strtotime(date("Y-m-d"))) {
        $msg = "Ne možete rezervisati termin u prošlosti!";
    } else {
        if ($db->insertReservation($user_id, $field_id, $date, $start_time, $end_time)) {
            $msg = "Uspešno rezervisano!";
        } else {
            $msg = "Termin zauzet!";
        }
    }
}


$fields = $db->getFields();


$reservations = $db->getReservationsByFieldAndDate($field_id, $date);


$occupied = [];
foreach ($reservations as $r) {
    $start = strtotime($r['start_time']);
    $end = strtotime($r['end_time']);
    while ($start < $end) {
        $occupied[] = date("H:i", $start);
        $start = strtotime("+1 hour", $start);
    }
}


function slots($from="08:00", $to="22:00") {
    $out = [];
    $cur = strtotime($from);
    $end = strtotime($to);
    while ($cur < $end) {
        $next = strtotime("+1 hour", $cur);
        $out[] = [date("H:i",$cur), date("H:i",$next)];
        $cur = $next;
    }
    return $out;
}
$allSlots = slots();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rezervacija terena</title>
    <link rel="stylesheet" href="css/reservation.css">
</head>
<body>
    <header>
        <h1>Rezervacija terena</h1>
    </header>

<?php if ($msg): ?>
    <p><strong><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></strong></p>
<?php endif; ?>

<!-- izbor terena i datuma -->
<form method="get">
    <?php
        $field = $db->getFieldById($field_id);
    ?>
    <h2>Teren: <?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?></h2>

    <label>Datum:</label>
    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" onchange="this.form.submit()">
    
    <a href="index.php">Početna</a>
</form>

<center><h2>Dostupni termini za <?= htmlspecialchars($date) ?></h2></center>
<table border="1">
<tr><th>Vreme</th><th>Status</th></tr>
<?php foreach ($allSlots as [$s,$e]): ?>
    <tr>
        <td><?= "$s - $e" ?></td>
        <td>
        <?php if (in_array($s, $occupied)): ?>
            Zauzeto
        <?php else: ?>
            <form method="post" style="display:inline;">
                <input type="hidden" name="field_id" value="<?= htmlspecialchars($field_id, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="date" value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="start_time" value="<?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="end_time" value="<?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit">Rezerviši</button>
            </form>
        <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
</body>
</html>
