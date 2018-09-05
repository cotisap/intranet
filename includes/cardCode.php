<?php
session_start();
include "mssqlconn.php";
include "mysqlconn.php";

$url = $_SERVER['REQUEST_URI'];
$fromName = str_replace("/includes/cardCode.php?name=", "", $url);
$explode = explode("&_=", $fromName);
$name = str_replace("'", "''", utf8_decode(rawurldecode( $explode[0] )));
//$name = utf8_decode($_GET["name"]);

$queryCheckSAP = "SELECT COUNT(CardName) isSAP FROM OCRD WHERE CardType = 'C' AND VatStatus = 'Y' AND CardName = '$name'";

$resultCheckSAP = mssql_query($queryCheckSAP);
$rowCheckSAP = mssql_fetch_assoc($resultCheckSAP);

if ($rowCheckSAP["isSAP"] != 0) {
	$queryCode = "SELECT CardCode FROM OCRD WHERE VatStatus = 'Y' AND CardType = 'C' AND CardName = '".$name."'";
	
	$resultCode = mssql_query($queryCode);
	$rowCode = mssql_fetch_assoc($resultCode);
} else {
	$queryCode = "SELECT Id_SN CardCode FROM SONE WHERE Name_SN = '".$name."'";
	
	$resultCode = mysql_query($queryCode);
	$rowCode = mysql_fetch_assoc($resultCode);
}

$rowCode["CardCode"] = utf8_encode($rowCode["CardCode"]);

echo json_encode($rowCode);
?>