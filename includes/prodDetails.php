<?php
session_start();
include "mssqlconn.php";
include "mysqlconn.php";


$sqlAvaile = "SELECT TOP 1 Locked FROM OITW WHERE itemCode = '" . $_GET['code'] . "' and WhsCode = '". $_SESSION["branch"] . "'";
$sqlAvaileR = mssql_query($sqlAvaile);
$rowProdR = mssql_fetch_assoc($sqlAvaileR);

if (!strcmp($rowProdR["Locked"], "Y") ){

	echo json_encode(true);

	exit;

}


$whCodes = array();
$whNames = array();
$queryWH = "SELECT WhsCode, LEFT(WhsName, 3) AS WhsName FROM OWHS WHERE Inactive <> 'Y' AND U_usable = 'Y' ORDER BY WhsCode ASC";
$resultWH = mssql_query($queryWH);
while ($rowWH = mssql_fetch_assoc($resultWH)) {
	$whCodes[] = $rowWH["WhsCode"];
	$whNames[] = $rowWH["WhsName"];
}

$queryMN = "SELECT TOP 1 CurrCode FROM OCRN WHERE ISOCurrCod = 'MXN'";
$resultMN = mssql_query($queryMN);
$rowMN = mssql_fetch_assoc($resultMN);
$sysMN = $rowMN["CurrCode"];

$url = $_SERVER['REQUEST_URI'];
$fromCode = str_replace("/includes/prodDetails.php?code=", "", $url);
$explode = explode("&_=", $fromCode);

$explode2 = explode("&itemcode=", $explode[0]);

$code = utf8_decode(rawurldecode( $explode2[0] ));


$queryClient = "SELECT ListNum FROM OCRD  WHERE CardCode = '" . $explode2[1] . "'";
$resultQueryClient = mssql_query($queryClient);
$rowQueryClient = mssql_fetch_assoc($resultQueryClient);


$queryCheckSAP = "SELECT COUNT(T1.ItemCode) isSAP FROM OITM T1 JOIN ITM1 T2 ON T1.ItemCode = T2.ItemCode WHERE T1.ItemCode = '$code' AND T2.pricelist = ".$_SESSION["priceList"]."";


$resultCheckSAP = mssql_query($queryCheckSAP);
$rowCheckSAP = mssql_fetch_assoc($resultCheckSAP);

if($rowCheckSAP["isSAP"] != 0) {
	$queryProd = "SELECT T1.ItemCode, T1.ItemName, T1.SalUnitMsr, T2.Price, T1.LastPurPrc, CASE T1.LastPurCur WHEN '".$sysMN."' THEN 'MXN' ELSE T1.LastPurCur END AS LastPurCur, CASE T2.Currency WHEN '".$sysMN."' THEN 'MXN' ELSE T2.Currency END AS Currency, T4.FirmName, ";
	//OnHand & OnOrder by Warehouse
	foreach ($whCodes as $whCode) {
		$queryProd.= "(SELECT CASE T3.Locked WHEN 'Y' THEN -1 ELSE T3.OnHand END FROM OITW T3 WHERE T3.ItemCode = T1.ItemCode AND T3.WhsCode = '".$whCode."') AS e".$whCode.", ";
	}
	foreach ($whCodes as $whCode) {
		$queryProd.= "(SELECT CASE T3.Locked WHEN 'Y' THEN -1 ELSE T3.OnOrder END FROM OITW T3 WHERE T3.ItemCode = T1.ItemCode AND T3.WhsCode = '".$whCode."') AS p".$whCode.", ";
	}

	if($rowQueryClient){
		$queryProd.= "Cast(CONVERT(DECIMAL(10,2), T1.OnHand) - 0 as nvarchar) AS OnHand, Cast(CONVERT(DECIMAL(10,2), T1.OnOrder) - 0 as nvarchar) AS OnOrder, isSAP = 'Y' FROM OITM T1 JOIN ITM1 T2 ON T1.itemcode = T2.itemcode JOIN OMRC T4 ON T1.FirmCode = T4.FirmCode WHERE T2.pricelist = ".$rowQueryClient["ListNum"]." AND T1.ItemCode = '".$code."'";
	} else {
		$queryProd.= "Cast(CONVERT(DECIMAL(10,2), T1.OnHand) - 0 as nvarchar) AS OnHand, Cast(CONVERT(DECIMAL(10,2), T1.OnOrder) - 0 as nvarchar) AS OnOrder, isSAP = 'Y' FROM OITM T1 JOIN ITM1 T2 ON T1.itemcode = T2.itemcode JOIN OMRC T4 ON T1.FirmCode = T4.FirmCode WHERE T2.pricelist = ".$_SESSION["priceList"]." AND T1.ItemCode = '".$code."'";
	}
	
	//echo $queryProd;
	$resultProd = mssql_query($queryProd);
	$rowProd = mssql_fetch_assoc($resultProd);
	
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
	$queryProd = "SELECT Codigo_Art ItemCode, Descripcion ItemName, UMV SalUnitMsr, precio Price, moneda Currency, isSAP FROM ART1 WHERE Codigo_Art = '$code'";
	$resultProd = mysql_query($queryProd);
	$rowProd = mysql_fetch_assoc($resultProd);
}

if(count($rowProd) != 0) {
	$rowProd["ItemCode"] = utf8_encode($rowProd["ItemCode"]);
	$rowProd["ItemName"] = utf8_encode($rowProd["ItemName"]);
	$rowProd["FirmName"] = utf8_encode($rowProd["FirmName"]);
	
	//echo $queryProd;


	//Lotes
	/*$lotes_query = "SELECT SUM(T1.Qua) AS Cantidad, T1.BatchNum, T1.WhsCode, T1.ItemCode FROM (SELECT Quantity,ItemCode, BatchNum, WhsCode,LineNum, CASE LineNum WHEN 1  THEN Quantity * -1 ELSE Quantity END as Qua FROM IBT1 WHERE ItemCode LIKE '%$code%') T1 GROUP BY T1.BatchNum, T1.WhsCode, T1.ItemCode;";
	$rs = mssql_query($lotes_query);

	$rowProd["BatchLength"] = mssql_num_rows($rs);
	$rowProd["Batch"] = [];
	$rowProd["BatchHTML"] = "";

	while($rowBatch = mssql_fetch_assoc($rs)) {
		$rowProd["Batch"] []= [
			'Cantidad' => utf8_encode($rowBatch['Cantidad']),
			'BatchNum' => utf8_encode($rowBatch['BatchNum']),
			'WhsCode' => utf8_encode($rowBatch['WhsCode'])
		];

		$rowProd["BatchHTML"] .= "<tr><td>".utf8_encode($rowBatch['BatchNum'])."</td><td>".utf8_encode($rowBatch['WhsCode'])."</td><td>".utf8_encode($rowBatch['Cantidad'])."</td></tr>";
	}*/


	echo json_encode($rowProd);
}
?>