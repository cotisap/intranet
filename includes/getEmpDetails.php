<?php
include "mssqlconn.php";

$empID = $_GET["empID"];

$queryEmp = "SELECT TOP 1 Commission, Telephone, Fax, Email, U_pass, U_priceList, U_branch, U_export, U_discounts FROM OSLP WHERE SlpCode = '$empID'";
$resultEmp = mssql_query($queryEmp);
$rowEmp = mssql_fetch_assoc($resultEmp);

if(count($rowEmp) != 0) {
	foreach($rowEmp as $value) {
		$value = utf8_encode($value);
	}
	echo json_encode($rowEmp);
}
?>
