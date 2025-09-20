<!DOCTYPE html>
<html>
<head>
<title>Filmovi</title>
</head>
<body>
<?php
echo '<h2 align="center">FILMOVI</h2>';
session_start();
require("film.php");
require("funkcije.php");


stampajLogin();
stampajFilter();

//ODJAVLJIVANJE
if (isset($_POST["odjavi"]) && isset($_SESSION["user"])){
    unset($_SESSION["user"]);
    header("Refresh:0");
}

//LOGOVANJE
if (isset($_POST["user"]))
    if (isset($_POST["pass"])) {
        if (verifikacija($_POST["user"], $_POST["pass"])) {
            $_SESSION["user"] = $_POST["user"];
            header("Refresh:0");
        }
        else
            echo '<script>alert("Pogresna kombinacija korisnickog imena i lozinke")</script>';
    }
        

$conn = new PDO("mysql:host=localhost;dbname=bazaproj", "dragan", "markovic");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$nizkol = citajFilmove();
$conn = null;
$nizfilm = array();
foreach ($nizkol as $kol) {
    $film = new Film($kol["Ime"], $kol["Godina"], $kol["Reziser"], $kol["Glumci"], $kol["Trajanje"], $kol["ID"]);
    $nizfilm[] = $film;
}
$brfilm = prikaziFilmove($nizfilm);
echo "<br><br><p align=\"center\">";
$brstr = $brfilm / 5 + 1;
if ($brfilm % 5 == 0)
    $brstr--;
for ($i = 1; $i <= $brstr; $i++) {
    $url = htmlspecialchars($_SERVER["REQUEST_URI"]);
    $url = str_replace("&amp;", "&", $url);
    if(str_contains($url, "str=")){
        $url = preg_replace('!str=[0-9]+!', "str=$i", $url);
    } else if(str_contains($url, "?"))
        $url .= "&str=$i";
    else
        $url .= "?&str=$i";
    $url = htmlspecialchars($url);
    echo "<a href=\"$url\"><span style=\"border: 1px solid black\">&nbsp;&nbsp;&nbsp;&nbsp;$i&nbsp;&nbsp;&nbsp;&nbsp;</span></a>";
}
echo "</p>";
?>
</body>
</html>


