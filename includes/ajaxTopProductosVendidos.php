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
							['Codigo_Art' => $rowF["Codigo_Art"],
							'Nombre_Art' => mb_convert_encoding($rowF["Nombre_Art"], "UTF-8", "auto"),
							'Empl_Ven' => $rowF["Empl_Ven"],
							'Cantidad' => $rowF["CANTIDAD"]
							]
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
//SELECT distinct CardName, Total

	public function getCotizaciones( $SLP ){
		//Productos vendidos por $slp TOP 10
		$queryF = "SELECT c.Codigo_Art, c.Nombre_Art, cc.Empl_Ven, COUNT( * ) AS CANTIDAD FROM COT1 c, COTI cc WHERE c.Id_Cot1=cc.Id_Cot1 AND cc.Empl_Ven = $SLP GROUP BY 2 ORDER BY 4 DESC LIMIT 10";

		return json_encode(self::exeQuery($queryF));
	}

}

if(isset($_POST['id'])){
	$slp = $_POST['id'];
}
else{
	$slp = $_SESSION['salesPrson'];
}
//$slp = 155;
$cotizaciones = new BuscarCotizaciones();

echo $cotizaciones->getCotizaciones( $slp);