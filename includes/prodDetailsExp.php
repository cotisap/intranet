<?php
session_start();
include "mssqlconn.php";
include "mysqlconn.php";

$whCodes = array();
$whNames = array();
$queryWH = "SELECT WhsCode, LEFT(WhsName, 3) AS WhsName FROM OWHS WHERE Inactive <> 'Y' AND U_usable = 'Y' ORDER BY WhsCode ASC";
$resultWH = mssql_query($queryWH);
while ($rowWH = mssql_fetch_assoc($resultWH)) {
	$whCodes[] = $rowWH["WhsCode"];
	$whNames[] = $rowWH["WhsName"];
}

$curRates = array();
$queryCurRates = "SELECT Currency, Rate FROM ORTT WHERE RateDate = CONVERT(date, getdate()) AND Currency = 'USD'";
$resultCurRates = mssql_query($queryCurRates);
$rowCurRates = mssql_fetch_assoc($resultCurRates);

$url = $_SERVER['REQUEST_URI'];
$fromCode = str_replace("/includes/prodDetailsExp.php?code=", "", $url);
$explode = explode("&_=", $fromCode);
$code = utf8_decode(rawurldecode( $explode[0] ));


$queryCheckSAP = "SELECT COUNT(T1.ItemCode) isSAP FROM OITM T1 JOIN ITM1 T2 ON T1.ItemCode = T2.ItemCode WHERE T1.ItemCode = '$code' AND T2.pricelist = ".$_SESSION["priceList"]."";
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
	$queryProd.= "Cast(CONVERT(DECIMAL(10,2), OnHand) - 0 as nvarchar) AS OnHand FROM OITM T1 INNER JOIN ITM1 T2 ON T1.itemcode = T2.itemcode WHERE T2.pricelist = ".$_SESSION["priceList"]." AND T1.ItemCode = '".$code."'";
	
	$resultProd = mssql_query($queryProd);
	$rowProd = mssql_fetch_assoc($resultProd);
	
	if ($rowProd["Currency"] == 'MN' || $rowProd["Currency"] == 'MXN' || $rowProd["Currency"] == 'MXP' || $rowProd["Currency"] == '') {
		$rowProd["Currency"] = 'USD';
		$rowProd["Price"] = number_format($rowProd["Price"] / $rowCurRates["Rate"], 2);
	}
	
	foreach ($whCodes as $whCode) {
		$querySD = "SELECT CONVERT(decimal(10,2), SUM(OpenQty)) Quantity, CONVERT(VARCHAR(10),ShipDate,103) ShipDate FROM POR1 WHERE ItemCode = '".$code."' AND LineStatus = 'O' AND WhsCode = '".$whCode."' GROUP BY ShipDate"; 
		$resultSD = mssql_query($querySD);
		$rowProd["qo".$whCode] = "<table>";
		while($rowSD = mssql_fetch_assoc($resultSD)) {
			$rowProd["qo".$whCode].= "<tr><td>".$rowSD["Quantity"]."</td><td>".$rowSD["ShipDate"]."</td></tr>";
		}
		$rowProd["qo".$whCode].= "</table>";
	}
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