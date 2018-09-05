<?php
include "../includes/mysqlconn.php";

	//Solicitud de Alta de Clientes
    $queryP = "INSERT INTO EMA1 (Id_Doc, Status, Fecha, Emp_Ven) 
			VALUES ('','',now(),'')";
	$resultP = mysql_query($queryP);

$nombre = $_REQUEST["Name"];

$para      = 'nmartinez@idited.com';
$titulo    = 'Alta de Socio de Negocios';
$mensaje   = 'Hola';
mail($para, $titulo, $mensaje);

echo "CORREO ENVIADO ", $nombre;

?>
