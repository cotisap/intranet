<?php
$value="";
$Con_Pago= $_POST["elegidoc"];

if ($_POST["elegidoc"]==11) {
	$value.='value="0"';
}
if ($_POST["elegidoc"]==12) {
}

echo $value;
?>