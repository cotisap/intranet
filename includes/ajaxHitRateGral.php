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

				//Total de ventas
		$query = "SELECT COUNT(*) AS Total FROM COTI WHERE (st = 1 OR st = 3)";

		$result = mysql_query($query);
		while ($rowF = mysql_fetch_array($result)){
			array_push($data, [
						'Ventas' => $rowF["Total"]
					]);
		}
				//Total de cotizaciones
		$query = "SELECT COUNT(*) AS Total FROM COTI";
		$result = mysql_query($query);
		while ($rowF = mysql_fetch_array($result)){
			array_push($data, [
						'Cotizaciones' => $rowF["Total"]
					]);
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

	public function getCotizaciones( ){
				//Total de ventas
		return json_encode(self::exeQuery(null));
	}

}

$cotizaciones = new BuscarCotizaciones();

echo $cotizaciones->getCotizaciones();