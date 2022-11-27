<?php

include("connectDB.php");

$sql = "SELECT varos.nev AS varosnev, varos.megyeszekhely, varos.megyeijogu, megye.nev AS megyenev, lelekszam.ev, lelekszam.no, lelekszam.osszesen FROM varos INNER JOIN megye ON megye.id = varos.megyeid INNER JOIN lelekszam ON varos.id = lelekszam.varosid
WHERE megye.nev LIKE ? AND varos.nev LIKE ? AND lelekszam.ev LIKE ?";

$strq = "%" . $_GET['q'] . "%";
$strw = "%" . $_GET['w'] . "%";
$stre = "%" . $_GET['e'] . "%";
//echo $strq . " és " . $strw . " és ". $stre;

$stmt = $db->prepare($sql);
$stmt->bind_param("sss", $strq, $strw, $stre);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($varosnev, $megyeszekhely, $megyeijogu, $megyenev, $ev, $no, $osszesen);

echo "<table>";
echo "<tbody>";

echo "<tr>";
echo "<th>Városnév</th>";
echo "<th>Megyeszékhely</th>";
echo "<th>Megyeijogú</th>";
echo "<th>Megyenév</th>";
echo "<th>Év</th>";
echo "<th>Nők száma</th>";
echo "<th>Összesen</th>";
echo "</tr>";
while ($stmt->fetch()) {
  echo "<tr>";
  echo "<td>" . $varosnev . "</td>";
  if ($megyeszekhely == 1) { echo "<td>Igen</td>"; } else { echo "<td>Nem</td>"; }
  if ($megyeijogu == 1) { echo "<td>Igen</td>"; } else { echo "<td>Nem</td>"; }
  echo "<td>" . $megyenev . "</td>";
  echo "<td>" . $ev . "</td>";
  echo "<td>" . $no . "</td>";
  echo "<td>" . $osszesen . "</td>";
  echo "</tr>";
}
echo "</tbody>";
echo "</table>";

$stmt->close();
