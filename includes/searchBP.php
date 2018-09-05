<?php
session_start();
include "mssqlconn.php";
include "mysqlconn.php";

$by = $_GET["by"];
$searchTerm = utf8_decode($_GET["term"]);

// SAP BP

$queryBP = "SELECT TOP 30 Card".$by." customer FROM OCRD  WHERE CardType = 'C' AND Card".$by." LIKE '%".$searchTerm."%' ORDER BY Card".$by." ASC";
$resultBP = mssql_query($queryBP);
while ($rowBP = mssql_fetch_assoc($resultBP)) {
	$data[] = utf8_encode($rowBP['customer']);
}

// No SAP BP
if ($by == "code") {
	$queryBPNoSAP = "SELECT Id_SN customer FROM SONE WHERE Id_SN LIKE '%".$searchTerm."%' ORDER BY Id_SN LIMIT 30";
} elseif ($by == "name") {
	$queryBPNoSAP = "SELECT Name_SN customer FROM SONE WHERE Name_SN LIKE '%".$searchTerm."%' LIMIT 30";
}
$resultBPNoSAP = mysql_query($queryBPNoSAP);
while ($rowBPNoSAP = mysql_fetch_assoc($resultBPNoSAP)) {
	$data[] = utf8_encode($rowBPNoSAP["customer"]);
}

echo json_encode($data);
?>