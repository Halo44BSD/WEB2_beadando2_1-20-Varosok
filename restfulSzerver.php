<?php

$eredmeny = "";
try {
	$dbh = new PDO('mysql:host=localhost;dbname=varosokdb', 'root', '',
				  array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	$dbh->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');
	switch($_SERVER['REQUEST_METHOD']) {
		case "GET":
				$sql = "SELECT * FROM varos";     
				$sth = $dbh->query($sql);
				$eredmeny .= "<table><tr><th>Id</th><th>Név</th><th>MegyeId</th><th>Megyeszékhely</th><th>Megyeijogú</th></tr>";
				while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$eredmeny .= "<tr>";
					foreach($row as $column)
						$eredmeny .= "<td>".$column."</td>";
					$eredmeny .= "</tr>";
				}
				$eredmeny .= "</table>";
			break;
		case "POST":
				$sql = "INSERT INTO varos values (0, :nev, :megyeid, :megyeszekhely, :megyeijogu)";
				$sth = $dbh->prepare($sql);
				$count = $sth->execute(Array(":nev"=>$_POST["nev"], ":megyeid"=>$_POST["megyeid"], ":megyeszekhely"=>$_POST["megyeszekhely"], ":megyeijogu"=>$_POST["megyeijogu"]));
				$newid = $dbh->lastInsertId();
				$eredmeny .= $count." beszúrt sor: ".$newid;
			break;
		case "PUT":
				$data = array();
				$incoming = file_get_contents("php://input");
				parse_str($incoming, $data);
				$modositando = "id=id"; $params = Array(":id"=>$data["id"]);
				if($data['nev'] != "") {$modositando .= ", nev = :nev"; $params[":nev"] = $data["nev"];}
				if($data['megyeid'] != "") {$modositando .= ", megyeid = :megyeid"; $params[":megyeid"] = $data["megyeid"];}
				if($data['megyeszekhely'] != "") {$modositando .= ", megyeszekhely = :megyeszekhely"; $params[":megyeszekhely"] = $data["megyeszekhely"];}
				if($data['megyeijogu'] != "") {$modositando .= ", megyeijogu = :megyeijogu"; $params[":megyeijogu"] = $data["megyeijogu"];}
				$sql = "UPDATE varos SET ".$modositando." WHERE id=:id";
				$sth = $dbh->prepare($sql);
				$count = $sth->execute($params);
				$eredmeny .= $count." módositott sor. Azonosítója:".$data["id"];
			break;
		case "DELETE":
				$data = array();
				$incoming = file_get_contents("php://input");
				parse_str($incoming, $data);
				$sql = "DELETE FROM varos WHERE id=:id";
				$sth = $dbh->prepare($sql);
				$count = $sth->execute(Array(":id" => $data["id"]));
				$eredmeny .= $count." sor törölve. Azonosítója:".$data["id"];
			break;
	}
}
catch (PDOException $e) {
	$eredmeny = $e->getMessage();
}
echo $eredmeny;

?>