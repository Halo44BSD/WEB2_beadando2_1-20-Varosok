<?php

session_start();
include("settings/connectDB.php");

$url = "http://localhost/restfulSzerver.php";
$result = "";
if (isset($_POST['id'])) {
    // Felesleges szóközök eldobása
    $_POST['id'] = trim($_POST['id']);
    $_POST['nev'] = trim($_POST['nev']);
    $_POST['megyeid'] = trim($_POST['megyeid']);
    $_POST['megyeszekhely'] = trim($_POST['megyeszekhely']);
    $_POST['megyeijogu'] = trim($_POST['megyeijogu']);

    // Ha nincs id és megadtak minden adatot (név, megyeid, megyeszékhely, megyeijogú), akkor beszúrás
    if ($_POST['id'] == "" && $_POST['nev'] != "" && $_POST['megyeid'] != "" && $_POST['megyeszekhely'] != "" && $_POST['megyeijogu'] != "") {
        $data = array("nev" => $_POST["nev"], "megyeid" => $_POST["megyeid"], "megyeszekhely" => $_POST["megyeszekhely"], "megyeijogu" => $_POST["megyeijogu"]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    // Ha nincs id de nem adtak meg minden adatot
    elseif ($_POST['id'] == "") {
        $result = "Hiba: Hiányos adatok!";
    }

    // Ha van id, amely >= 1, és megadták legalább az egyik adatot (név, megyeid, megyeszékhely, megyeijogú), akkor módosítás
    elseif ($_POST['id'] >= 1 && ($_POST['nev'] != "" || $_POST['megyeid'] != "" || $_POST['megyeszekhely'] != "" || $_POST['megyeijogu'] != "")) {
        $data = array("id" => $_POST["id"], "nev" => $_POST["nev"], "megyeid" => $_POST["megyeid"], "megyeszekhely" => $_POST["megyeszekhely"], "megyeijogu" => $_POST["megyeijogu"]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    // Ha van id, amely >=1, de nem adtak meg legalább az egyik adatot
    elseif ($_POST['id'] >= 1) {
        $data = array("id" => $_POST["id"]);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    // Ha van id, de rossz az id, akkor a hiba kiírása
    else {
        echo "Hiba: Rossz azonosító (Id): " . $_POST['id'] . "<br>";
    }
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$tabla = curl_exec($ch);
curl_close($ch);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Varosok</title>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["admin"]) && $_SESSION["admin"] === false) {
        echo '<p class="bejelentkezve">Bejelentkezett: ' . $_SESSION['login_name'] . ' (' . $_SESSION['login_logName'] . ') - felhasználó </p>';
    }
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
        echo '<p class="bejelentkezve">Bejelentkezett: ' . $_SESSION['login_name'] . ' (' . $_SESSION['login_logName'] . ') - admin </p>';
    }
    ?>
    <header>
        <h1>Városok</h1>
        <p>Magyarország városai</p>
    </header>
    <nav>
        <?php
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
            $sqlMenu = "SELECT * FROM menu WHERE szerepkor = 'vendeg'";
            $resultMenu = mysqli_query($db, $sqlMenu);
            while ($row = $resultMenu->fetch_array()) {
                echo "<a href=$row[href]>$row[nev]</a>";
            }
        }
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["admin"]) && $_SESSION["admin"] === false) {
            $sqlMenu = "SELECT * FROM menu WHERE szerepkor = 'felhasznalo'";
            $resultMenu = mysqli_query($db, $sqlMenu);
            while ($row = $resultMenu->fetch_array()) {
                echo "<a href=$row[href]>$row[nev]</a>";
            }
        }
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
            $sqlMenu = "SELECT * FROM menu WHERE szerepkor = 'felhasznalo' OR szerepkor = 'admin'";
            $resultMenu = mysqli_query($db, $sqlMenu);
            while ($row = $resultMenu->fetch_array()) {
                echo "<a href=$row[href]>$row[nev]</a>";
            }
        }
        ?>
    </nav>
    <section>
        <article>


            <h1>Restful</h1>

            <h2>Varosok</h2>
            
            <h3>Módosítás / Beszúrás</h3>
            <form method="post">
                Id:<br><input type="text" name="id"><br>
                Név:<br><input type="text" name="nev" maxlength="45"><br>
                MegyeId:<br><input type="text" name="megyeid"><br>
                Megyeszékhely:<br><input type="text" name="megyeszekhely"><br>
                Megyeijogú:<br><input type="text" name="megyeijogu"><br><br>
                <input type="submit" value="Küldés">
            </form>
            <?php echo "Result: ".$result."<br>"; ?>
            <?php echo "Tábla: ".$tabla."<br>"; ?>


        </article>
    </section>
    <footer>Ez a weboldal egy iskolai beadandó feladat keretében készült.</footer>
</body>

</html>