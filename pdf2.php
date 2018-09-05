<?php
include_once "includes/mysqlconn.php";
include_once "includes/mssqlconn.php";

require('fpdf/mc_table.php');

$folioCot = $_REQUEST["idcot"];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(30,55,15,25,15,25,25));
$pdf->Row(array(utf8_decode("Código"),utf8_decode("Descripción"),"Cantidad","Precio de lista","Desc","Precio unitario","Total"));
$pdf->SetFont('Arial','I',8);
// Lineas de cotización
$queryCoti = "SELECT Id_Cot1, Codigo_Art, Nombre_Art, Cantidad, Moneda, Precio_Lista, UMV, Precio_Unidad, Factor, Sub_Tot_Line FROM COT1 WHERE Id_Cot1 = $folioCot";
$resultCoti = mysql_query($queryCoti);
while($rowCoti = mysql_fetch_array($resultCoti)) {
	$pdf->Row(array($rowCoti["Codigo_Art"],$rowCoti["Nombre_Art"],$rowCoti["Cantidad"]." ".$rowCoti["UMV"],$rowCoti["Precio_Lista"]." ".$rowCoti["Moneda"],$rowCoti["Factor"]." ",$rowCoti["Precio_Unidad"]." ".$rowCoti["Moneda"],$rowCoti["Sub_Tot_Line"]." ".$rowCoti["Moneda"]));
}

////////////////////////////////////////////////////////////////////
	//Subtotal USD
	$queyrSubUSD = "SELECT SUM( Sub_Tot_Line ) AS SubtotalUSD FROM COT1 WHERE Id_Cot1 = $folioCot AND Moneda = 'USD'";
	$resultSubUSD = mysql_query($queyrSubUSD);
	$rowSubUSD = mysql_fetch_array($resultSubUSD);
	
	$SubtotalDocUSD = $rowSubUSD["SubtotalUSD"];
	
	//Subtotal MXN
	$queyrSubMXN = "SELECT SUM( Sub_Tot_Line ) AS SubtotalMXN FROM COT1 WHERE Id_Cot1 = $folioCot AND Moneda = 'MXN'";
	$resultSubMXN = mysql_query($queyrSubMXN);
	$rowSubMXN = mysql_fetch_array($resultSubMXN);
	
	$SubtotalDocMXN = $rowSubMXN["SubtotalMXN"];
	
	//Total Doc
	$folioCot = $_REQUEST["idcot"];
	$query = "SELECT Id_Cot1, Total_Doc, Empl_Ven, TC FROM COTI WHERE Id_Cot1 = $folioCot";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	
	$TotalDoc = $row["Total_Doc"];
	
	//Importes totales de Cotización	
	//Total MXN
	
	$IVAMXN = $SubtotalDocMXN * 0.16;
	$TotalMXN = $SubtotalDocMXN + $IVAMXN;
	
	$pdf->Cell(190,5,' ',0,1);
	$pdf->Cell(140);
	$pdf->SetFont('Arial','B',8); //Titulos
	$pdf->Cell(50,5,'Total de partidas en MN',1,2,'C');
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(25,5,'Subtotal:',1,0,'L');
	$pdf->Cell(25,5,'$'.number_format($SubtotalDocMXN,2),1,1,'R');
	$pdf->Cell(140);
	$pdf->Cell(25,5,'I.V.A.:',1,0,'L');
	$pdf->Cell(25,5,'$'.number_format($IVAMXN,2),1,1,'R');
	$pdf->Cell(140);
	$pdf->Cell(25,5,'Total:',1,0,'L');
	$pdf->Cell(25,5,'$'.number_format($TotalMXN,2),1,1,'R');
	
	//Total USD
	$IVAUSD = $SubtotalDocUSD * 0.16;
	$TotalUSD = $SubtotalDocUSD + $IVAUSD;
	
	$pdf->Cell(190,5,' ',0,1);
	$pdf->Cell(140);
	$pdf->SetFont('Arial','B',8); //Titulos
	$pdf->Cell(50,5,'Total de partidas en USD',1,2,'C');
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(25,5,'Subtotal:',1,0,'L');
	$pdf->Cell(25,5,'$'.number_format($SubtotalDocUSD,2),1,1,'R');
	$pdf->Cell(140);
	$pdf->Cell(25,5,'I.V.A.:',1,0,'L');
	$pdf->Cell(25,5,'$'.number_format($IVAUSD,2),1,1,'R');
	$pdf->Cell(140);
	$pdf->Cell(25,5,'Total:',1,0,'L');
	$pdf->Cell(25,5,'$'.number_format($TotalUSD,2),1,1,'R');	
	
	//Total de Documento en MXN
	$pdf->Cell(190,5,' ',0,1);	
	$SubtotalDoc3 = ($TotalDoc / 1.16);
	$IVA3 = ($TotalDoc - $SubtotalDoc3);
	
	$pdf->Cell(140);
	$pdf->SetFont('Arial','B',8); //Titulos
	$pdf->Cell(50,5,'Total MN con TC Actual',1,2,'C');
	$pdf->Cell(25,5,'Subtotal:',1,0,'L');
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(25,5,'$'.number_format($SubtotalDoc3,2),1,1,'R');
	$pdf->Cell(140);
	$pdf->SetFont('Arial','B',8); //Titulos
	$pdf->Cell(25,5,'I.V.A.:',1,0,'L');
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(25,5,'$'.number_format($IVA3,2),1,1,'R');
	$pdf->Cell(140);
	$pdf->SetFont('Arial','B',9); //Titulos
	$pdf->Cell(25,5,'Total:',1,0,'L');
	$pdf->Cell(25,5,'$'.number_format($TotalDoc,2),1,1,'R');
	//////////////////////////////////////////////////////////////////////

$pdf->Output();
?>