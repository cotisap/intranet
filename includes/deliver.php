<?php
session_start();
include "mysqlconn.php";

$RefNum = $_POST["numAtCard"];
$DocNum = $_POST["docNum"];
$CardCode = $_POST["CardCode"];
$CardName = $_POST["CardName"];
$DocDate = $_POST["DocDate"];
$CntPerson = $_POST["CntPerson"];
$CntPhone = $_POST["CntPhone"];
$DlvPerson = $_POST["DlvPerson"];
$DlvPhone = $_POST["DlvPhone"];
$DlvIdType = $_POST["DlvIdType"];
$EmpId = $_SESSION["salesPrson"];
$remarks = $_POST["remarks"];

// Configuration - Your Options
$allowed_filetypes = array('.jpg','.jpeg','.png','.pdf','.doc','.docx','.JPG','.JPEG','.PNG','.PDF','.DOC','.DOCX'); // These will be the types of file that will pass the validation.
$max_filesize = 5000000; // Maximum filesize in BYTES (currently 5MB).
$upload_path = '../ftp/entregas/'; // The place the files will be uploaded to (currently a 'files' directory).

$filename = $_FILES['file']['name']; // Get the name of the file (including file extension).
$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.

// Check if the filetype is allowed, if not DIE and inform the user.
if(!in_array($ext,$allowed_filetypes))
  die('El formato de archivo que intentas usar no est&aacute; permitido. Sólo JPG, JPEG, PNG, PDF, DOC y DOCX.');

// Now check the filesize, if it is too large then DIE and inform the user.
if(filesize($_FILES['file']['tmp_name']) > $max_filesize)
  die('El peso del archivo que intentas usar es mayor al permitido de 5MB.');

// Check if we can upload to the specified path, if not DIE and inform the user.
if(!is_writable($upload_path))
  die('You cannot upload to the specified directory, please CHMOD it to 777.');

// Upload the file to your specified path.
if(move_uploaded_file($_FILES['file']['tmp_name'],$upload_path . $filename)) {
	$query = "INSERT INTO DLVS (id, RefNum, DocNum, CardCode, CardName, DocDate, CntPerson, CntPhone, DlvPerson, DlvPhone, DlvIdType, DlvId, DateCreated, EmpId, remarks) VALUES ('', '$RefNum', '$DocNum', '$CardCode', '$CardName', '$DocDate', '$CntPerson', '$CntPhone', '$DlvPerson', '$DlvPhone', '$DlvIdType', '$filename', now(), '$EmpId', '$remarks')";
	$result = mysql_query($query);
	
	if(!$result) {
		die('Could not enter data DLVS: ' . mysql_error());
	}
	header("Location: /entregar.php?idDLV=".$DocNum."&msg=dlvsuccess");
	//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
} else {
	 echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
}

?>