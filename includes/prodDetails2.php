<?php
session_start();
include "mssqlconn.php";
include "mysqlconn.php";

$whCodes = array();
$whNames = array();
$queryWH = "SELECT WhsCode, LEFT(WhsName, 3) AS WhsName FROM OWHS WHERE Inactive <> 'Y' ORDER BY WhsCode ASC";
$resultWH = mssql_query($queryWH);
while ($rowWH = mssql_fetch_assoc($resultWH)) {
	$whCodes[] = $rowWH["WhsCode"];
	$whNames[] = $rowWH["WhsName"];
}

$code = utf8_decode(rawurldecode(str_replace("/includes/prodDetails2.php?code=", "", $_SERVER['REQUEST_URI'])));

$queryCheckSAP = "SELECT COUNT(ItemCode) isSAP FROM OITM WHERE ItemCode = '$code'";
$resultCheckSAP = mssql_query($queryCheckSAP);
$rowCheckSAP = mssql_fetch_assoc($resultCheckSAP);

if($rowCheckSAP["isSAP"] != 0) {
	$queryProd = "SELECT T1.ItemCode, T1.ItemName, T1.SalUnitMsr, T2.Price, T2.Currency, ";
	//OnHand & OnOrder by Warehouse
	foreach ($whCodes as $whCode) {
		$queryProd.= "(SELECT T3.OnHand FROM OITW T3 WHERE T3.ItemCode = T1.ItemCode AND T3.WhsCode = '".$whCode."') AS e".$whCode.", ";
	}
	foreach ($whCodes as $whCode) {
		$queryProd.= "(SELECT T3.OnOrder FROM OITW T3 WHERE T3.ItemCode = T1.ItemCode AND T3.WhsCode = '".$whCode."') AS p".$whCode.", ";
	}
	$queryProd.= "Cast(CONVERT(DECIMAL(10,2),OnHand) as nvarchar) AS OnHand FROM OITM T1 INNER JOIN ITM1 T2 ON T1.itemcode = T2.itemcode WHERE T2.pricelist = ";
	switch ($_SESSION['company']) {
		case "alianza":// Centro
			$queryProd.= "7";
			break;
		case "sureste":// Puebla
			$queryProd.= "1";
			break;
		case "pacifico":// Pacífico
			$queryProd.= "1";
			break;
		case "fg":// FG Electrical
			$queryProd.= "3";
			break;
	}
	$queryProd.= " AND T1.ItemCode = '".$code."'";
	
	$resultProd = mssql_query($queryProd);
	$rowProd = mssql_fetch_assoc($resultProd);
} else {
	$queryProd = "SELECT Codigo_Art ItemCode, Descripcion ItemName, UMV SalUnitMsr, precio Price, moneda Currency FROM ART1 WHERE Codigo_Art = '$code'";
	$resultProd = mysql_query($queryProd);
	$rowProd = mysql_fetch_assoc($resultProd);
}

if(count($rowProd) != 0) {
	$rowProd["ItemCode"] = utf8_encode($rowProd["ItemCode"]);
	$rowProd["ItemName"] = utf8_encode($rowProd["ItemName"]);
	
	echo json_encode($rowProd);
}
?>