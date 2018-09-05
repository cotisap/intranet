<?php
include "../mssqlconn.php";

$Code = $_POST["idNote"];
$Name = utf8_decode($_POST["name"]);
$conditions = utf8_decode(nl2br($_POST["conditions"]));

$queryCode = "SELECT TOP 1 Code FROM [@CNOT] ORDER BY Code DESC";
$resultCode = mssql_query($queryCode);
$rowCode = mssql_fetch_assoc($resultCode);
$lastCode = $rowCode["Code"];
$nCode = $lastCode + 1;

if ($Code == '') {
	$query = "INSERT INTO [@CNOT] (Code, Name, U_conditions) VALUES ('$nCode', '$Name', '$conditions')";
} else {
	$query = "UPDATE [@CNOT] SET Name = '$Name', U_conditions = '$conditions' WHERE Code = '$Code'";
}

$result = mssql_query($query);

if(!$result) {
	die('Could not enter data CNOT: ' . mssql_error());
}
?>