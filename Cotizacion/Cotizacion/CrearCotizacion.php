<?php
session_start();
include "../includes/mysqlconn.php";
//include "../includes/mssqlconn.php";

//Empleado
$empl = $_SESSION["salesPrson"];
$mail= $_SESSION["email"];
//Cliente
$code = $_POST["CardCode"];

//Articulo
//Cotizacion
$coment = $_POST["remarks"];

	$numQuery = "SELECT Id_Cot1 FROM COTI ORDER BY Id_Cot1 DESC LIMIT 2";
	$resultID = mysql_query($numQuery);
	if (!$resultID)
	{
		die('Could not read data: ' . mysql_error());
	}else{
	$rowID = mysql_fetch_array($resultID);	
	$Id = $rowID["Id_Cot1"]+1;
	}


//insert Cotizacion	
    $queryC = "INSERT INTO COTI (Codigo_SN, CardName,Serie,FechaCreacion,Comentarios,Empl_Ven,Id_Cot1) VALUES ( '$code','$name','',now(),'$coment',$empl,$Id)";
	$resultC = mysql_query($queryC);
//insert Lineas
foreach ($_POST['product'] as $key => $value) 
	{
		$quant = $_POST['quant'][$key];
		$price=$_POST["linePrice"];
		
		$queryL = "INSERT INTO COT1 (Refe_Cot, Line, Codigo_Art,Cantidad,Precio_Unidad,FechaCreacion) VALUES ( $Id,0,'$value','$quant',$price,now())";

		$resultL = mysql_query($queryL);
		if(! $resultL )
			{
			  die('Could not read data: ' . mysql_error());
			} else {
				echo "SE HA DADO DE ALTA";
			}
	}

//Solicitud de Alta de Clientes
    $queryP = "INSERT INTO EMA1 (Id_Doc, Status, Fecha, Emp_Ven) 
			VALUES ('','',now(),'')";
	$resultP = mysql_query($queryP);

//envio de correo
$para      = 'nmartinez@idited.com';
$titulo    = 'Alta de Cotizacion';
$mensaje   = 'Se ha Creado ';
mail($para, $titulo, $mensaje);

echo "SE HA DADO DE ALTA";




?>