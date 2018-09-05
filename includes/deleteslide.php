<?php
session_start();
include "mysqlconn.php";

$slCode = $_GET["slCode"];
$filename = $_POST['filename'];

$delete_path = '../ftp/slider/';
	
$query = "DELETE from SLDR WHERE id = $slCode";  
$result = mysql_query($query);
	
if(!$result) {
		die('Could not enter data Slide: ' . mysql_error());
}

unlink($delete_path.$filename);

header('Location: /slideradmin.php?msg=slidesuccess');
?>