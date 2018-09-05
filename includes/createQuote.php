<?php
session_start();
include "mysqlconn.php";
include "mssqlconn.php";
	
//Empleado
$empl = $_SESSION["salesPrson"];
$mail= $_SESSION["email"];

//Cliente
$bpCode = $_POST["CardCode"];
$bpName = $_POST["CardName"];

//Cotizacion
$DocCur = $_POST["DocCur"];
$comment = $_POST["remarks"];
$DiscPrcnt = $_POST["gDiscP"];
$DocTotal = $_POST["DocTotal"];
$account = $_POST["account"];
$comNots = $_POST["comNots"];
$company = $_SESSION["company"];
$tax = $_POST["ivaP"];

// Datos de entrega
$dlvType = $_POST["dlvType"];
$dlvPerson = $_POST["dlvPerson"];
$dlvPhone = $_POST["dlvPhone"];
$dlvEmail = $_POST["dlvEmail"];
$dlvAddress = $_POST["dlvAddress"];
$dlvFlet = $_POST["dlvFlet"];

//ID
$numQuery = "SELECT Id_Cot1 FROM COTI ORDER BY Id_Cot1 DESC LIMIT 1";
$resultID = mysql_query($numQuery);
if (!$resultID)
{
	die('Could not read data: ' . mysql_error());
} else {
	$rowID = mysql_fetch_assoc($resultID);	
	$Id = $rowID["Id_Cot1"] + 1;
}

mysql_query("SET NAMES 'utf8'");

//insert Cotizacion
$queryC = "INSERT INTO COTI (Codigo_SN, CardName, FechaCreacion, DiscPrcnt, Total_Doc, Comentarios, Empl_Ven, Id_Cot1, status, account, cnotes, dlvType, dlvPerson, dlvPhone, dlvEmail, dlvAddress, dlvFlet, company, tax, DocCur) VALUES ('$bpCode', '$bpName', now(), $DiscPrcnt, $DocTotal, '$comment', '$empl', '$Id', 'Q', '$account', '$comNots', '$dlvType', '$dlvPerson', '$dlvPhone', '$dlvEmail', '$dlvAddress', '$dlvFlet', '$company', $tax, '$DocCur')";
	
$resultC = mysql_query($queryC);
	
if(!$resultC) {
	die('Could not enter data COT: ' . mysql_error());
}
	
//insert Lineas
foreach ($_POST['itemCode'] as $key => $value)
	{
		$lineNum = $_POST["lineNumT"][$key];
		$codArt = $_POST["itemCode"][$key];
		$prodName = utf8_encode(str_replace("'", "\'", $_POST["itemName"][$key]));
		$quant = $_POST["quant"][$key];
		$price = $_POST["listPrice"][$key];
		$desc = $_POST["disc"][$key];
		$lineDisc = $_POST["lineDisc"][$key];
		$price_uni = $_POST["finalPrice"][$key];
		$importe = $_POST["lineDiscPrice"][$key];
		$moneda = $_POST["currency"][$key];
		$entrega = $_POST["deliv"][$key];
		$uniMed = $_POST["umv"][$key];
		$FirmName = $_POST["FirmName"][$key];
		$lineRemark = $_POST["lineRemark"][$key];
		
		$queryL = "INSERT INTO COT1 (Id_Cot1, lineNum, Codigo_Art, Nombre_Art, FirmName, Cantidad, Moneda, Precio_Lista, Precio_Unidad, Factor, DiscPrcnt, Sub_Tot_Line, FechaCreacion, UMV, TiempoEntrega, remarks) VALUES ('$Id', '$lineNum', '$codArt', '$prodName', '$FirmName', '$quant', '$moneda', '$price', '$price_uni', '$desc', '$lineDisc', '$importe', now(), '$uniMed', '$entrega', '$lineRemark')";
		
		//echo $queryL;
		//exit;

		$resultL = mysql_query($queryL);
		if(! $resultL ) {
			die('Could not enter data LIN: ' . mysql_error());
		}
	}

header("Location: /cotizador.php?msg=cotsuccess&fquote=".$Id);
?>