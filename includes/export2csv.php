<?php
include "mysqlconn.php";
include "mssqlconn.php";

$slpCode = $_GET["slpCode"];

$querySLP = "SELECT SlpName FROM OSLP WHERE SlpCode = '$slpCode'";
$resultSLP = mssql_query($querySLP);
$rowSLP = mssql_fetch_assoc($resultSLP);
$slpName = $rowSLP["SlpName"];

$output = "";
$query = mysql_query("SELECT T1.Id_Cot1 AS 'Folio Referencia', T2.FechaCreacion AS 'Fecha', T2.Codigo_SN AS 'Código Cliente', T2.CardName AS 'Nombre Cliente', T1.Codigo_Art AS 'Código Artículo', T1.Nombre_Art AS 'Nombre Artículo', T1.Precio_Lista AS 'Precio de Lista', T1.Moneda, T1.Factor AS 'Descuento', T1.Precio_Unidad AS 'Precio Unitario', T1.Cantidad, T1.Sub_Tot_Line AS 'Importe', T1.UMV AS 'U.M.V.', T1.TiempoEntrega AS 'Tiempo Entrega', T1.Almacen AS 'Almacén', T1.remarks AS 'Comentarios' FROM COT1 T1 JOIN COTI T2 ON T1.Id_Cot1 = T2.Id_Cot1 WHERE T2.Empl_Ven = '$slpCode' ORDER BY T1.Id_Cot1 ASC");
$result = mysql_num_fields($query);

// Get The Field Name

for ($i = 0; $i < $result; $i++) {
	$heading	=	utf8_decode(mysql_field_name($query, $i));
	$output		.= '"'.$heading.'",';
}
$output .="\n";

// Get Records from the table

while ($row = mysql_fetch_array($query)) {
	for ($i = 0; $i < $result; $i++) {
		$output .='"'.$row["$i"].'",';
	}
	$output .="\n";
}

// Download the file

$filename =  "Cotizaciones ".$slpName.".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;
	
?>
