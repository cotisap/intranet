<?php
session_start();

include "../mysqlconn.php";

$filename = $_FILES['file']['name'];

// Configuration - Your Options
$allowed_filetypes = array('.xls','.xlsx','.XLS','.XLSX'); // These will be the types of file that will pass the validation.
$max_filesize = 20000000; // Maximum filesize in BYTES (currently 20MB).
$upload_path = '../../ftp/pList/'; // The place the files will be uploaded to (currently a 'files' directory).

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
	$query = "INSERT INTO PLST (id, file, empl, dateCreated) VALUES ('', '$filename', ".$_SESSION["salesPrson"].", now())";
	$result = mysql_query($query);
	
	if(!$result) {
		die('Could not enter data PLST: ' . mysql_error());
	}
	header('Location: /genadmin.php?msg=filesuccess');
} else {
	 echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
}

?>