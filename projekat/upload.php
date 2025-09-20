<?php

if (empty($_POST)) {
    header('Location:dodaj.php');
    die();
}

if (!isset($_POST["ime"]) || !isset($_POST["god"]) || !isset($_POST["rez"]) || !isset($_POST["glum"]) || !isset($_POST["traj"]) ||
$_POST["ime"] == "" || $_POST["god"] == "" || $_POST["rez"] == "" || $_POST["glum"] == "" || $_POST["traj"] == ""  || !isset($_FILES["poster"])) {
    echo '<script>alert("Niste uneli sve podatke")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

//PROVERA VELICINE
if ($_FILES["poster"]["size"] > 5000000) {
    echo '<script>alert("Fajl koji ste uneli je prevelik")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

//PROVERA DA LI JE SLIKA
$slika = getimagesize($_FILES["poster"]["tmp_name"]);
if($slika === false) {
    echo '<script>alert("Fajl koji ste uneli nije slika")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

$ime = htmlspecialchars(trim($_POST["ime"]));
$god = htmlspecialchars(intval(trim($_POST["god"])));
$rez = htmlspecialchars(trim($_POST["rez"]));
$glum = htmlspecialchars(trim($_POST["glum"]));
$traj = htmlspecialchars(intval(trim($_POST["traj"])));

//PROVERE ZA VALIDNOST
if ($god < 1878) {
    echo '<script>alert("Niste uneli validnu godinu")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

if ($traj < 1) {
    echo '<script>alert("Niste uneli validnu duzinu")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

if ($traj < 1) {
    echo '<script>alert("Niste uneli validnu duzinu")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

if (strlen($ime) > 100) {
    echo '<script>alert("Niste uneli validno ime")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

if (strlen($rez) > 100) {
    echo '<script>alert("Niste uneli validnog rezisera")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

if (strlen($glum) > 100) {
    echo '<script>alert("Uneli ste predvise glumaca")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

$conn = new PDO("mysql:host=localhost;dbname=bazaproj", "dragan", "markovic");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//PROVERA DA LI FILM VEC POSTOJI
$sql = "SELECT * FROM filmovi WHERE Ime='$ime' AND Godina=$god AND Reziser='$rez' AND Trajanje=$traj";
$rezultat = null;
try {
    $rezultat = $conn->query($sql);
}
catch(PDOException $e){
    $e->getMessage();
}

if ($rezultat->rowCount() != 0) {
    $conn = null;
    echo '<script>alert("Dati film vec postoji")</script>';
    header('Refresh: 0; URL=dodaj.php');
    die();
}

//DODAVANJE FILMA
$sql = "INSERT INTO `filmovi` (`ID`, `Ime`, `Godina`, `Reziser`, `Glumci`, `Trajanje`) VALUES (NULL, '$ime', $god, '$rez', '$glum', $traj)";
try {
    $rezultat = $conn->query($sql);
}
catch(PDOException $e){
    $e->getMessage();
}

//TRAZENJE ID-A
$sql = "SELECT ID FROM filmovi WHERE Ime='$ime' AND Godina=$god AND Reziser='$rez' AND Trajanje=$traj";
try {
    $rezultat = $conn->query($sql);
}
catch(PDOException $e){
    $e->getMessage();
}
$conn = null;

//CUVANJE SLIKE
$rezultat = $rezultat->fetch();
$id = $rezultat["ID"];
$putanja = "posteri/" . basename($_FILES["poster"]["name"]);
$tip = strtolower(pathinfo($putanja, PATHINFO_EXTENSION));
$putanja = "posteri/$id.$tip";
move_uploaded_file($_FILES["poster"]["tmp_name"], $putanja);

echo '<script>alert("FILM USPESNO DODAT")</script>';
header('Refresh: 0; URL=dodaj.php');
die();

?>