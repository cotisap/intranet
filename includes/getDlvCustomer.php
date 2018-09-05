<?php
session_start();
include "mssqlconn.php";

$dlvCode = $_GET["dlvCode"];

$queryDlv = "SELECT CardName FROM ODLN WHERE DocNum = '$dlvCode'";
$resultDlv = mssql_query($queryDlv);
$rowDlv = mssql_fetch_assoc($resultDlv);

if(count($rowDlv) != 0) {
	foreach($rowDlv as $value) {
		$value = utf8_encode($value);
	}
	
	echo json_encode($rowDlv);
}
?>
