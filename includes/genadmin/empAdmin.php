<?php
include "../mssqlconn.php";

$empID = $_GET["empID"];
$mgrID = $_GET["mgrID"];

$query = "UPDATE OSLP SET U_manager = ".$mgrID." WHERE SlpCode = ".$empID;
$result = mssql_query($query);

if(!$result) {
	die('Could not set Emp Manager: ' . mssql_error());
}

?>