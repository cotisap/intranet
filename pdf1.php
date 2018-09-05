<?php
session_start();
include_once "includes/mysqlconn.php";
include_once "includes/mssqlconn.php";
include("fpdf/fpdf.php");


class PDF extends FPDF
{
	
// Page header
function Header()
{
	$folioCot = $_REQUEST["idcot"];
	
	
	//Folio de Cotización
	$query = "SELECT Id_Cot1, CONCAT(CardName, ' - ', Codigo_SN) AS Cliente, Codigo_SN, FechaCreacion, Total_Doc, Empl_Ven, TC FROM COTI WHERE Id_Cot1 = $folioCot";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	
	$cliente = $row["Cliente"];
	$folioCot = $row["Id_Cot1"];
	$Empleado = $row["Empl_Ven"];
	$FechaDoc = $row["FechaCreacion"];
	$TotalDoc = $row["Total_Doc"];
	$TC = $row["TC"];
	$NumCte = $row["Codigo_SN"];
	
	// Lineas de cotización
	$queryCoti = "SELECT Id_Cot1, Codigo_Art, Nombre_Art, Cantidad, Moneda, Precio_Lista, UMV, Precio_Unidad, Factor, Sub_Tot_Line FROM COT1 WHERE Id_Cot1 = $folioCot";
	$resultCoti = mysql_query($queryCoti);
	$rowCoti = mysql_fetch_array($resultCoti);
	
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
	
		
	//Datos de sucursal
	$querySuc = "SELECT T1.CompnyName, T1.TaxIdNum, T1.TaxRegime, T2.Street, T2.Block, T2.City, T2.ZipCode, T2.County, T2.State, T2.Country, T2.IntrntAdrs, T1.Phone1, T1.Phone2, T1.E_Mail FROM OADM T1 INNER JOIN ADM1 T2 ON T1.Code = T2.Code WHERE T1.Code = 1";
	$resultSuc = mssql_query($querySuc);
	$rowSuc = mssql_fetch_array($resultSuc);
	
	$NomSuc = $rowSuc["CompnyName"];
	$RFCSUc = $rowSuc["TaxIdNum"];
	$DomSuc = $rowSuc["Street"].", ".$rowSuc["Block"].", ".$rowSuc["County"].", ".$rowSuc["ZipCode"].", ".$rowSuc["City"].", ".$rowSuc["State"];
	$TelSuc = $rowSuc["Phone1"].", ".$rowSuc["Phone2"];
	$EmailSuc = $rowSuc["E_Mail"];
	$PWeb = $rowSuc["IntrntAdrs"];
	
	//Datos de Cliente
		
	$queryCte = "SELECT T1.CardCode, T1.CardName, T1.LicTradNum, T2.Street, T2.Block, T2.City, T2.ZipCode, T2.County, T2.State, T2.Country FROM OCRD T1 INNER JOIN CRD1 T2 ON T1.CardCode = T2.CardCode WHERE T1.CardCode = $NumCte";
	$resultCte = mssql_query($queryCte);
	$rowCte = mssql_fetch_array($resultCte);
	
	//$NumCte = $rowCte["CardCode"];
	$NomCte = $rowCte["CradName"];
	$RFCCte = $rowCte["LicTradNum"];
	$DomCte = $rowCte["Street"].", ".$rowCte["Block"].", ".$rowCte["City"].", ".$rowCte["County"].", ".$rowCte["ZipCode"].", ".$rowCte["State"].", ".$rowCte["Country"];
	
	
    // Logo
    $this->Image("images/logo-alianza.png",20,20,70);
    // Arial bold 20
    $this->SetFont('Arial','B',20);
    // Move to the right
    $this->Cell(190,8,' ',0,1);
	$this->Cell(110);
    // Title
	
    //$this->SetTextColor(280, 215, 140); //Color Rojo, Verde, Azul
	$this->Cell(80,16,'Cotizacion',0,1,'R'); 
	$this->SetFont('Arial','B',8); //Titulos
	$this->Cell(110,5,$NomSuc,0,0,'L');
	$this->Cell(30,5,'Num de cotizacion',1,0,'C');
	$this->Cell(35,5,'Fecha de documento',1,0,'C');
	$this->Cell(15,5,'Pagina',1,1,'C');
	$this->Cell(110,5,$RFCSUc,0,0,'L');
	$this->SetFont('Arial','I',8);
	$this->Cell(30,5,$folioCot,1,0,'C');
	$this->Cell(35,5,$FechaDoc,1,0,'C');
	$this->Cell(15,5,$this->PageNo().'/{nb}',1,1,'C');
	$this->Cell(110,5,$DomSuc,0,0,'L');
	$this->SetFont('Arial','B',8); //Titulos
	$this->Cell(30,5,'Num de cliente',1,0,'C');
	$this->Cell(35,5,'RFC',1,0,'C');
	$this->Cell(15,5,'T.C.',1,1,'C');
	$this->Cell(110);
	$this->SetFont('Arial','I',8);
	$this->Cell(30,5,$NumCte,1,0,'C');
	$this->Cell(35,5,$RFCCte,1,0,'C');
	$this->Cell(15,5,'$'.$TC,1,1,'C');
	$this->SetFont('Arial','B',8); //Titulos
	$this->Cell(110,5,'Datos Fiscales',0,0,'L');
	$this->Cell(80,5,'Vendedor',1,1,'C');
	$this->Cell(110,5,$cliente,0,0,'L');
	$this->SetFont('Arial','I',8);
	$this->Cell(80,5,$Empleado,1,1,'L');
	$this->Cell(110,5,$DomCte,0,1,'L');
	//$this->Cell(110);
	//$this->Cell(80,5,'Domicilio Fiscal',1,1,'C');
	//$this->Cell(110);
	//$this->Cell(80,5,$DomCte,1,1,'L');
	$this->Cell(190,5,' ',0,1);			
	
	//Lineas de Cotización 
	$this->SetFont('Arial','B',8); //Titulos
	$this->Cell(30,6,'Codigo',1,0,'C');
	$this->Cell(55,6,'Descripcion',1,0,'C');
	$this->Cell(15,6,'Cantidad',1,0,'C');
	$this->Cell(25,6,'Precio de Lista',1,0,'C');
	$this->Cell(15,6,'Descuento',1,0,'C');
	$this->Cell(25,6,'Precio Unitario',1,0,'C');
	$this->Cell(25,6,'Total',1,1,'C');
	$this->SetFont('Arial','I',8); //Texto simple
	
	$i = 0;
	
	while($i < $rowCoti = mysql_fetch_array($resultCoti)){
		$CodArt = $rowCoti["Codigo_Art"];
		$NomArt = $rowCoti["Nombre_Art"];
		$Cant = $rowCoti["Cantidad"]." ".$rowCoti["UMV"];
		$P_Lista = $rowCoti["Precio_Lista"]." ".$rowCoti["Moneda"];
		$Desc = $rowCoti["Factor"];
		$P_Unitario = $rowCoti["Precio_Unidad"]." ".$rowCoti["Moneda"];
		$Subtotal_Linea = $rowCoti["Sub_Tot_Line"]." ".$rowCoti["Moneda"];	
					
		$this->Cell(30,5,$CodArt,1,0,'C');
		$this->MultiCell(55,5,$NomArt,1, 'L', false);
		$this->Cell(15,5,$Cant,1,0,'C');
		$this->Cell(25,5,$P_Lista,1,0,'C');
		$this->Cell(15,5,$Desc.' %',1,0,'C');
		$this->Cell(25,5,$P_Unitario,1,0,'C');
		$this->Cell(25,5,$Subtotal_Linea,1,1,'C');
		
		$i++;
	}	
	
		
	//Importes totales de Cotización	
	//Total MXN
	
	$IVAMXN = ($SubtotalDocMXN * 0.16);
	$TotalMXN = ($SubtotalDocMXN + $IVAMXN);
	
	$this->Cell(190,5,' ',0,1);
	$this->Cell(140);
	$this->SetFont('Arial','B',8); //Titulos
	$this->Cell(50,5,'Total de partidas en MN',1,2,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(25,5,'Subtotal:',1,0,'L');
	$this->Cell(25,5,'$'.$SubtotalDocMXN,1,1,'R');
	$this->Cell(140);
	$this->Cell(25,5,'I.V.A.:',1,0,'L');
	$this->Cell(25,5,'$'.$IVAMXN,1,1,'R');
	$this->Cell(140);
	$this->Cell(25,5,'Total:',1,0,'L');
	$this->Cell(25,5,'$'.$TotalMXN,1,1,'R');
	
	//Total USD
	
	$IVAUSD = ($SubtotalDocUSD * 0.16);
	$TotalUSD = ($SubtotalDocUSD + $IVAUSD);
	
	$this->Cell(190,5,' ',0,1);
	$this->Cell(140);
	$this->SetFont('Arial','B',8); //Titulos
	$this->Cell(50,5,'Total de partidas en USD',1,2,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(25,5,'Subtotal:',1,0,'L');
	$this->Cell(25,5,'$'.$SubtotalDocUSD,1,1,'R');
	$this->Cell(140);
	$this->Cell(25,5,'I.V.A.:',1,0,'L');
	$this->Cell(25,5,'$'.$IVAUSD,1,1,'R');
	$this->Cell(140);
	$this->Cell(25,5,'Total:',1,0,'L');
	$this->Cell(25,5,'$'.$TotalUSD,1,1,'R');	
	
	//Total de Documento en MXN
	$this->Cell(190,5,' ',0,1);	
	$SubtotalDoc3 = ($TotalDoc / 1.16);
	$IVA3 = ($TotalDoc - $SubtotalDoc3);
	
	$this->Cell(140);
	$this->SetFont('Arial','B',8); //Titulos
	$this->Cell(50,5,'Total MN con TC Actual',1,2,'C');
	$this->Cell(25,5,'Subtotal:',1,0,'L');
	$this->SetFont('Arial','I',8);
	$this->Cell(25,5,'$'.$SubtotalDoc3,1,1,'R');
	$this->Cell(140);
	$this->SetFont('Arial','B',8); //Titulos
	$this->Cell(25,5,'I.V.A.:',1,0,'L');
	$this->SetFont('Arial','I',8);
	$this->Cell(25,5,'$'.$IVA3,1,1,'R');
	$this->Cell(140);
	$this->SetFont('Arial','B',9); //Titulos
	$this->Cell(25,5,'Total:',1,0,'L');
	$this->Cell(25,5,'$'.$TotalDoc,1,1,'R');
	
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
	$folioCot = $_REQUEST["idcot"];
	$query1 = "SELECT Id_Cot1, Codigo_SN, Comentarios FROM COTI WHERE Id_Cot1 = $folioCot";
	$result1 = mysql_query($query1);
	$row1 = mysql_fetch_array($result1);
	
	$Comentarios= $row1["Comentarios"];	
	
	$querySuc = "SELECT T1.CompnyName, T1.TaxIdNum, T2.IntrntAdrs, T1.Phone1, T1.Phone2, T1.E_Mail FROM OADM T1 INNER JOIN ADM1 T2 ON T1.Code = T2.Code WHERE T1.Code = 1";
	$resultSuc = mssql_query($querySuc);
	$rowSuc = mssql_fetch_array($resultSuc);
	
	$NomSuc = $rowSuc["CompnyName"];
	$TelSuc = $rowSuc["Phone1"].", ".$rowSuc["Phone2"];
	$EmailSuc = $rowSuc["E_Mail"];
	$PWeb = $rowSuc["IntrntAdrs"];
	
	/*/Prueba	
	$this->SetTextColor(255, 255, 255);
	$this->MultiCell(100,6,'Prueba aaaaa aaaaaaaa aaaaaaaaaa aaaaaaaaaaaaaaaa aaaaaaaaaaa aaaaaaaaaaaaaaa',1,0,'R',true);
	$this->Cell(80,5,'Prueba',1,1,'C',0);	
	//$this->Text(0,100,'Direccion de Administracion y Servicios',1,'L',0);
	//Fin Prueba*/
	
	
    // Position at 1.5 cm from bottom
    $this->SetY(-50);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
	$this->Cell(30,6,'Comentarios: ',0,1,'L');
	$this->MultiCell(190, 5, $Comentarios, 1, 1, 'L', false);
	$this->Cell(63,6,'Telefonos: '.$TelSuc, 0,0,'L');
	$this->Cell(63,6,'Sitio Web: '.$PWeb, 0,0,'L');
	$this->Cell(63,6,'Sitio Web: '.$EmailSuc, 0,1,'L');
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->Output();

?>