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


/**
*  Buscar cotizaciones 
*/

class Updater
{

	/**
	 * Ejecuta la consulta en la Base de datos
	 *
	 * @param String Query
	 * @return Array Data
	 */

	public function exeQuery($query){
		return mysql_query($query);
	}
	
	/**
	 * Regresa todos los regitros en JSON 
	 *
	 * @param 
	 * @return JSON Data
	 */
//SELECT distinct CardName, Total

	public function updateStatus( $status, $id ){
		//Productos vendidos por $slp TOP 10
		$queryF = "UPDATE ART1 SET st=$status WHERE Codigo_Art = '$id'";

		return json_encode(self::exeQuery($queryF));
	}

}

$id = $_POST["id"];
$status = $_POST["status"];
//$slp = 155;
$cotizaciones = new Updater();

echo $cotizaciones->updateStatus( $status, $id );