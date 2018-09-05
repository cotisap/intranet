<?php
session_start();
include "mysqlconn.php";


$name = $_POST["Name_SN"];
$name_cp = $_POST["Name_CP"];
$tel = $_POST["Tel_SN"];
$email = $_POST["email_SN"];

$coment = $_POST["Comentarios"];
//Empleado
$empl = $_SESSION["salesPrson"];

//  ID
	$numQuery = "SELECT Id_SN FROM SONE ORDER BY Id_SN DESC LIMIT 1";
	$resultID = mysql_query($numQuery);
	if (!$resultID)
	{
		die('Could not read data: ' . mysql_error());
	}else{
	$rowID = mysql_fetch_array($resultID);	
	$Id = $rowID["Id_SN"]+1;
	}

//
if ($LimCre=="")
{
	$LimCre=0;
		}

//insert Cliente Nuevo	
    $queryP = "INSERT INTO SONE (Name_SN, Name_CP, Tel, email, Emp_Ven, Comentarios, FechaCreacion, Id_SN, company) VALUES ('$name', '$name_cp', '$tel', '$email', $empl, '$coment', now(), $Id, '".$_SESSION["company"]."')";
			
	$resultP = mysql_query($queryP);
	
	if(! $resultP )
{
  die('Could not enter data CUSTOMER: ' . mysql_error());
 
}

header('Location: /altacliente.php?msg=snsuccess');

?>