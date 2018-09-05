<?php
include "../includes/mysqlconn.php";

//CABECERA DE PEDIDO
$Name = 'Pedidos.csv';
$FileName = "./$Name";
$Datos = 'DocNum,DocDate,DocDueDate,CardCode,DocCurrency,DocRate,DocTotal';
$Datos .= "\r\n";
$Datos .= 'DocNum,DocDate,DocDueDate,CardCode,DocCurrency,DocRate,DocTotal';
$Datos .= "\r\n";
//Descarga el archivo desde el navegador
header('Expires: 0');
header('Cache-control: private');
header('Content-Type: application/x-octet-stream'); // Archivo de Excel
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Last-Modified: '.date('D, d M Y H:i:s'));
header('Content-Disposition: attachment; filename="'.$Name.'"');
header("Content-Transfer-Encoding: binary");
/**1.  consultar sap_maestro_cartera_v3 **/
$queryP = "SELECT  Id_Cot1, DATE_FORMAT(FechaCreacion, '%Y%m%d') as Date, Codigo_SN, CardName, Total_MN, Total_Doc, TC FROM COTI where Id_Cot1=2000031";
$resultP = mysql_query($queryP);
$rowP = mysql_fetch_array($resultP);
$Datos .= "$rowP[Id_Cot1],$rowP[Date],$rowP[Date],$rowP[Codigo_SN],MXN,$rowP[TC],$rowP[Total_Doc]";
$Datos .= "\r\n"; 
echo $Datos;
?>