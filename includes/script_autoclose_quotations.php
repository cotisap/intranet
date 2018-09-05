<?php
$host = 'idited.com';
$dbname = 'idited_foliumqa';
$user = 'idited_foliumqa';
$psw = 'Folium1*';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $psw);
$stm = $pdo->prepare("SELECT *, DATEDIFF(NOW(), CAST(last_update AS DATE)) AS 'Dias' FROM COTI WHERE DATEDIFF(NOW(), CAST(last_update AS DATE)) > 15");
if($stm->execute()){
	$mail = '<h2>Cotizaciones cerradas:</h2>';
	if($stm->rowCount() == 0 ){
		$mail = '<h2>No hay cotizaciones con más de 15 días sin modificaciones:</h2>';
	}else{
		$rs = $stm->fetchAll(PDO::FETCH_ASSOC);
		foreach( $rs as $q ){
			/*Close Quotation*/
			
			/*
			$stm = $pdo->prepare('UPDATE COTI SET st = 0 WHERE Id_Cot1 = :id');
			$stm->bindParam(':id', $q['Id_Cot1']);
			$stm->execute();
			*/
			

			$mail .= '<p>#'.$q['Id_Cot1'].'. Última act.: '.$q['last_update'].'. Díaz sin modificaciones: '.$q['Dias'].'</p>';
			print($q['Id_Cot1']."\n");
		}
	}
	mail('gcabello@mbrhosting.com', 'Reporte de cierre de cotizaciones', $mail, $headers);
}
