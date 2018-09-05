<?php
session_start();

if ($_SESSION["authenticated_user"] != true) {
	$company = $_POST["company"];
} else {
	$company = $_SESSION["company"]; 
}

if($company == "fg" || $company == "alianza" || $company == "sureste" || $company == "pacifico" || $company == "alianzati" || $company == "manufacturing") {
	//$myServer = "187.178.181.4:1433";   //ip mbr --  Axtel
	//$myServer = "138.94.140.25:1433";   //ip FG --  Join networks
	//$myServer = "mbr-nznqgdqbdc.dynamic-m.com:1433"; 
	//sergio $myServer = "http://corporativo-vallejo-tntchvdtpq.dynamic-m.com:8082/";  //dinamica  axtel o join networks
   	$myServer = "corporativo-vallejo-tntchvdtpq.dynamic-m.com:1433/";  //dinamica  axtel o join networks
		$myUser = "sapbo";
	$myPass = "Alianza123$";
} elseif ($company == "mbr") {
	//$myServer = "demosap.cloudapp.net:1433";
	//$myUser = "SAP";
	//$myPass = "Mexico1*";
	// sergio$myServer = "http://corporativo-vallejo-tntchvdtpq.dynamic-m.com:8082/";
	$myServer = "mbr-nznqgdqbdc.dynamic-m.com:1433"; 
	$myUser = "intranetMBR";
	$myPass = "1ntr4n3t*";
}



switch ($company) {
	case "fg":
		//$myDB = "SBO_FG";
		$myDB = "Pruebas_FG";
		break;
	case "alianza":
		//$myDB = "SBO_ALIANZA_PRODUCTIVO_DF";
		$myDB = "SBO_Pruebas";
		break;
	case "sureste":
		//$myDB = "SBO_SURESTE";
		$myDB = "Pruebas_Sur";
		break;
	case "pacifico":
		$myDB = "SBO_PACIFICO";
		break;
	case "manufacturing":
		//$myDB = "SBO_MANUFACTURING";
		break;
	case "alianzati":
		//$myDB = "SBO_FGVJ";
		break;
	case "mbr":
		$myDB = "SBO_Pruebas_MBR";
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