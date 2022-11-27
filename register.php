<?php

session_start();
include("settings/connectDB.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myuser = mysqli_real_escape_string($db, $_POST['logName']);
    $myname = mysqli_real_escape_string($db, $_POST['fullName']);
    $mypwd1 = mysqli_real_escape_string($db, $_POST['password1']);
    $mypwd2 = mysqli_real_escape_string($db, $_POST['password2']);

    // Ha üresen maradt mező
    if ((!isset($_POST['logName'], $_POST['fullName'], $_POST['password1'], $_POST['password2'])) || (empty($_POST['logName']) || empty($_POST['fullName']) || empty($_POST['password1']) || empty($_POST['password2']))) {
        $error1 = 'Kérem adon meg minden adatot!';
    }
    if (!isset($error1) && $mypwd1 != $mypwd2) {
        $error2 = 'A két jelszó nem egyezik!';
    }
    if (!isset($error1) && !isset($error2)) {
        // Ha létezik már a felhasználó
        $sql = "SELECT login FROM users WHERE login = '$myuser'";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            $error3 = "Már létezik ilyen felhasználó!";
        }
    }
    // Ha minden jó, rögzítjük a usert
    if (!isset($error1) && !isset($error2) && !isset($error3)) {
        $sql = "INSERT INTO users (name, login, pwd, szerep) VALUES ('$myname', '$myuser', '$mypwd1', 'felhasznalo')";

        if ($db->query($sql) === TRUE) {
            $siker = "Felhasználó sikeresen létrehozva!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            exit();
        }
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

            <h2>Regisztráció</h2>

            <form action="/register.php" method="post">
                <?php
                if (isset($error1)) {
                    echo '<label style="color:red">' . $error1 . '</label><br>';
                }
                if (isset($error2)) {
                    echo '<label style="color:red">' . $error2 . '</label><br>';
                }
                if (isset($error3)) {
                    echo '<label style="color:red">' . $error3 . '</label><br>';
                }
                if (isset($siker)) {
                    echo '<label style="color:greenyellow">' . $siker . '</label><br>';
                }
                ?>
                <label for="logName">Felhasználónév:</label><br>
                <input type="text" id="logName" name="logName"><br>
                <label for="fullName">Teljes név:</label><br>
                <input type="text" id="fullName" name="fullName"><br>
                <label for="pwd1">Jelszó:</label><br>
                <input type="password" id="password1" name="password1"><br>
                <label for="pwd2">Jelszó mégegyszer:</label><br>
                <input type="password" id="password2" name="password2"><br>
                <input type="submit" value="Regisztráció">

            </form>


        </article>
    </section>
    <footer>Ez a weboldal egy iskolai beadandó feladat keretében készült.</footer>
</body>

</html>