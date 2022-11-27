<?php

session_start();
include("settings/connectDB.php");


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

            <h2>PDF készítés</h2>

            <form action="pdfgenerator.php" method="POST">
                <select name="megye" id="megye">
                    <option value="">Válassz egy megyét:</option>
                    <?php
                    $sqlMegye = "SELECT nev FROM megye ORDER BY nev";
                    $resultMegye = mysqli_query($db, $sqlMegye);
                    while ($row = $resultMegye->fetch_array()) {
                        echo "<option value=$row[nev]>$row[nev]</option>";
                    }
                    ?>
                </select>
                <select name="varos" id="varos">
                    <option value="">Válassz egy várost:</option>
                    <?php
                    $sqlVaros = "SELECT nev FROM varos ORDER BY nev";
                    $resultVaros = mysqli_query($db, $sqlVaros);
                    while ($row = $resultVaros->fetch_array()) {
                        echo "<option value=$row[nev]>$row[nev]</option>";
                    }
                    ?>
                </select>
                <select name="ev" id="ev">
                    <option value="">Válassz egy évet:</option>
                    <?php
                    $sqlEv = "SELECT ev FROM lelekszam GROUP BY ev ORDER BY ev";
                    $resultEv = mysqli_query($db, $sqlEv);
                    while ($row = $resultEv->fetch_array()) {
                        echo "<option value=$row[ev]>$row[ev]</option>";
                    }
                    ?>
                </select>
                <br>
                <input type="submit" value="PDF generálás">
            </form>


        </article>
    </section>
    <footer>Ez a weboldal egy iskolai beadandó feladat keretében készült.</footer>
</body>

</html>