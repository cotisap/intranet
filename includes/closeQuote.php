<?php
session_start();

include "mssqlconn.php";
include "mysqlconn.php";

$iDocNum = $_REQUEST["iDocNum"];

$queryiQuote = "SELECT Codigo_SN FROM COTI WHERE Id_Cot1 = '$iDocNum'";
$resultiQuote = mysql_query($queryiQuote);
$rowiQuote = mysql_fetch_assoc($resultiQuote);

$bp = $rowiQuote["Codigo_SN"];

$querysQuote = "SELECT MAX(DocNum) DocNum FROM OQUT WHERE NumAtCard = '$iDocNum' AND CardCode = '$bp'";
$resultsQuote = mssql_query($querysQuote);
$rowsQuote = mssql_fetch_assoc($resultsQuote);

$DocNum = $rowsQuote["DocNum"];

$queryClose = "UPDATE COTI SET status = 'S', DocNum = '$DocNum' WHERE Id_Cot1 = '$iDocNum'";
$resultClose = mysql_query($queryClose);
if(!$resultClose) {
	die('Could not Close Quote: ' . mysql_error());
} else {
	header("Location: /vercotizacion.php?idCot=".$iDocNum."&msg=sapcreated");
}
?>