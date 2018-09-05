<?php
/**
 * Archivo que filtra las Vendedores y realiza un find 
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

include 'mssqlconn.php';


/**
*  Buscar Vendedores 
*/

class BuscarVendedores
{

	/**
	 * Ejecuta la consulta en la Base de datos
	 *
	 * @param String Query
	 * @return Array Data
	 */

	public function exeQuery($query){

		$data = array();

		$result = mssql_query($query);
			while ($rowF = mssql_fetch_array($result)){
				array_push($data,
							['SlpCode' => $rowF["SlpCode"],
							'SlpName' => @mb_convert_encoding($rowF["SlpName"], "UTF-8", "auto")
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

	public function getVendedores( $SLP ){
		$queryF = "SELECT SlpName, SlpCode, U_manager FROM OSLP WHERE (U_Manager = $SLP AND SlpName NOT LIKE '%lvarez%') OR (U_branch = (SELECT U_branch FROM OSLP WHERE SlpCode = '$SLP') AND SlpName NOT LIKE '%lvarez%') ORDER BY 1";
		//$queryF = "SELECT * FROM OSLP WHERE U_admin != NULL ORDER BY 1";
		//$queryF = "SELECT * FROM OSLP ORDER BY 1";
		return json_encode(self::exeQuery($queryF));
	}

}

if(isset($_POST['idManager']))
	$slp = $_POST['idManager'];
else
	$slp = $_SESSION['salesPrson'];
$vendedores = new BuscarVendedores();

echo $vendedores->getVendedores( $slp );