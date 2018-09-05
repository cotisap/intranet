<?php
session_start();
include "mssqlconn.php";
include "mysqlconn.php";

$url = $_SERVER['REQUEST_URI'];
$fromName = str_replace("/includes/prodCode.php?name=", "", $url);
$explode = explode("&_=", $fromName);
$name = str_replace("'", "''", utf8_decode(rawurldecode( $explode[0] )));

$queryCheckSAP = "SELECT COUNT(T1.ItemName) isSAP FROM OITM T1 JOIN ITM1 T2 ON T1.ItemCode = T2.ItemCode WHERE T2.pricelist = ".$_SESSION["priceList"]." AND T1.ItemName = '".$name."'";
$resultCheckSAP = mssql_query($queryCheckSAP);
$rowCheckSAP = mssql_fetch_assoc($resultCheckSAP);

if ($rowCheckSAP["isSAP"] != 0) {
	$queryCode = "SELECT T1.ItemCode FROM OITM T1 JOIN ITM1 T2 ON T1.ItemCode = T2.ItemCode WHERE T2.pricelist = ".$_SESSION["priceList"]." AND T1.ItemName = '".$name."'";
	
	$resultCode = mssql_query($queryCode);
	$rowCode = mssql_fetch_assoc($resultCode);
} else {
	$queryCode = "SELECT Codigo_Art ItemCode	 FROM ART1 WHERE Descripcion = '".$name."'";
	$resultCode = mysql_query($queryCode);
	$rowCode = mysql_fetch_assoc($resultCode);
}

$rowCode["ItemCode"] = utf8_encode($rowCode["ItemCode"]);

echo json_encode($rowCode);
?>