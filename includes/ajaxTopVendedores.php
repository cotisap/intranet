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

include 'mysqlconn.php';
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

	public function exeQuery($query, $SLP){


		$empl = array();
		$data = array();

		
		$MSquery = "SELECT SlpCode, SlpName FROM OSLP WHERE U_Manager = $SLP";
		$MSresult = mssql_query($MSquery);
		$in = "(";
		while ($MSrow = mssql_fetch_array($MSresult)){
			$in.=($MSrow["SlpCode"].",");
			array_push($empl, [
						'SlpCode' => $MSrow["SlpCode"],
						'SlpName' => $MSrow["SlpName"]
					]);
		}
		$in = trim($in, ",");
		$in.=")";
		if(mssql_num_rows($MSresult) == 0 )
			return $data;
		$query = "SELECT CardName, Empl_Ven, COUNT(*) AS COTIZACIONES FROM COTI WHERE Empl_Ven IN $in GROUP BY Empl_Ven ORDER BY 3 DESC LIMIT 10";
		$result = mysql_query($query);
			while ($rowF = mysql_fetch_array($result)){
				//echo "<br><br><br>";
				//Encuentra el nombre que corresponde
				$name = null;
				foreach( $empl  as $k){
					if($rowF["Empl_Ven"] == $k["SlpCode"]){
						$name = $k["SlpName"];
						break;
					}	
				}
				array_push($data,
							['CardName' => mb_convert_encoding($name, "UTF-8", "auto"),
							//'SlpName' => $name,
							'Cantidad' => $rowF["COTIZACIONES"] ]
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

	public function getVendedores($SLP){
		$queryF = "SELECT Empl_Ven, COUNT(*) AS COTIZACIONES FROM COTI WHERE Empl_Ven = $slp GROUP BY Empl_Ven ORDER BY 2 DESC LIMIT 10";
		return json_encode(self::exeQuery($queryF, $SLP));
	}

}


if(isset($_POST['id'])){
	$slp = $_POST['id'];
}
else{
	$slp = $_SESSION['salesPrson'];
}
$vendedores = new BuscarVendedores();

echo $vendedores->getVendedores( $slp );