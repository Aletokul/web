<!DOCTYPE html>
<html>
<head>
<title>Filmovi</title>
</head>
<body>
<?php
if (!isset($_GET["f"]) || $_GET["f"] == "")
    exit("<p>Doslo je do greske</p>");

echo "</br><a href=\"index.php\"><button type=\"button\">NAZAD NA GLAVNU STRANICU</button></a></br></br></br>";

$id = $_GET["f"];
$conn = new PDO("mysql:host=localhost;dbname=bazaproj", "dragan", "markovic");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$rezultat = array();
$sql = "SELECT * FROM filmovi WHERE ID=$id";
$rez = null;
try {
    $rez = $conn->query($sql);
}
catch(PDOException $e){
    $e->getMessage();
}
$conn = null;

if($rez->rowCount() == 0)
    exit("<p>Ovaj film ne postoji u nasoj bazi</p>");

$rez = $rez->fetch();

$ime = $rez["Ime"];
$godina = $rez["Godina"];
$reziser = $rez["Reziser"];
$glumci = $rez["Glumci"];
$trajanje = $rez["Trajanje"];
$id = $rez["ID"];
$lokacija = glob("posteri/$id.*")[0];
$str = "<img src=\"$lokacija\" alt=\"$ime poster\" height=\"500\">";
$str .= "<h2>$ime ($godina)</h2>";
$str .= "<p>Rezija: $reziser</p>";
$str .= "<p>Glume: $glumci</p>";
$str .= "<p>Trajanje: $trajanje min</p>";
echo $str;

if (isset($_POST["dodaj"])) {
    if (!isset($_COOKIE["omiljeni"])) {
        setcookie("omiljeni", "$id,", time() + 60 * 60 * 24 * 30);
        header("Refresh:0");
    }
    else if (!str_contains($_COOKIE["omiljeni"], $id)) {
        $kuki = $_COOKIE["omiljeni"];
        $kuki .= "$id,";
        setcookie("omiljeni", $kuki, time()+60*60*24*30);
        header("Refresh:0");
    }
}

if (isset($_POST["obrisi"])) {
    if(isset($_COOKIE["omiljeni"]) && str_contains($_COOKIE["omiljeni"], $id)) {
        $kuki = $_COOKIE["omiljeni"];
        $kuki = str_replace("$id,", "", $kuki);
        setcookie("omiljeni", $kuki, time()+60*60*24*30);
        header("Refresh:0");
    }
}

if(!isset($_COOKIE["omiljeni"]) || !str_contains($_COOKIE["omiljeni"], $id)) {
    echo "<form method=\"post\">
    <input type=\"submit\" name=\"dodaj\"
            value=\"Dodaj u omiljene\"/>
    </form>";
} 
else {
    echo "<form method=\"post\">
    <input type=\"submit\" name=\"obrisi\"
            value=\"Obrisi iz omiljenih\"/>
    </form>";
}

session_start();
if (isset($_SESSION["user"])) {
    echo "<form action=\"obrisi.php\" method=\"post\">
    <input hidden type=\"text\" id=\"id\" name=\"id\" value=\"$id\">
    <input type=\"submit\" name=\"obrisi\" value=\"Obrisi film\"/>
    </form>";
}
?>
</body>
</html>