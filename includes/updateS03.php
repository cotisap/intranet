<?php
session_start();
include "mssqlconn.php";

$DocEntry = $_POST["DocEntry"];
$invNum = $_POST["invNum"];

//insert Lines
foreach ($_POST['s03'] as $key => $value) {
	$s03 = $_POST["s03"][$key];
	
	$queryL = "UPDATE INV1 SET U_Sigla03 = '$s03' WHERE LineNum = '$key' AND DocEntry = '$DocEntry'";

	$resultL = mssql_query($queryL);
	if(!$resultL) {
		die('Could not enter data S03: ' . mssql_error());
	}
}

header("Location: /verfactura.php?num=".$invNum."&msg=cotupsuccess");
?>