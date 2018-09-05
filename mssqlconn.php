<?php
session_start();

if ($_SESSION["authenticated_user"] != true) {
	$company = $_POST["company"];
} else {
	$company = $_SESSION["company"]; 
}

if($company == "fg" || $company == "alianza" || $company == "sureste" || $company == "pacifico" || $company == "alianzati" || $company == "manufacturing") {
	//$myServer = "187.178.181.4:1433";
	$myServer = "138.94.140.25:1433";
	//$myUser = "sapbo";
	//$myPass = "Alianza123$";
	$myUser = "sapbo";
	$myPass = "L0g0ut*";
} elseif ($company == "mbr") {
	$myServer = "demosap.cloudapp.net:1433";
	$myUser = "SAP";
	$myPass = "Mexico1*";
}



switch ($company) {
	case "fg":
		$myDB = "Pruebas_FG";
		break;
	case "alianza":
		$myDB = "SBO_ALIANZA_PRODUCTIVO_DF";
		break;
	case "sureste":
		$myDB = "SBO_SURESTE";
		break;
	case "pacifico":
		$myDB = "SBO_PACIFICO";
		break;
	case "alianzati":
		$myDB = "SBO_FGVJ";
		break;
	case "manufacturing":
		$myDB = "SBO_MANUFACTURING";
		break;
	case "mbr":
		$myDB = "MBR";
		break;
	
}

//connection to the database
$dbhandle = mssql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on ".$myServer); 

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB"); 

//close the connection
//mssql_close($dbhandle);
?>