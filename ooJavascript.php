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

            <h2>Objektum orientált javascript</h2>

            <p id="jsText1" style="text-align: center;">Ez egy p tag.</p>
            <p id="jsText2" style="text-align: center;">Ez egy p tag.</p>

            <script>
                class szemely {
                    constructor(vnev, knev) {
                        this.vnev = vnev;
                        this.knev = knev;
                    }
                    bemutatkozas() {
                        return "Helló! Én " + this.vnev + " " + this.knev + " vagyok."
                    }
                }
                let elem1 = document.getElementById('jsText1');
                let elem2 = document.getElementById('jsText2');
                let p1 = new szemely('Git', 'Áron');
                let p2 = new szemely('Vak', 'Cina');

                elem1.innerText = p1.bemutatkozas();
                elem2.innerText = p2.bemutatkozas();

                function szinValtas() {
                    setTimeout(function() {
                        elem1.style.backgroundColor = "#a80000";
                    }, 1000);
                    setTimeout(function() {
                        elem2.style.backgroundColor = "#a80000";
                    }, 2000);
                    setTimeout(function() {
                        elem1.style.backgroundColor = "#00a800";
                    }, 3000);
                    setTimeout(function() {
                        elem2.style.backgroundColor = "#00a800";
                    }, 4000);
                    setTimeout(function() {
                        elem1.style.backgroundColor = "#0000a8";
                    }, 5000);
                    setTimeout(function() {
                        elem2.style.backgroundColor = "#0000a8";
                    }, 6000);
                }
                szinValtas();
                setInterval(szinValtas, 6000);
            </script>

        </article>
    </section>
    <footer>Ez a weboldal egy iskolai beadandó feladat keretében készült.</footer>
</body>

</html>