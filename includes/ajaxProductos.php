<?php
/**
 * Archivo que filtra las cotizaciones y realiza un find 
 *
 * @version v0.1
 * @author GerardoSteven
 */

header('Content-Type: text/html;charset=utf-8');

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
*  Buscar productos 
*/

class BuscarProductos
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
							['Codigo' => $rowF["Codigo_Art"],
							'Descripcion' => $rowF["Descripcion"],
							'UMV' => $rowF["UMV"],
							'precio' => $rowF["precio"],
							'moneda' => $rowF["moneda"],
							'company' => $rowF["company"]
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
//SELECT distinct CardName, Total_MN FROM COTI where company = 'alianza' AND Total_MN > 100000 ORDER BY Total_MN ASC LIMIT 10;

	public function getProductos($cmp){
		$queryF = "SELECT Codigo_Art, Descripcion, UMV, precio, moneda, company FROM ART1 WHERE company LIKE '%$cmp%'";
		//$queryF = "SELECT Codigo_Art, Descripcion, UMV, precio, moneda FROM ART1 LIMIT 10";
		
		return json_encode(self::exeQuery($queryF));
	}
	public function shortify($str){
		return substr($str, 0, 50)."...";
	}

}


$productos = new BuscarProductos();
echo $productos->getProductos( $company );