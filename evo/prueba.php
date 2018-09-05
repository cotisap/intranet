<?php
include "../includes/mssqlevo.php";

$query = "SELECT * FROM OWHS";
$result = mssql_query($query);
while ($row = mssql_fetch_assoc($result)) {
	echo "Algo: ".$row["WhsCode"].", ".$row["WhsName"]."<br>";
}
?>