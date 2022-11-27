<?php

session_start();
include("settings/connectDB.php");
$sqlGrafikon = "SELECT ev, osszesen FROM `lelekszam` WHERE varosid = 185 AND ev BETWEEN 2014 AND 2019 ORDER BY ev; ";
$resultGrafikon = mysqli_query($db, $sqlGrafikon);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Varosok</title>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            <h2>Grafikon</h2>
            <h4>Kecskemét népessége grafikonon 2014-2019</h4>
            <canvas id="myChart" width="200" height="200"></canvas>
            <script>
                const ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['2014', '2015', '2016', '2017', '2018', '2019'],
                        datasets: [{
                            label: '# Mutat',
                            data: [
                                <?php
                                while ($row = $resultGrafikon->fetch_array()) {
                                    echo $row['osszesen'];
                                    if ($row['ev'] != '2019') {
                                        echo ", ";
                                    }
                                }
                                ?>
                            ],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>


        </article>
    </section>
    <footer>Ez a weboldal egy iskolai beadandó feladat keretében készült.</footer>
</body>

</html>