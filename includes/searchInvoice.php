<?php
session_start();
include "mssqlconn.php";

$searchTerm = utf8_decode($_GET["term"]);

$queryInvoice = "SELECT TOP 30 DocNum FROM OINV WHERE DocNum LIKE '%".$searchTerm."%' ORDER BY DocNum ASC";
$resultInvoice = mssql_query($queryInvoice);
while ($rowInvoice = mssql_fetch_assoc($resultInvoice)) {
	$data[] = utf8_encode($rowInvoice["DocNum"]);
}

echo json_encode($data);
?>