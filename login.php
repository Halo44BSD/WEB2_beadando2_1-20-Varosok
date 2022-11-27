<?php

session_start();
include("settings/connectDB.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myuser = mysqli_real_escape_string($db, $_POST['logName']);
    $mypwd = mysqli_real_escape_string($db, $_POST['password']);

    $sql = "SELECT * FROM users WHERE login = '$myuser' and pwd = '$mypwd'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        $_SESSION['login_logName'] = $row['loginName'];
        $_SESSION['login_name'] = $row['name'];
        $_SESSION['loggedin'] = true;
        if ($row['szerep'] == 'admin') {
            $_SESSION['admin'] = true;
        } else {
            $_SESSION['admin'] = false;
        }
        header("location: index.php");
    } else {
        $error = "Hibás felhasználónév vagy jelszó!";
    }
}

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

            <h2>Bejelentkezés</h2>

            <form action="/login.php" method="post">
                <?php
                if (isset($error)) {
                    echo '<label style="color:red">' . $error . '</label><br>';
                }
                ?>
                <label for="logName">Felhasználónév:</label><br>
                <input type="text" id="logName" name="logName"><br>
                <label for="pwd">Jelszó:</label><br>
                <input type="password" id="password" name="password"><br>
                <input type="submit" value="Belépés">

            </form>

            <a href="register.php">Regisztráció</a>

        </article>
    </section>
    <footer>Ez a weboldal egy iskolai beadandó feladat keretében készült.</footer>
</body>

</html>