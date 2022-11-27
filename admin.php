<?php

session_start();
include("settings/connectDB.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $sqlUsers = "SELECT * FROM users WHERE login = '$_POST[admin]'";
  $resultUsers = mysqli_query($db, $sqlUsers);
  $row = $resultUsers->fetch_array();
  $sql = "UPDATE users SET szerep = 'admin' WHERE login = '$_POST[admin]'";

  if ($db->query($sql) === TRUE) {
    $siker = "$_POST[admin] felhasználó mostmár admin!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    exit();
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

      <h2>Admin</h2>
      <?php
      if (isset($siker)) {
        echo '<label style="color:greenyellow">' . $siker . '</label><br>';
      }
      ?>
      <form action="admin.php" method="POST">
        <label>Adminná tétel:</label><br><br>
        <select name="admin" id="admin" style="max-width: 250px;">
          <option value="">Válassz egy felhasználót:</option>
          <?php
          $sqlUsers = "SELECT name, login FROM users WHERE szerep <> 'admin' ORDER BY name";
          $resultUsers = mysqli_query($db, $sqlUsers);
          while ($row = $resultUsers->fetch_array()) {
            echo "<option value=$row[login]>$row[name] ($row[login])</option>";
          }
          ?>
        </select><br><br>
        <input type="submit" value="Adminná tétel">
      </form>

    </article>
  </section>
  <footer>Ez a weboldal egy iskolai beadandó feladat keretében készült.</footer>
</body>

</html>