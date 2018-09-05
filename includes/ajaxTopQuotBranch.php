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

class BuscarCotizacionesSucursal{

	public function shortify($str){
		if(strlen($str) > 15){
			return substr($str, 0, 15)."...";
		}else return $str;
	}

	public function getCotizaciones($slp){
		/*Obtiene SLPCodes de vendedores que tengan el mismo whscode que el gerente ($slp)*/
		$querySAP = "SELECT SlpCode FROM OSLP WHERE U_branch = (SELECT U_branch FROM OSLP WHERE SlpCode = '$slp')";
		$result = mssql_query($querySAP);

		$slp_codes = [];

		while($rowF = mssql_fetch_array($result)){
			$slp_codes []= $rowF['SlpCode'];
		}

		/*Dados los SlpCodes, los busca en MySQL para obtener las cotizaciones mas altas de ellos*/
		$in = implode(',', $slp_codes); // '1','2','3','4','5','7','8'
		$queryMySQL = "SELECT Id_Cot1, CardName, Total_MN, st FROM COTI WHERE Empl_Ven IN ($in) AND Total_MN > 100000 ORDER BY 3 DESC LIMIT 10";

		$data = array();

		$result = mysql_query($queryMySQL);
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
}


$cotizaciones = new BuscarCotizacionesSucursal();
$slp = $_SESSION['salesPrson'];

header('Content-Type: application/json');
echo json_encode($cotizaciones->getCotizaciones($slp));