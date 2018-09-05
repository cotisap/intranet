<?php
session_start();
include "mysqlconn.php";

$title = $_POST["sTitle"];
$link = $_POST["sLink"];
$remarks = $_POST["remarks"];
if ($_POST["sActive"] == "Y") {
	$active = "Y";
} else {
	$active = "N";
}
if ($_POST["sClient"] == "Y") {
	$client = "Y";
} else {
	$client = "N";
}

if(isset($_FILES['sFile']) && $_FILES['sFile']['size'] > 0) {
	// Configuration - Your Options
	$allowed_filetypes = array('.jpg','.png'); // These will be the types of file that will pass the validation.
	$max_filesize = 500000; // Maximum filesize in BYTES (currently 500KB).
	$upload_path = '../ftp/slider/'; // The place the files will be uploaded to (currently a 'files' directory).

	$filename = $_FILES['sFile']['name']; // Get the name of the file (including file extension).
	$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.

	// Check if the filetype is allowed, if not DIE and inform the user.
	if(!in_array($ext,$allowed_filetypes))
	  die('The file you attempted to upload is not allowed.');

	// Now check the filesize, if it is too large then DIE and inform the user.
	if(filesize($_FILES['sFile']['tmp_name']) > $max_filesize)
	  die('The file you attempted to upload is too large.');

	// Check if we can upload to the specified path, if not DIE and inform the user.
	if(!is_writable($upload_path))
	  die('You cannot upload to the specified directory, please CHMOD it to 777.');

	// Upload the file to your specified path.
	if(move_uploaded_file($_FILES['sFile']['tmp_name'],$upload_path . $filename)) {
		//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
	} else {
		 echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
	}
}

$query = "INSERT INTO SLDR (id, title, link, file, active, for_customer, emp_id, created_at, remarks, company) VALUES ('', '$title', '$link', '$filename', '$active', '$client', ".$_SESSION["salesPrson"].", now(), '$remarks', '".$_SESSION["company"]."')";
$result = mysql_query($query);

if(!$result) {
	die('Could not enter data Slide: ' . mysql_error());
}
header('Location: /slideradmin.php?msg=slidesuccess');

?>