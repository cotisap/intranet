<?php

include 'mssqlconn.php';
$query = "SELECT CardCode, CardName, (CardCode + ' - ' + CardName) as Name FROM OCRD WHERE VatStatus = 'Y' AND CardType = 'C'";
$result = mssql_query($query);
$data = array();
while($row = mssql_fetch_array($result)){

	array_push($data, [
		"CardCode" => $row["CardCode"],
		"Name" => $row["Name"]
	]);
}
var_dump($data);
echo json_encode( $data );
?>