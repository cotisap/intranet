<?php
session_start();
include "mysqlconn.php";

$title = $_POST["title"];
$category = $_POST["category"];
$remarks = $_POST["remarks"];



// Configuration - Your Options
  $allowed_filetypes = array('.jpg','.png','.pdf','.xls','.ppt','.pptx','.doc','.xlsx','.docx'); // These will be the types of file that will pass the validation.
  $max_filesize = 10000000; // Maximum filesize in BYTES (currently 10MB).
  $upload_path = '../ftp/capacitacion/'; // The place the files will be uploaded to (currently a 'files' directory).

$filename = $_FILES['file']['name']; // Get the name of the file (including file extension).
$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.

// Check if the filetype is allowed, if not DIE and inform the user.
if(!in_array($ext,$allowed_filetypes))
  die('The file you attempted to upload is not allowed.');

// Now check the filesize, if it is too large then DIE and inform the user.
if(filesize($_FILES['file']['tmp_name']) > $max_filesize)
  die('The file you attempted to upload is too large.');

// Check if we can upload to the specified path, if not DIE and inform the user.
if(!is_writable($upload_path))
  die('You cannot upload to the specified directory, please CHMOD it to 777.');

// Upload the file to your specified path.
if(move_uploaded_file($_FILES['file']['tmp_name'],$upload_path . $filename)) {
	$query = "INSERT INTO TRNG (id, title, category, file, active, emp_id, created_at, remarks) VALUES ('', '$title', '$category', '$filename', 'Y', ".$_SESSION["salesPrson"].", now(), '$remarks')";
	$result = mysql_query($query);
	
	if(!$result) {
		die('Could not enter data Tool: ' . mysql_error());
	}
	header('Location: /intranetadmin.php?admin=capacitacion&msg=toolsuccess');
	//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
} else {
	 echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
}

?>