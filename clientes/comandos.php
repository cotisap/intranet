<?php
include "../includes/mysqlconn.php";
include "../includes/mssqlconn.php";

$name = $_POST["Name_SN"];
$rfc = $_POST["RFC_SN"];
$tel = $_POST["Tel_SN"];
$email = $_POST["email_SN"];
$moneda = $_POST["Moneda_SN"];
$coment = $_POST["Comentarios"];

//Insert Cliente
    $queryP = "INSERT INTO `SONE`(`Name_SN`, `RFC`, `Tel`, `email`,`Emp_Ven`,`Tipo_Sociedad`, `Comentarios`, `Id_SN`) VALUES ('$name','$rfc','$tel','$email','1','C','$coment',1000000)";
	$resultP = mssql_query($queryP);

echo "SE HA DADO DE ALTA";



?>
