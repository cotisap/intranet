<?php
//include_once 'functions.php';

session_start();
$location = "";
if($_SESSION["isBP"]) {
	$location = "../clientes.php";
} else {
	$location = "../index.php";
}
// Unset all session values 
$_SESSION = array();
 
// get session parameters 
//$params = session_get_cookie_params();
 
// Delete the actual cookie. 
//setcookie(session_name(),
//        '', time() - 42000, 
//        $params["path"], 
//        $params["domain"], 
//        $params["secure"], 
//        $params["httponly"]);
 
// Destroy session 
session_destroy();

header('Location: '.$location);