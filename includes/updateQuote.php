<?php
session_start();
include "mysqlconn.php";
include "mssqlconn.php";
	
//Empleado
$Empl_Ven = $_POST["Empl_Ven"];
$mail= $_SESSION["email"];
$company = $_POST["company"];

//Cliente
$bpCode = $_POST["bpCode"];
$bpName = $_POST["bpName"];

//Cotizacion
$idCot = $_POST["idCot"];
$DocCur = $_POST["DocCur"];
$comment = $_POST["remarks"];
$DiscPrcnt = $_POST["gDiscP"];
$DocTotal = $_POST["DocTotal"];
$account = $_POST["account"];
$comNots = $_POST["comNots"];
$tax = $_POST["ivaP"];

// Datos de entrega
$dlvType = $_POST["dlvType"];
$dlvPerson = $_POST["dlvPerson"];
$dlvBranch = $_POST["dlvBranch"];
$dlvPhone = $_POST["dlvPhone"];
$dlvEmail = $_POST["dlvEmail"];
$dlvAddress = $_POST["dlvAddress"];
$dlvFlet = $_POST["dlvFlet"];

//echo "CNOTES: ".$comNots;
//exit;
mysql_query("SET NAMES 'utf8'");
//insert Cotizacion	
$queryC = "REPLACE INTO COTI (Codigo_SN, CardName, FechaCreacion, DiscPrcnt, Total_Doc, Comentarios, Empl_Ven, Id_Cot1, status, account, cnotes, dlvType, dlvPerson, dlvPhone, dlvEmail, dlvAddress, dlvFlet, company, tax, DocCur) VALUES ('$bpCode', '$bpName', now(), '$DiscPrcnt', '$DocTotal', '$comment', '$Empl_Ven', '$idCot', 'Q', '$account', '$comNots', '$dlvType', '$dlvPerson', '$dlvPhone', '$dlvEmail', '$dlvAddress', '$dlvFlet', '$company', '$tax', '$DocCur')";
$resultC = mysql_query($queryC);

if(!$resultC) {
	die('Could not enter data COT: ' . mysql_error());
}
	
$queryD = "DELETE FROM COT1 WHERE Id_Cot1 = '$idCot'";

$resultD = mysql_query($queryD);
if(!$resultD) {
	die('Could not delete data LIN: ' . mysql_error());
}
	
//insert Lineas
foreach ($_POST['itemCode'] as $key => $value) {
	$lineNum = $_POST["lineNumT"][$key];
	$codArt = $_POST["itemCode"][$key];
	$prodName = str_replace("'", "\'", $_POST["itemName"][$key]);
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
	
	$queryL = "INSERT INTO COT1 (Id_Cot1, lineNum, Codigo_Art, Nombre_Art, FirmName, Cantidad, Moneda, Precio_Lista, Precio_Unidad, Factor, DiscPrcnt, Sub_Tot_Line, FechaCreacion, UMV, TiempoEntrega, remarks) VALUES ('$idCot', '$lineNum', '$codArt', '$prodName', '$FirmName', '$quant', '$moneda', '$price', '$price_uni', '$desc', '$lineDisc', '$importe', now(), '$uniMed', '$entrega', '$lineRemark')";

	$resultL = mysql_query($queryL);
	if(!$resultL) {
		die('Could not enter data LIN: ' . mysql_error());
	}
}

// Save payments
$allowed_filetypes = array('.jpg','.png','.pdf'); // These will be the types of file that will pass the validation.
$max_filesize = 1000000; // Maximum filesize in BYTES (currently 1MB).
$upload_path = '../ftp/pagos/'; // The place the files will be uploaded to (currently a 'files' directory).

if (count($_FILES["file"]) > 0) {
	foreach ($_FILES['file']['name'] as $key => $value) {
		$pMethod = $_POST["pMethod"][$key];
		$ammount = $_POST["ammount"][$key];
		$pCurrency = $_POST["pCurrency"][$key];
		$pRef = $_POST["ref"][$key];
		$pDate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST["pDate"][$key])));
		
		$filename = $_FILES['file']['name'][$key]; // Get the name of the file (including file extension).
		$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
		
		// Check if the filetype is allowed, if not DIE and inform the user.
		if(!in_array($ext,$allowed_filetypes))
		  die('The file you attempted to upload is not allowed.');
		
		// Now check the filesize, if it is too large then DIE and inform the user.
		if(filesize($_FILES['file']['tmp_name'][$key]) > $max_filesize)
		  die('The file you attempted to upload is too large.');
		
		// Check if we can upload to the specified path, if not DIE and inform the user.
		if(!is_writable($upload_path))
		  die('You cannot upload to the specified directory, please CHMOD it to 777.');
		
		$newName = $idCot."-".round(microtime(true)).$filename; 
		
		// Upload the file to your specified path.
		if(move_uploaded_file($_FILES['file']['tmp_name'][$key],$upload_path . $newName)) {
			$queryP = "INSERT INTO PMNT (ref_q, ref_p, method, ammount, currency, date, file, status) VALUES ('$idCot', '$pRef', '$pMethod', $ammount, '$pCurrency', '$pDate', '$newName', 'W')";
			
			$resultP = mysql_query($queryP);
			if(!$resultP) {
				die('Could not enter data PMNT: ' . mysql_error());
			}
			//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
		} else {
			 echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
		}
	}
}

header("Location: /vercotizacion.php?idCot=".$idCot."&msg=cotupsuccess");

?>