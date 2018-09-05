<?php
include_once "includes/mysqlconn.php";
include_once "includes/mssqlconn.php";

require('fpdf/mc_table_mbr_sq.php');

$folioCot = $_REQUEST["idcot"];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Helvetica','B',7,'C');
// Table
$pdf->SetFillColor(85,187,234);
$pdf->SetTextColor(255,255,255);
$pdf->SetWidths(array(7,25,70,13,14,23,23, 23));
$pdf->Row(array("#",utf8_decode("Código"),utf8_decode("Descripción"),"Cantidad","U. Medida","P. U. (Sin dsc.)","Descuento","Importe"));
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);

//Datos cotización
$query1 = "SELECT Id_Cot1, Codigo_SN, Comentarios, cnotes, account, DocCur, tax, DiscPrcnt FROM COTI WHERE Id_Cot1 = $folioCot";
$result1 = mysql_query($query1);
$row1 = mysql_fetch_assoc($result1);

$DocCur = $row1["DocCur"];
$Tax = $row1["tax"];
$DiscPrcnt = $row1["DiscPrcnt"];
//$DiscPrcnt = 10.00;
$Comentarios= $row1["Comentarios"];
$cnotes = $row1["cnotes"];
$account = $row1["account"];
	
//Ctas Bancarias

$querycta = "SELECT AcctCode, AcctName, AccntntCod, U_account, U_clabe, U_bankName FROM OACT WHERE FatherNum = '".$_SESSION["FatherNum"]."' AND AccntntCod = '$account'";
$queryctaUSD = "SELECT AcctCode, AcctName, AccntntCod, U_account, U_clabe, U_bankName FROM OACT WHERE FatherNum = '".$_SESSION["FatherNum"]."' AND ActCurr = 'USD'";

$resultcta = mssql_query($querycta);
$rowcta = mssql_fetch_assoc($resultcta);

$resultctaUSD = mssql_query($queryctaUSD);
$rowctaUSD = mssql_fetch_assoc($resultctaUSD);


$BancoMXP = $rowcta["AcctName"];
$CtaMXP = $rowcta["U_account"];
$ClabeMXP = $rowcta["U_clabe"];
$bankMXP = $rowcta["U_bankName"];
$BancoUSD = $rowctaUSD["AcctName"];
$CtaUSD = $rowctaUSD["U_account"];
$ClabeUSD = $rowctaUSD["U_clabe"];
$bankUSD = $rowctaUSD["U_bankName"];
	
	
	
// Lineas de cotización
$queryCoti = "SELECT T1.Id_Cot1, T1.lineNum, T1.Codigo_Art, T1.Nombre_Art, T1.FirmName, T1.Cantidad, T1.Moneda, T1.UMV, T1.Precio_Unidad, T1.Sub_Tot_Line, T1.TiempoEntrega, T1.remarks FROM COT1 T1 WHERE Id_Cot1 = $folioCot ORDER BY T1.lineNum ASC";
$resultCoti = mysql_query($queryCoti);
$pdf->SetDrawColor(255,255,255);
$pdf->SetFont('Helvetica','',7);
while($rowCoti = mysql_fetch_array($resultCoti)) {
	$y = $pdf->GetY();
	if (($y + 10) > 265) {
		$pdf->AddPage();
	}
	$pdf->Row(array($rowCoti["lineNum"],$rowCoti["Codigo_Art"],$rowCoti["Nombre_Art"],$rowCoti["Cantidad"],$rowCoti["UMV"],"$ ".number_format(floatval($rowCoti["Precio_Unidad"]), 2).$rowCoti["Moneda"],"$".number_format((floatval($rowCoti["Precio_Unidad"])*intval($rowCoti["Cantidad"])-floatval($rowCoti["Sub_Tot_Line"])), 2).$rowCoti["Moneda"],"$ ".number_format($rowCoti["Sub_Tot_Line"], 2)." ".$DocCur));
	$pdf->SetWidths(array(38,163));
	$pdf->Row(array("", "Marca: ".$rowCoti["FirmName"]." Entrega: ".$rowCoti["TiempoEntrega"]." Comentarios: ".$rowCoti["remarks"]));
	$pdf->SetWidths(array(7,25,70,13,14,23,23, 23));
}
$pdf->SetDrawColor(0,0,0);


////////////////////////////////////////////////////////////////////
$curRates = array();
$queryCurRates = "SELECT Currency, Rate FROM ORTT WHERE RateDate = CONVERT(date, getdate())";
$resultCurRates = mssql_query($queryCurRates);
while ($rowCurRates = mssql_fetch_assoc($resultCurRates)) {
	$curRates[$rowCurRates["Currency"]] = $rowCurRates["Rate"];
}

//Subtotal
$querySub = "SELECT SUM(Sub_Tot_Line) AS Subtotal FROM COT1 WHERE Id_Cot1 = $folioCot";
$resultSub = mysql_query($querySub);
$rowSub = mysql_fetch_array($resultSub);

$Subtotal = $rowSub["Subtotal"];
$gDisc = $Subtotal * $DiscPrcnt / 100;
$subDisc = $Subtotal - $gDisc;
$iva = $subDisc * $Tax / 100;
$TotalDoc = $subDisc + $iva;

$pdf->Cell(196,5,'',0,1);

$y = $pdf->GetY();
if (($y + 50) > 265) {
    $pdf->AddPage();
}

$pdf->Cell(148);
$pdf->SetFont('Helvetica','B',8); //Titulos
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(85,187,234);
$pdf->Cell(48,5,'Totales ('.$DocCur.')',0,2,'C',1);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(24,5,'Subtotal (Sin dsc.):',0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(24,5,'$ '.number_format(($Subtotal + $gDisc),2),0,1,'R');
$pdf->Cell(148);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(24,5,'Descuento ('.$DiscPrcnt.'%):',0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(24,5,'$ '.number_format($gDisc,2),0,1,'R');
$pdf->Cell(148);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(24,5,'Subtotal:',0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(24,5,'$ '.number_format($Subtotal,2),0,1,'R');
$pdf->Cell(148);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(24,5,'I.V.A.:',0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(24,5,'$ '.number_format($iva,2),0,1,'R');
$pdf->Cell(148);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(24,5,'Total:',0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(24,5,'$ '.number_format($TotalDoc,2),0,1,'R');
//////////////////////////////////////////////////////////////////////

$pdf->Ln(2);

//Query notas comerciales
$folioCot = $_REQUEST["idcot"];
$queryNotas = "SELECT U_conditions FROM [@CNOT] WHERE Code = '$cnotes'";
$resultNotas = mssql_query($queryNotas);
$rowNotas = mssql_fetch_assoc($resultNotas);

$NotasComerciales = $rowNotas["U_conditions"];

$y = $pdf->GetY();
if (($y + 20) > 265) {
    $pdf->AddPage();
}

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(196,5,'Notas Comerciales: ',0,1,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->WriteHTML($NotasComerciales."<br>");
$pdf->Ln(2);
$pdf->SetFillColor(85,187,234);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(196,.1,'',0,1,'',true);
$pdf->SetFont('Helvetica','B',8);
// Page number
$pdf->Cell(30,6,'Comentarios: ',0,1,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->MultiCell(190, 5, $Comentarios,0,'L', false);
$pdf->Cell(196,.1,'',0,1,'',true);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(196, 5,'Favor de realizar su pago en las cuentas:',0,1, 'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(196, 5,$BancoMXP.", Banco: ".$bankMXP.", Cuenta: ".$CtaMXP.", Clabe Interbancaria: ".$ClabeMXP,0, 1, 'L');
$pdf->Cell(196, 5,$BancoUSD.", Banco: ".$bankUSD.", Cuenta: ".$CtaUSD.", Clabe Interbancaria: ".$ClabeUSD,0, 1, 'L');
$pdf->Cell(196,5,'',0,1,'');

$fileName = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if ($fileName == "sendQuote") {
	
} else {
	$pdf->Output();
}
?>