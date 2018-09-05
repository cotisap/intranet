<?php
include "../mssqlconn.php";

$empID = $_POST["empID"];
$telephone = $_POST["telephone"];
$extension = $_POST["extension"];
$email = $_POST["email"];
$password = $_POST["password"];
$commission = $_POST["commission"];
$priceList = $_POST["priceList"];
$wareHouse = $_POST["wareHouse"];
if(isset($_POST["export"])) {
	$export = 'Y';
} else {
	$export = 'N';
}
if(isset($_POST["discounts"])) {
	$discounts = 'Y';
} else {
	$discounts = 'N';
}

$query = "UPDATE OSLP SET Telephone = '".$telephone."', Fax = '".$extension."', Email = '".$email."', U_pass = '".$password."', Commission = ".$commission.", U_priceList = '".$priceList."', U_branch = '".$wareHouse."', U_export = '".$export."', U_discounts = '".$discounts."' WHERE SlpCode = ".$empID;
$result = mssql_query($query);

if(!$result) {
	die('Could not edit emp details: ' . mssql_error());
}

?>