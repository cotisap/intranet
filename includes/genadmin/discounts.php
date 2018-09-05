<?php
include "../mssqlconn.php";

$Code = $_POST["idDisc"];
$percent = $_POST["percent"];

$queryCode = "SELECT TOP 1 Code FROM [@DISCOUNT] ORDER BY Code DESC";
$resultCode = mssql_query($queryCode);
$rowCode = mssql_fetch_assoc($resultCode);
$lastCode = $rowCode["Code"];
$nCode = $lastCode + 1;

if ($Code == "") {
	$query = "INSERT INTO [@DISCOUNT] (Code, Name) VALUES ('$nCode', '$percent')";
} else {
	$query = "UPDATE [@DISCOUNT] SET Name = '$percent' WHERE Code = '$Code'";
}

$result = mssql_query($query);

if(!$result) {
	die('Could not enter data DISCOUNT: ' . mssql_error());
}
?>