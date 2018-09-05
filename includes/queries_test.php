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
					var_dump($rowF);
					echo "<br><br><br>";
				
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
		//Productos vendidos por $slp
		/*
		$queryF = "SELECT c.Codigo_Art, c.Nombre_Art, cc.Empl_Ven, COUNT( * ) AS CANTIDAD FROM COT1 c, COTI cc WHERE c.Id_Cot1=cc.Id_Cot1 AND cc.Empl_Ven = $SLP GROUP BY 2 ORDER BY 4 DESC LIMIT 10"; */

		//obtiene articulos del vendedor con id $slp
		/*$queryF = "SELECT c.Id_Cot1, c.Codigo_Art, c.Nombre_Art, cc.CardName, cc.Empl_Ven FROM COT1 c, COTI cc WHERE c.Id_Cot1=cc.Id_Cot1 AND cc.Empl_Ven = $SLP ORDER BY 1";*/
					//Cotizaciones totales por mes
		$queryF = "SELECT * FROM COTI";
		$queryF = "SELECT * FROM COT1 WHERE Codigo_Art = 'MX64-HW' LIMIT 1";
		$queryF = "SELECT * FROM ART1 LIMIT 1";

		//$queryF = "SELECT CardName, FechaCreacion, FechaEntrega FROM COTI LIMIT 10";
		//$queryF = "SELECT CardName, FechaCreacion, Fecha_Entrega FROM COTI LIMIT 10 WHERE FechaCreacion >= '";
		return json_encode(self::exeQuery($queryF));
	}

}

if(isset($_POST['id'])){
	$slp = $_POST['id'];
}
else{
	$slp = $_SESSION['salesPrson'];
}
$slp = 155;
$cotizaciones = new BuscarCotizaciones();

echo $cotizaciones->getCotizaciones( $slp);