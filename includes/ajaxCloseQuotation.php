<?php
$host = 'idited.com';
$dbname = 'idited_foliumqa';
$user = 'idited_foliumqa';
$psw = 'Folium1*';


header('Content-Type: application/json');

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $psw);

$num_cot = $_GET["num_cot"];
$comments = $_GET["comments"];
$status = $_GET["status"];

try{
	if($status == 4){
		$stm = $pdo->prepare("UPDATE COTI SET st = :status, ComentarioCierre = :comm, razon = 'Otra', otro = :otro  WHERE Id_Cot1 = :id");
		$stm->bindParam(':id', $num_cot, PDO::PARAM_INT);
		$stm->bindParam(':comm', $comments, PDO::PARAM_STR);
		$stm->bindParam(':status', $status, PDO::PARAM_INT);
		$stm->bindParam(':otro', $comments, PDO::PARAM_STR);
		$stm->execute();

		
	}else{
		$stm = $pdo->prepare('UPDATE COTI SET st = :status, ComentarioCierre = :comm, razon = :razon WHERE Id_Cot1 = :id');
		$stm->bindParam(':id', $num_cot, PDO::PARAM_INT);
		$stm->bindParam(':comm', $comments, PDO::PARAM_STR);
		$stm->bindParam(':status', $status, PDO::PARAM_INT);
		$stm->bindParam(':razon', $comments, PDO::PARAM_STR);
		$stm->execute();
	}
	echo json_encode([
		'status' => true
	]);


}catch(PDOException $ex){
	echo json_encode([
		'status' => false,
		'message' => $ex->getMessage()
	]);
}		

