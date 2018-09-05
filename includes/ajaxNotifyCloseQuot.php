<?php
/**
 *
 * @version v0.1
 * @author Gerardo Cabello Acosta 
 */


session_start();

$timeout = 28800;

if(isset($_SESSION['timeout'])) {
    $duration = time() - (int)$_SESSION['timeout'];
    if($duration > $timeout) {
        session_destroy();
        session_start();
    }
}

$_SESSION['timeout'] = time();
$company = $_SESSION['company'];

include 'mysqlconn.php';
include 'mssqlconn.php';


/**
*  Buscar cotizaciones 
*/

class BuscarCotizaciones{

	public function shortify($str){
		if(strlen($str) > 15){
			return substr($str, 0, 15)."...";
		}else return $str;
	}

	public function getCotizaciones($rol, $slp){
		/*Cotizaciones con 15 días sin actualizacion y sin fecha de aplaza O cotizaciones cuya fecha de aplazo sea menor a hoy*/

		/*Query a Producción*/
		//ADMIN
		//$queryMySQL = "SELECT *, DATEDIFF(NOW(), CAST(last_update AS DATE)) AS 'Dias' FROM COTI WHERE ((DATEDIFF(NOW(), CAST(last_update AS DATE)) > 15 AND FechaCierre IS NULL) OR (CURDATE() >= FechaCierre)) AND st <> 3 AND st <> 4";

		//Query a QA
		//ADMIN
		if($rol == 'Y'){
			
			$queryMySQLinit = "SELECT SlpName, SlpCode, U_manager FROM OSLP WHERE U_Manager = '$slp' AND Active = 'Y' AND SlpCode > 0 AND Email <> '' ORDER BY SlpName";
			
			$resultInit = mssql_query($queryMySQLinit);

			if(mssql_num_rows($resultInit)== 0){
				$queryMySQL = "SELECT *, DATEDIFF(NOW(), CAST(last_update AS DATE)) AS 'Dias' FROM COTI WHERE ((DATEDIFF(NOW(), CAST(last_update AS DATE)) > 15 AND FechaCierre IS NULL) OR (CURDATE() >= FechaCierre)) AND st <> 3 AND st <> 4 LIMIT 200";	
			}
		 else{

			$slp_codes = [];

			while($rowF = mssql_fetch_array($resultInit)) {
		 
				$slp_codes []= "'".$rowF["SlpCode"]."'";
			}

			$queryMySQL = "SELECT *, DATEDIFF(NOW(), CAST(last_update AS DATE)) AS 'Dias' FROM COTI WHERE (((DATEDIFF(NOW(), CAST(last_update AS DATE)) > 15 AND FechaCierre IS NULL) OR (CURDATE() >= FechaCierre)) AND st <> 3 AND st <> 4) AND Empl_Ven IN (".implode(',', $slp_codes).") LIMIT 200";

			
		 }
				

		}else if($rol == 'W' && $rol == 'N' ){
			$queryMySQL = "SELECT *, DATEDIFF(NOW(), CAST(last_update AS DATE)) AS 'Dias' FROM COTI WHERE ((DATEDIFF(NOW(), CAST(last_update AS DATE)) > 15 AND FechaCierre IS NULL) OR (CURDATE() >= FechaCierre)) AND st <> 3 AND st <> 4 LIMIT 200";		
		}else{
			//VENDEDOR
			$queryMySQLinit = "SELECT SlpName, SlpCode, U_manager FROM OSLP WHERE U_Manager = '$slp' AND Active = 'Y' AND SlpCode > 0 AND Email <> '' ORDER BY SlpName";
			$resultInit = mssql_query($queryMySQLinit);
			if(mssql_num_rows($resultInit)== 0){
				$queryMySQL = "SELECT *, DATEDIFF(NOW(), CAST(last_update AS DATE)) AS 'Dias' FROM COTI WHERE (((DATEDIFF(NOW(), CAST(last_update AS DATE)) > 15 AND FechaCierre IS NULL) OR (CURDATE() >= FechaCierre)) AND st <> 3 AND st <> 4) AND Empl_Ven = '".$slp."' LIMIT 200";
			}else{
				$slp_codes = [];
				while($rowF = mssql_fetch_array($resultInit)) {
					$slp_codes []= "'".$rowF["SlpCode"]."'";
				}

				$queryMySQL = "SELECT *, DATEDIFF(NOW(), CAST(last_update AS DATE)) AS 'Dias' FROM COTI WHERE (((DATEDIFF(NOW(), CAST(last_update AS DATE)) > 15 AND FechaCierre IS NULL) OR (CURDATE() >= FechaCierre)) AND st <> 3 AND st <> 4) AND Empl_Ven IN (".implode(',', $slp_codes).") LIMIT 200";
			}


			//$queryMySQL = "SELECT *, DATEDIFF(NOW(), CAST(last_update AS DATE)) AS 'Dias' FROM COTI WHERE (((DATEDIFF(NOW(), CAST(last_update AS DATE)) > 15 AND FechaCierre IS NULL) OR (CURDATE() >= FechaCierre)) AND st <> 3 AND st <> 4) AND Empl_Ven = '".$slp."' LIMIT 200";	
		}


		

		$data = array();

		$result = mysql_query($queryMySQL);
		while ($rowF = mysql_fetch_array($result)){
			array_push($data,
						[
							'Id_Cot1' => utf8_encode($rowF["Id_Cot1"]),
							'ComentarioCierre' => utf8_encode($rowF["ComentarioCierre"]),
							'CardName' => empty($rowF["CardName"]) ? '- SIN CLIENTE -' : utf8_encode($rowF["CardName"]),
							'Total_Doc' => number_format($rowF["Total_Doc"], 2, '.', ','),
							'status' => $rowF["st"]
						]
			);
		}


		return $data;
	}
}


$cotizaciones = new BuscarCotizaciones();
header('Content-type: application/json');
echo json_encode($cotizaciones->getCotizaciones($_GET['rol'], $_SESSION['salesPrson']));
