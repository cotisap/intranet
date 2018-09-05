<?php
include "../includes/mysqlconn.php";

//LINEAS DE PEDIDO
$NameL = 'Lineas.csv';
$FileName = "./$NameL";
$line = 'ParentKey,LineNum,ItemCode,Quantity,Price,Currency,TaxCode,LineTotal,WarehouseCode';
$line .= "\r\n";
$line .= 'DocNum,LineNum,ItemCode,Quantity,Price,Currency,TaxCode,VatPrcnt,WhsCode';
$line .= "\r\n";
//Descarga el archivo desde el navegador
header('Expires: 0');
header('Cache-control: private');
header('Content-Type: application/x-octet-stream'); // Archivo de Excel
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Last-Modified: '.date('D, d M Y H:i:s'));
header('Content-Disposition: attachment; filename="'.$NameL.'"');
header("Content-Transfer-Encoding: binary");
/**1.  consultar sap_maestro_cartera_v3 **/
$queryL = "SELECT Id_Cot1, Codigo_Art, Cantidad, Moneda,Precio_Unidad, Sub_Tot_Line FROM COT1 where Id_Cot1 = 2000031";
$resultL = mysql_query($queryL);
$rowL = mysql_fetch_array($resultL);
$id_line = 0;
while($rowL = mysql_fetch_array($resultL))
{
    $line .= "$rowL[Id_Cot1],$id_line,$rowL[Codigo_Art],$rowL[Cantidad],$rowL[Precio_Unidad],$rowL[Moneda],IVA_V_16,$rowL[Sub_Tot_Line],1002";
    $line .= "\r\n"; 
	$id_line = $id_line + 1;
}
echo $line;
?>