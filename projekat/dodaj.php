<!DOCTYPE html>
<html>
<head>
<title>Filmovi</title>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION["user"])) {
    header('Location:index.php');
    die();
}

echo "</br><a href=\"index.php\"><button type=\"button\">NAZAD NA GLAVNU STRANICU</button></a></br></br></br>";

echo "<h2>Dodavanje filmova</h2>
    <form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">
    <label for=\"ime\">Ime filma</label><br>
    <input type=\"text\" id=\"ime\" name=\"ime\"><br><br>
    <label for=\"god\">Godina</label><br>
    <input type=\"text\" id=\"god\" name=\"god\"><br><br>
    <label for=\"rez\">Reziser</label><br>
    <input type=\"text\" id=\"rez\" name=\"rez\"><br><br>
    <label for=\"glum\">Glumci (razdvojeni zarezom)</label><br>
    <input type=\"text\" id=\"glum\" name=\"glum\"><br><br>
    <label for=\"traj\">Duzina (u min)</label><br>
    <input type=\"text\" id=\"traj\" name=\"traj\"><br><br>
    <label for=\"poster\">Poster</label><br>
    <input type=\"file\" name=\"poster\" id=\"poster\"><br><br><br>
    <input type=\"submit\" value=\"Potvrdi\">
    </form></br></br></br>";


?>
</body>
</html>