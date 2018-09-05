<?php

/*
$myServer = '138.94.140.25:1433';
$myUser = "sa";
$myPass = "Alianza123$";
$myDB = "Pruebas FG";
*/


$myServer = '138.94.140.25:1433';
$myUser = "sa";
$myPass = "Alianza123$";
$myDB = "SBO_Pruebas_Productivo"; 
 

/*
switch ($company) {
	case "fg":
		$myDB = "FGELECTRICALPROD";
		break;
	case "alianza":
		$myDB = "SBO_Pruebas_Productivo"; $myDB = "Pruebas_Sureste"; $myDB= "Pruebas_Pacifico";
	//$myDB = "INTRANET_FG_AL";//Test DB
		break;
	
}
*/

	
//connection to the database
$dbhandle = mssql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on $myServer"); 

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB"); 

//mssql_pconnect($dbhandle);	
//close the connection
mssql_close($dbhandle);
?>