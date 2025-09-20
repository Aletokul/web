<?php
if(empty($_POST) || !isset($_POST["id"])){
    header('Location:index.php');
    die();
}
$id = htmlspecialchars($_POST["id"]);
$sql = "DELETE FROM filmovi WHERE ID=$id";
$conn = new PDO("mysql:host=localhost;dbname=bazaproj", "dragan", "markovic");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    $conn->query($sql);
}
catch(PDOException $e){
    $e->getMessage();
}
$conn = null;
if(isset($_COOKIE["omiljeni"]) && str_contains($_COOKIE["omiljeni"], $id)) {
    $kuki = $_COOKIE["omiljeni"];
    $kuki = str_replace("$id,", "", $kuki);
    setcookie("omiljeni", $kuki, time()+60*60*24*30);
}

$filename = glob("posteri/$id.*")[0];
unlink($filename);

echo '<script>alert("FILM USPESNO OBRISAN")</script>';
header('Refresh: 0; URL=index.php');
die();
?>