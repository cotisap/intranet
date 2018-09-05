<?php
session_start();
include "mysqlconn.php";

$slCode = $_GET["slCode"];

$title = $_POST["uTitle"];
$link = $_POST["uLink"];
$remarks = $_POST["uRemarks"];
if ($_POST["uActive"] == "Y") {
	$active = "Y";
} else {
	$active = "N";
}
if ($_POST["uClient"] == "Y") {
	$client = "Y";
} else {
	$client = "N";
}


// Configuration - Your Options
$allowed_filetypes = array('.jpg','.png'); // These will be the types of file that will pass the validation.
$max_filesize = 500000; // Maximum filesize in BYTES (currently 500KB).
$upload_path = '../ftp/slider/'; // The place the files will be uploaded to (currently a 'files' directory).




if($_FILES["uFile"]["error"] != 4) {
	$filename = $_FILES['uFile']['name']; // Get the name of the file (including file extension).
	$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
	
	// Check if the filetype is allowed, if not DIE and inform the user.
	if(!in_array($ext,$allowed_filetypes))
	  die('The file you attempted to upload is not allowed.');
	
	// Now check the filesize, if it is too large then DIE and inform the user.
	if(filesize($_FILES['uFile']['tmp_name']) > $max_filesize)
	  die('The file you attempted to upload is too large.');
	
	// Check if we can upload to the specified path, if not DIE and inform the user.
	if(!is_writable($upload_path))
	  die('You cannot upload to the specified directory, please CHMOD it to 777.');
	
	// Upload the file to your specified path.
	if(move_uploaded_file($_FILES['uFile']['tmp_name'],$upload_path . $filename)) {
		
		//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
	} else {
		 echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
	}
	
	$query = "REPLACE INTO SLDR (id, title, link, file, active, for_customer, emp_id, created_at, remarks) VALUES ($slCode, '$title', '$link', '$filename', '$active', '$client', ".$_SESSION["salesPrson"].", now(), '$remarks')";
	$result = mysql_query($query);
	
	if(!$result) {
		die('Could not enter data Slide: ' . mysql_error());
	}
} else {
	$query = "UPDATE SLDR SET title = '$title', link = '$link', active = '$active', for_customer = '$client', emp_id = ".$_SESSION["salesPrson"].", created_at = now(), remarks = '$remarks' WHERE id = $slCode";
	$result = mysql_query($query);
	
	if(!$result) {
		die('Could not enter data Slide: ' . mysql_error());
	}
}

header('Location: /slideradmin.php?msg=slidesuccess');
?>