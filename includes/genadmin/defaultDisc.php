<?php
include "../mssqlconn.php";

$discount = $_GET["disc"];

$queryReset = "UPDATE [@DISCOUNT] SET U_discount = 0";
$resultReset = mssql_query($queryReset);

if(!$resultReset) {
	die('Could not reset data : ' . mssql_error());
}

$queryDefault = "UPDATE [@DISCOUNT] SET U_discount = 1 WHERE Name = '$discount'";
$resultDefault = mssql_query($queryDefault);

if(!$resultDefault) {
	die('Could not set data Default : ' . mssql_error());
}
?>