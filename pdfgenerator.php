<?php

include("settings/connectDB.php");

include('./TCPDF-php8-main/tcpdf.php');
$pdf = new TCPDF('p', 'mm', 'A4');
//$pdf = new TCPDF('p', 'mm', 'A4', true, 'utf-8', false);
//$pdf->SetFont('helvetica', '', 9, '', true);
$pdf->SetFont('freeserif', '', 9);
$pdf->AddPage();
$str = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $megye = "%" . $_POST['megye'] . "%";
    $varos = "%" . $_POST['varos'] . "%";
    $ev = "%" . $_POST['ev'] . "%";
    $sql = "SELECT varos.nev AS varosnev, varos.megyeszekhely, varos.megyeijogu, megye.nev AS megyenev, lelekszam.ev, lelekszam.no, lelekszam.osszesen FROM varos INNER JOIN megye ON megye.id = varos.megyeid INNER JOIN lelekszam ON varos.id = lelekszam.varosid
    WHERE megye.nev LIKE '$megye' AND varos.nev LIKE '$varos' AND lelekszam.ev LIKE '$ev'";
    $result = mysqli_query($db, $sql);

    
    $str .= "<h1>PDF generálás eredménye</h1><br>";
    $str .= "<table>";
    $str .= "<tbody>";

    $str .= "<tr>";
    $str .= "<th>Varosnév</th>";
    $str .= "<th>Megyeszékhely</th>";
    $str .= "<th>Megyeijogú</th>";
    $str .= "<th>Megyenév</th>";
    $str .= "<th>Év</th>";
    $str .= "<th>Nők száma</th>";
    $str .= "<th>Összesen</th>";
    $str .= "</tr>";

    while ($row = $result->fetch_array()) {
        $str .= "<tr>";
        $str .= "<td>" . $row['varosnev'] . "</td>";
        if ($row['megyeszekhely'] == 1) {
            $str .= "<td>Igen</td>";
        } else {
            $str .= "<td>Nem</td>";
        }
        if ($row['megyeijogu'] == 1) {
            $str .= "<td>Igen</td>";
        } else {
            $str .= "<td>Nem</td>";
        }
        $str .= "<td>" . $row['megyenev'] . "</td>";
        $str .= "<td>" . $row['ev'] . "</td>";
        $str .= "<td>" . $row['no'] . "</td>";
        $str .= "<td>" . $row['osszesen'] . "</td>";
        $str .= "</tr>";
    }

    $str .= "</tbody>";
    $str .= "</table>";
    
}

//echo $str;
//echo mb_detect_encoding($str);

$pdf->writeHTMLCell(190, 0, '', '', $str, 1, 1);
$pdf->Output();
