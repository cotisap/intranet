<?php
include "../mssqlconn.php";

$emails = array();
$query = "SELECT code, U_email FROM [@EMLS]";
$result = mssql_query($query);
while ($row = mssql_fetch_assoc($result)) {
	$code = $row["code"];
	$queryW = "UPDATE [@EMLS] SET U_email = '".$_POST[$code]."' WHERE code = '$code'";
	$resultW = mssql_query($queryW);
	
	if(!$resultW) {
		die("Could not enter data EMLS $code: " . mysql_error());
	}
}
?>