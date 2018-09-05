<?php
session_start();
include "../includes/mysqlconn.php";
//include "../includes/mssqlconn.php";

$Code_Art = $_POST["Code_Art"];
$Des_Art = $_POST["Des_Art"];
$Suj_Imp = $_POST["Suj_Imp"];
$Grp_Art= $_POST["Grp_Art"];
$List_Art = $_POST["Lis_Art"];

$Precio_Art = $_POST ["Precio_Art"];
$moneda = $_POST ["moneda"];
$UMV =$_POST ["UMV"];


$coment = $_POST["Comentarios"];
//Empleado
$empl = $_SESSION["salesPrson"];

	//  ID
	$numQuery = "SELECT Id_Art FROM ART1 ORDER BY Id_Art DESC LIMIT 2";
	$resultID = mysql_query($numQuery);
	if (!$resultID)
	{
		die('Could not read data: ' . mysql_error());
	}else{
	$rowID = mysql_fetch_array($resultID);	
	$Id = $rowID["Id_Art"]+1;
	}


//
//insert Cliente Nuevo	
    $queryP = "INSERT INTO ART1 (Codigo_Art, Descripcion, Grupo_Art,Lista_Precio,UMV,precio,moneda, Comentarios,FechaCreacion,Id_Art,Empl_Ven) VALUES ('$Code_Art','$Des_Art',$Grp_Art,$List_Art,'$UMV',$Precio_Art,'$moneda','$coment',now(),$Id,$empl)";
	
			
	$resultP = mysql_query($queryP);
	
	if(! $resultP )
{
  die('Could not enter data: ' . mysql_error());
  echo $queryP;
}

echo "SE HA DADO DE ALTA";




?>