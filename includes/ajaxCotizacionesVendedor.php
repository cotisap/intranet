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
$company = $_SESSION['company'];

include 'mysqlconn.php';
include 'mssqlconn.php';


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
	public function shortify($str){
		if(strlen($str) > 15){
			return substr($str, 0, 15)."...";
		}else return $str;
	}
	public function exeQuery($query){

		$data = array();

		$result = mysql_query($query);
			while ($rowF = mysql_fetch_array($result)){
				array_push($data,
							['CardName' => mb_convert_encoding( self::shortify( $rowF["CardName"]), "UTF-8", "auto"),
							'Total_MN' => $rowF["Total_MN"],
							'id' => $rowF["Id_Cot1"],
							'st' => $rowF["st"] ]
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
//SELECT distinct CardName, Total_MN FROM COTI where company = 'alianza' AND Total_MN > 100000 ORDER BY Total_MN ASC LIMIT 10;

	public function getCotizaciones( $SLP ){
		$queryF = "SELECT Id_Cot1, CardName, Total_MN, st FROM COTI WHERE Empl_Ven = $SLP AND Total_MN > 100000 ORDER BY 3 DESC LIMIT 10";
		return json_encode(self::exeQuery($queryF));
	}

}

if(isset($_POST['id'])){
	$slp = $_POST['id'];
}
else{
	$slp = $_SESSION['salesPrson'];
}
$cotizaciones = new BuscarCotizaciones();

echo $cotizaciones->getCotizaciones( $slp );