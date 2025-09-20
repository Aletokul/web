<?php

function ispunjavaUslove(Film $film){
    $uslov = true;
    if(isset($_GET["godmin"]) && $_GET["godmin"] != "" && is_numeric($_GET["godmin"]) && $film->getGodina() < $_GET["godmin"])
        $uslov = false;
    else if(isset($_GET["godmax"]) && $_GET["godmax"] != "" && is_numeric($_GET["godmax"]) && $film->getGodina() > $_GET["godmax"])
        $uslov = false;
    else if(isset($_GET["rez"]) && $_GET["rez"] != "" && !str_contains(strtolower($film->getReziser()), strtolower(htmlspecialchars_decode($_GET["rez"]))))
        $uslov = false;
    else if(isset($_GET["glum"]) && $_GET["glum"] != null && !str_contains(strtolower($film->getGlumci()), strtolower(htmlspecialchars_decode($_GET["glum"]))))
        $uslov = false;
    else if(isset($_GET["ime"]) && $_GET["ime"] != null && !str_contains(strtolower($film->getIme()), strtolower(htmlspecialchars_decode($_GET["ime"]))))
        $uslov = false;
    return $uslov;
}

function prikaziFilm(Film $film){
    
    $ime = $film->getIme();
    $godina = $film->getGodina();
    $reziser = $film->getReziser();
    $glumci = $film->getGlumci();
    $trajanje = $film->getTrajanje();
    $id = $film->getId();
    $lokacija = glob("posteri/$id.*")[0];
    $str = "<tr>";
    $str .= "<td><a href=\"filmovi.php?f=$id\"><img src=\"$lokacija\" alt=\"$ime poster\" height=\"200\"></a></td>";
    $str .= "<td>$ime</td>";
    $str .= "<td>$godina.</td>";
    $str .= "<td>$reziser</td>";
    $str .= "<td>$glumci</td>";
    $str .= "<td>$trajanje min</td>";
    $str .= "</tr>";
    echo $str;
}

function prikaziFilmove(array $niz){
    echo "<table style=\"width:100%\">
    <tr>
      <th>Poster</th>
      <th>Ime</th>
      <th>Godina</th>
      <th>Reziser</th>
      <th>Glumci</th>
      <th>Trajanje</th>
    </tr>";
    $str = 1;
    if (isset($_GET["str"]) && is_numeric($_GET["str"]))
        $str = intval($_GET["str"]);
    $br = 0;
    foreach ($niz as $film) {
        if (ispunjavaUslove($film)) {
            $br++;
            if($br <= $str * 5 && $br > ($str - 1) * 5)
                prikaziFilm($film);
        }
    }
    echo "</table>";
    return $br;
}

function citajFilmove(){
    $conn = new PDO("mysql:host=localhost;dbname=bazaproj", "dragan", "markovic");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $rezultat = array();
    $sql = "SELECT * FROM filmovi";
    try {
        $rez = $conn->query($sql);
        foreach ($rez as $red)
            $rezultat[] = $red;
    }
    catch(PDOException $e){
        $e->getMessage();
    }
    $conn = null;
    return $rezultat;
}

function stampajFilter(){
    $i = "";
    if (isset($_GET["ime"]))
        $i = $_GET["ime"];
    $ma = "";
    if (isset($_GET["godmax"]))
        $ma = $_GET["godmax"];
    $mi = "";
    if (isset($_GET["godmin"]))
        $mi = $_GET["godmin"];
    $r = "";
    if (isset($_GET["rez"]))
        $r = $_GET["rez"];
    $g = "";
    if (isset($_GET["glum"]))
        $g = $_GET["glum"];
    
    echo "<form action=\"index.php\">
    <label for=\"ime\">Ime filma</label><br>
    <input type=\"text\" id=\"ime\" name=\"ime\" value=\"$i\"><br>
    <label for=\"godmin\">Minimalna godina</label><br>
    <input type=\"text\" id=\"godmin\" name=\"godmin\" value=\"$mi\"><br>
    <label for=\"godmax\">Maksimalna godina</label><br>
    <input type=\"text\" id=\"godmax\" name=\"godmax\" value=\"$ma\"><br>
    <label for=\"rez\">Reziser</label><br>
    <input type=\"text\" id=\"rez\" name=\"rez\" value=\"$r\"><br>
    <label for=\"glum\">Glumac</label><br>
    <input type=\"text\" id=\"glum\" name=\"glum\" value=\"$g\"><br>
    <input type=\"submit\" value=\"Potvrdi\">
  </form></br></br></br>";
}

function stampajLogin(){
    if (!isset($_SESSION["user"]))
        echo "<h3>Logovanje za admine</h3>
        <form action=\"index.php\" method=\"post\">
        <label for=\"user\">Korisnicko ime</label><br>
        <input type=\"text\" id=\"user\" name=\"user\"><br><br>
        <label for=\"pass\">Lozinka</label><br>
        <input type=\"password\" id=\"pass\" name=\"pass\"><br><br>
        <input type=\"submit\" value=\"Potvrdi\">
        </form></br></br></br>";
    
    else
        echo "<br></form><form action=\"dodaj.php\">
        <input type=\"submit\" value=\"Dodaj film\"></form><br> 
        <form action=\"index.php\" method=\"post\">
        <input hidden type=\"text\" id=\"odjavi\" name=\"odjavi\" value=\"0\">
        <input type=\"submit\" value=\"Odjavi se\">
        </form></br></br>";
}

function verifikacija($user, $pass){
    $conn = new PDO("mysql:host=localhost;dbname=bazaproj", "dragan", "markovic");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $rezultat = array();
    $sql = "SELECT * FROM korisnici";
    try {
        $rez = $conn->query($sql);
        foreach ($rez as $red)
            $rezultat[] = $red;
    }
    catch(PDOException $e){
        $e->getMessage();
    }
    $conn = null;

    foreach ($rezultat as $korisnik) {
        if ($user == $korisnik["Username"])
            if ($pass == $korisnik["Password"])
                return true;
    }
    return false;
}
?>