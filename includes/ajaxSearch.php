<?php
/**
 * Archivo que filtra las cotizaciones y realiza un find 
 *
 * @version v0.1
 * @author sergiomireles.com 
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

include 'mysqlconn.php';

$id = $_GET['q'];
$date = $_GET['date'];
$empl = $_SESSION["salesPrson"];

/**
*  Buscar cotizaciones 
*/

class BuscarCotizaciones
{

	/**
	 * Ejecuta la consulta en la Base de datos
	 *
	 * @param String Query
	 * @return Array Data
	 */

	public function exeQuery($query){

		$data = array();

		$result = mysql_query($query);
			while ($rowF = mysql_fetch_array($result)) {	

				array_push($data,
							['id' => $rowF["Id_Cot1"],'text' => $rowF["Id_Cot1"]." - ".$rowF["CardName"]]
					);
				
			}

		return $data;
	}
	
	/**
	 * Regresa todos los regitros en JSON 
	 *
	 * @param 
	 * @return JSON Data
	 */

	public function getCotizaciones($id, $date, $empl){

		if (strcmp($date, 'Todos')) {
			$dates = explode('-', $date); 	 
			$sqlDates = "AND FechaCreacion BETWEEN '". $dates[0] ."' AND '". $dates[1] ."'";
		} else {
			$sqlDates = "";
		}

		if ($_SESSION["admin"] == 'Y' || $isManager) {
			$queryF = "SELECT Id_Cot1, CardName FROM COTI ".
						"WHERE company = '".$_SESSION["company"]."' AND ( Id_Cot1 LIKE '%".$id."%' OR CardName LIKE '%".$id."%' ) ".$sqlDates." ORDER BY Id_Cot1 DESC";
		} else {
			$queryF = "SELECT Id_Cot1, CardName FROM COTI ".
			 			"WHERE Empl_Ven = '$empl' AND ( Id_Cot1 LIKE '%".$id."%' OR  CardName LIKE '%".$id."%' ) ".$sqlDates." ORDER BY Id_Cot1 DESC";
		}

		return json_encode(self::exeQuery($queryF));
	}

}


$cotizaciones = new BuscarCotizaciones();

echo $cotizaciones->getCotizaciones($id, $date, $empl);