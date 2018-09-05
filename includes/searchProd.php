<?php
session_start();
include "mssqlconn.php";
include "mysqlconn.php";

$by = $_GET["by"];
$searchTerm = utf8_decode($_GET["term"]);

$ndash = html_entity_decode("&#x2013;", ENT_COMPAT, "UTF-8");
$queryProd = "SELECT TOP 30 T1.Item".$by." Product FROM OITM T1 JOIN ITM1 T2 ON T1.ItemCode = T2.ItemCode WHERE T2.pricelist = ".$_SESSION["priceList"]." AND T1.validFor = 'Y' AND T1.frozenFor = 'N' AND T1.Item".$by." LIKE '%".$searchTerm."%' ORDER BY T1.Item".$by." ASC";
$resultProd = mssql_query($queryProd);
while ($rowProd = mssql_fetch_assoc($resultProd)) {
	$data[] = utf8_encode($rowProd['Product']);
}

if ($by == "code") {
	$queryNoSAP = "SELECT Codigo_Art Product ";
} elseif ($by == "name") {
	$queryNoSAP = "SELECT Descripcion Product ";
}
$queryNoSAP.= "FROM ART1 WHERE st='1' && ";
if ($by == "code") {
	$queryNoSAP.= "Codigo_Art LIKE '%".$searchTerm."%'";
} elseif ($by == "name") {
	$queryNoSAP.= "Descripcion LIKE '%".$searchTerm."%'";
}
$queryNoSAP.= " LIMIT 10";
$resultNoSAP = mysql_query($queryNoSAP);
while ($rowNoSAP = mysql_fetch_assoc($resultNoSAP)) {
	$data[] = utf8_encode($rowNoSAP["Product"]);
}

echo json_encode($data);
?>