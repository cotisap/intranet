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

		$result = mysql_query($query);
			while ($rowF = mysql_fetch_array($result)){
				array_push($data,
							['CardName' => $rowF["CardName"],'Cantidad' => $rowF["Cantidad"] ]
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

	public function getCotizaciones(){
		$queryF = "SELECT CardName, COUNT(*) AS Cantidad FROM COTI where company LIKE '%$company%' GROUP BY CardName ORDER BY Cantidad DESC LIMIT 10";
		return json_encode(self::exeQuery($queryF));
	}

}


$cotizaciones = new BuscarCotizaciones();

echo $cotizaciones->getCotizaciones();