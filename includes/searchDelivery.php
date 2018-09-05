<?php
session_start();
include "mssqlconn.php";

$searchTerm = utf8_decode($_GET["term"]);

$queryDeliv = "SELECT TOP 30 DocNum FROM ODLN WHERE DocNum LIKE '%".$searchTerm."%' ORDER BY DocNum ASC";
$resultDeliv = mssql_query($queryDeliv);
while ($rowDeliv = mssql_fetch_assoc($resultDeliv)) {
	$data[] = utf8_encode($rowDeliv["DocNum"]);
}

echo json_encode($data);
?>