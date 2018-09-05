<?php
$host = 'idited.com';
$dbname = 'idited_foliumqa';
$user = 'idited_foliumqa';
$psw = 'Folium1*';


header('Content-Type: application/json');

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $psw);
try{
	$stm = $pdo->prepare('UPDATE COTI SET FechaCierre = :date, ComentarioCierre = :comm, st = 2 WHERE Id_Cot1 = :id');

	$date = new DateTime($_GET['hold_date']);
	$hold_date = $date->format('Y-m-d');
	$stm->bindParam(':id', $_GET['num_cot'], PDO::PARAM_INT);
	$stm->bindParam(':date', $hold_date, PDO::PARAM_STR);
	$stm->bindParam(':comm', $_GET['comm'], PDO::PARAM_STR);
	$stm->execute();

	echo json_encode([
		'status' => true
	]);
}catch(PDOException $ex){
	echo json_encode([
		'status' => false,
		'message' => $ex->getMessage()
	]);
}		

