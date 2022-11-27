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
      while($row = $resultMenu->fetch_array()){
        echo "<a href=$row[href]>$row[nev]</a>";
      }
    }
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["admin"]) && $_SESSION["admin"] === false) {
      $sqlMenu = "SELECT * FROM menu WHERE szerepkor = 'felhasznalo'";
      $resultMenu = mysqli_query($db, $sqlMenu);
      while($row = $resultMenu->fetch_array()){
        echo "<a href=$row[href]>$row[nev]</a>";
      }
    }
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
      $sqlMenu = "SELECT * FROM menu WHERE szerepkor = 'felhasznalo' OR szerepkor = 'admin'";
      $resultMenu = mysqli_query($db, $sqlMenu);
      while($row = $resultMenu->fetch_array()){
        echo "<a href=$row[href]>$row[nev]</a>";
      }
    }
    ?>
  </nav>
  <section>
    <article>

      <h2>Városok</h2>
      <p>
        A város olyan település, amelynek valamilyen (kulturális, ipari, kereskedelmi stb.) jelentőségénél fogva különleges, törvény szerint meghatározott jogállása van. A város élén ma általában a polgármester áll.<br>
        A város az emberi településformák legmagasabb szerveződési szintje. Az ember számára a város az egyetlen olyan élőhelytípus, ahol nagy egyedszámban és sűrűségben, lényegében természetesen van jelen. A város ezért a legtöbb diszciplína, például a történelemtudomány, szociológia, orvostudomány, a mérnöki tudományok (például várostervezés/urbanisztika) a közgazdaságtan és a természettudományok művelői számára egyaránt jelentős kutatási terepnek számít.<br>
        A város (vagyis „urbanizált terület”) egy széles körben értelmezhető kifejezés, amely földrajzi értelemben egy régió területhasznosítására vonatkozik. Olyan hely, ami „meglehetősen nagy”, sűrűn lakott, és ipari, kereskedelmi és lakóövezetekkel jellemezhető.<br>
      </p>
      <br>
      <img src="images/varos.jpg" alt="varos kep">
      <br><br>
      <h2>Történelem</h2>
      <p>
        Az első állandó emberi települések és a letelepedett életmód a sikeres mezőgazdasági technikák egyenes következményei voltak. Körülbelül 9000 évvel ezelőttre tehető a kor, amikor a mind hatékonyabbá váló földművelési módszerekkel – az emberiség történetében először – tartósan táplálékfelesleg keletkezett, ami lehetővé tette más munkaformák, más tevékenységek fejlődését is. Az állandó települések sikere abban állt, hogy bennük egyszerre több, egymást kiegészítő tevékenység koncentrálódott, és ez a fejlődő kézműipar, a kereskedelem fokozódását is lehetővé tette. Nem mellékes, hogy városok fejlődésén át vezetett a kereskedelmi útvonalak, így a kereskedő városok hálózatának kialakulása is.<br>
        A városok és a falvak közti különbség azonban nem pusztán azok méretében vagy az ott lakók számában rejlik. A funkció az, ami megkülönbözteti a két fő településformát – vagyis a jellemzően agrár és az ipari-kereskedelmi településeket – egymástól.[2]<br>
        A huszadik században lejátszódó urbanizációs robbanás, a városi lakosság ugrásszerű növekedése feltételezi, hogy rendelkezésre áll az a táplálékfelesleg, ami a termelők igényein túl a táplálékot nem termelők (t.i. városi lakosság) szükségleteit is kielégíti. A 80 százalékosan urbanizálódott USA-ban a fejlett technológiák alkalmazása lehetővé teszi, hogy egy mezőgazdasági termelő átlagosan 125 másik embernek elegendő táplálékot termeljen. A világ egy másik jelentősen urbanizálódott országában, Hollandiában a mezőgazdasági termeléssel a lakosságnak elenyésző hányada (2%) foglalkozik úgy, hogy az nem a termelés visszafogását jelenti.<br>
        A történelem során városok lettek naggyá, majd tűntek el, miközben máshol más városok váltak meghatározóvá. E jelenséget már a görög történetíró, Hérodotosz is megemlíti Kr.e. 440 körül írt könyvében:[3]<br>
        „A legtöbb város mostanára kicsi, amely egykoron nagy volt; és azok, amelyek kicsik voltak, még életemben naggyá lettek. Tudván ezek alapján, hogy az emberi jólét soha nem virágozhat sokáig egy helyen, ezeket a jelenségeket mindenképpen figyelemre méltónak kell tartanom.” <br>
      </p>

    </article>
  </section>
  <footer>Ez a weboldal egy iskolai beadandó feladat keretében készült.</footer>
</body>

</html>