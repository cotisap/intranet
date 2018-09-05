<?php
include_once "includes/mysqlconn.php";
include_once "includes/mssqlconn.php";

require('fpdf/mc_tableExp.php');

$folioCot = $_REQUEST["idcot"];
$pdf = new PDF_MC_Table();
$pdf->AddFont('Narrow','','narrow.php');
$pdf->AddFont('Narrow','B','narrowb.php');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Narrow','B',8,'C');
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(10,28,80,13,17,24,24));
$pdf->Row(array("#",utf8_decode("Código"),utf8_decode("Descripción"),"Cantidad","U. Medida","Precio Unitario","Precio Total"));
$pdf->SetFont('Narrow','',8, 'C');



//Datos cotización
$query1 = "SELECT Id_Cot1, Codigo_SN, Comentarios, cnotes, account FROM COTI WHERE Id_Cot1 = $folioCot";
$result1 = mysql_query($query1);
$row1 = mysql_fetch_array($result1);

$Comentarios= $row1["Comentarios"];
$cnotes = $row1["cnotes"];
$account = $row1["account"];
	
//Ctas Bancarias 
$querycta = "SELECT AcctCode, AcctName, AccntntCod, U_account, U_clabe, U_bankName FROM OACT WHERE FatherNum = '1120-000-000' AND AccntntCod = '$account'";
$resultcta = mssql_query($querycta);
$rowcta = mssql_fetch_assoc($resultcta);

$queryctaUSD = "SELECT AcctCode, AcctName, AccntntCod, U_account, U_clabe, U_bankName FROM OACT WHERE FatherNum = '1120-000-000' AND ActCurr = 'USD'";
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
$queryCoti = "SELECT T1.Id_Cot1, T1.lineNum, T1.Codigo_Art, T1.Nombre_Art, T1.Cantidad, T1.Moneda, T1.UMV, T1.Precio_Unidad, T1.Sub_Tot_Line, T2.Text AS TiempoEntrega, T1.remarks FROM COT1 T1 JOIN DLVT T2 ON T1.TiempoEntrega = T2.id WHERE Id_Cot1 = $folioCot ORDER BY T1.lineNum ASC";
$resultCoti = mysql_query($queryCoti);
$pdf->SetDrawColor(255,255,255);
while($rowCoti = mysql_fetch_array($resultCoti)) {
	$pdf->Row(array($rowCoti["lineNum"],$rowCoti["Codigo_Art"],$rowCoti["Nombre_Art"],$rowCoti["Cantidad"],$rowCoti["UMV"],"$ ".number_format($rowCoti["Precio_Unidad"], 2)." ".$rowCoti["Moneda"],"$ ".number_format($rowCoti["Sub_Tot_Line"], 2)." ".$rowCoti["Moneda"]));
	$pdf->SetWidths(array(38,163));
	$pdf->Row(array(" ", " Tiempo de entrega: ".$rowCoti["TiempoEntrega"]." Comentarios: ".$rowCoti["remarks"]));
	$pdf->SetWidths(array(10,28,80,13,17,24,24));
}
$pdf->SetDrawColor(0,0,0);


////////////////////////////////////////////////////////////////////
$curRates = array();
$queryCurRates = "SELECT Currency, Rate FROM ORTT WHERE RateDate = CONVERT(date, getdate())";
$resultCurRates = mssql_query($queryCurRates);
while ($rowCurRates = mssql_fetch_assoc($resultCurRates)) {
	$curRates[$rowCurRates["Currency"]] = $rowCurRates["Rate"];
}

//Subtotal USD
$queyrSubUSD = "SELECT SUM( Sub_Tot_Line ) AS SubtotalUSD FROM COT1 WHERE Id_Cot1 = $folioCot AND Moneda = 'USD'";
$resultSubUSD = mysql_query($queyrSubUSD);
$rowSubUSD = mysql_fetch_array($resultSubUSD);

$SubtotalDocUSD = $rowSubUSD["SubtotalUSD"];

//Subtotal MXN
$queryMN = "SELECT ChkName, CurrCode FROM OCRN WHERE ChkName = 'Pesos'";
$resultMN = mssql_query($queryMN);
$rowMN = mssql_fetch_assoc($resultMN);
$mnCode = $rowMN["CurrCode"];
	
$queyrSubMXN = "SELECT SUM( Sub_Tot_Line ) AS SubtotalMXN FROM COT1 WHERE Id_Cot1 = $folioCot AND Moneda = '$mnCode'";
$resultSubMXN = mysql_query($queyrSubMXN);
$rowSubMXN = mysql_fetch_array($resultSubMXN);

$SubtotalDocMXN = $rowSubMXN["SubtotalMXN"];

//Importes totales de Cotización	
//Total MXN

$IVAMXN = $SubtotalDocMXN * 0.16;
$TotalMXN = $SubtotalDocMXN + $IVAMXN;

$pdf->Cell(196,5,'',0,1);

$y = $pdf->GetY();
if (($y + 70) > 265) {
    $pdf->AddPage();
}

$pdf->Cell(148);
$pdf->SetFont('Narrow','B',8); //Titulos

//Total USD
$IVAUSD = $SubtotalDocUSD * 0.16;
$TotalUSD = $SubtotalDocUSD + $IVAUSD;

$pdf->Cell(190,5,' ',0,1);
$pdf->Cell(148);
$pdf->SetFont('Narrow','B',8); //Titulos
$pdf->Cell(48,5,'Total USD',1,2,'C');
$pdf->SetFont('Narrow','',8);
$pdf->Cell(24,5,'',0,0,'L');
$pdf->SetFont('Narrow','B',8);
$pdf->Cell(24,5,'$ '.number_format($SubtotalDocUSD,2),0,1,'R');

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

$pdf->SetFont('Narrow','B',8);
$pdf->Cell(196,5,'Notas Comerciales: ',0,1,'L');
$pdf->SetFont('Narrow','',8);
$pdf->WriteHTML($NotasComerciales."<br>");
$pdf->Ln(2);

$pdf->SetFont('Narrow','B',8);
$pdf->Cell(196,.1,'',0,1,'',true);
$pdf->SetFont('Narrow','B',8);
// Page number
$pdf->Cell(30,6,'Comentarios: ',0,1,'L');
$pdf->SetFont('Narrow','',8);
$pdf->MultiCell(190, 5, $Comentarios,0,'L', false);
$pdf->Cell(196,.1,'',0,1,'',true);
$pdf->SetFont('Narrow','B',8);
$pdf->Cell(196, 5,'Favor de realizar su pago en la cuenta:',0,1, 'L');
$pdf->SetFont('Narrow','',8);
$pdf->Cell(196, 5,$BancoUSD.", Banco: ".$bankUSD.", Cuenta: ".$CtaUSD.", Clabe Interbancaria: ".$ClabeUSD,0, 1, 'L');
$pdf->Cell(196,5,'',0,1,'');

$fileName = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if ($fileName == "sendQuoteExp") {
	
} else {
	$pdf->Output();
}
?>