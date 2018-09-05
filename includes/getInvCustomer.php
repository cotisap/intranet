<?php
session_start();
include "mssqlconn.php";

$invCode = $_GET["invCode"];

$queryInv = "SELECT CardName FROM OINV WHERE DocNum = '$invCode'";
$resultInv = mssql_query($queryInv);
$rowInv = mssql_fetch_assoc($resultInv);

if(count($rowInv) != 0) {
	foreach($rowInv as $value) {
		$value = utf8_encode($value);
	}
	
	echo json_encode($rowInv);
}
?>
