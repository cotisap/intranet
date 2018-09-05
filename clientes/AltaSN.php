<?php
session_start();
include "../includes/mysqlconn.php";
//include "../includes/mssqlconn.php";


$name = $_POST["Name_SN"];
$rfc = $_POST["RFC_SN"];
$tel = $_POST["Tel_SN"];
$email = $_POST["email_SN"];
$TipoSo = $_POST["TipoSo"];

$calle = $_POST ["Calle_F"];
$Colonia = $_POST ["Colonia_F"];
$munF = $_POST["Mun_Del_F"];
$ciudad =$_POST ["Ciudad_F"];
$pais= $_POST ["pais"];
$estado =$_POST ["estado"];
$cpF = $_POST ["CP_F"];

$calleE = $_POST ["Calle_E"];
$ColoniaE = $_POST ["Colonia_E"];
$munE = $_POST["Mun_Del_E"];
$ciudadE =$_POST ["Ciudad_E"];
$paisE= $_POST ["paise"];
$estadoE =$_POST ["estadoe"];
$cpE =$_POST ["CP_E"];

$LimCre = $_POST ["Lim_Cred"];
$IndImp = $_POST["IndImp"];
$CondPag = $_POST["CondPag"];
$LisPre = $_POST["LisPre"]; 

$coment = $_POST["Comentarios"];
//Empleado
$empl = $_SESSION["salesPrson"];

//  ID
	$numQuery = "SELECT Id_SN FROM SONE ORDER BY Id_SN DESC LIMIT 2";
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
    $queryP = "INSERT INTO SONE (Name_SN, RFC, Tel, email, Calle, Colonia, Ciudad, CP, Municipio, Estado, Pais,Calle_E, Colonia_E,Ciudad_E, Municipo_E, Estado_E, Pais_E, CP_E, Ind_Imp, Cond_Pago, Lista_Precios,Emp_Ven, Lim_Cred,Tipo_Sociedad, Comentarios,FechaCreacion, Id_SN) VALUES ('$name','$rfc','$tel','$email','$calle','$Colonia','$ciudad',$cpF,'$munF','$estado','$pais','$calleE','$ColoniaE','$ciudadE','$munE','$estadoE','$paisE',$cpE,'$IndImp','$CondPag',$LisPre,$empl,$LimCre,'$TipoSo','$coment',now() ,$Id)";
			
	$resultP = mysql_query($queryP);
	
	if(! $resultP )
{
  die('Could not enter data: ' . mysql_error());
 
}

echo "SE HA DADO DE ALTA";

?>