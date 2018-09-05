<?php
/**
 * Archivo que filtra las cotizaciones y realiza un find 
 *
 * @version v0.1
 * @author Gerardo Cabello 
 */

include 'mssqlconn.php';
class BuscadorLotes{

	/**
	 * Ejecuta la consulta en la Base de datos
	 *
	 * @param String Query
	 * @return Array Data
	 */

	public function exeQuery($item, $whcode){
		$data = [];

		$query = "SELECT CASE WHEN A.Cantidad < 0 THEN A.Cantidad * -1 ELSE A.Cantidad END AS Cantidad, A.BatchNum FROM (SELECT SUM(T1.Qua) AS Cantidad, T1.BatchNum FROM (SELECT Quantity,ItemCode, BatchNum, WhsCode,Direction, CASE WHEN Direction >= 1 THEN Quantity * -1 ELSE Quantity END as Qua FROM IBT1 WHERE ItemCode LIKE '%$item%' AND WhsCode = $whcode) T1 GROUP BY T1.BatchNum, T1.WhsCode, T1.ItemCode) A;";

		$result = mssql_query($query);
		if(mssql_num_rows($result) == 0){
			return false;
		}else{
			while ($rowF = mssql_fetch_array($result)){
				array_push($data, [
							'BatchID' => $rowF["BatchNum"],
							'Quantity' => $rowF["Cantidad"]
						]);
			}
			return $data;
		}
	}
	
	/**
	 * Regresa todos los regitros en JSON 
	 *
	 * @param 
	 * @return JSON Data
	 */
//SELECT distinct CardName, Total_MN FROM COTI where company = 'alianza' AND Total_MN > 100000 ORDER BY Total_MN ASC LIMIT 10;

	public function getLotes( $item, $whcode ){
				//Total de ventas
		return json_encode([
			'batches' => self::exeQuery($item, $whcode),
		]);
	}

}

$item 	= $_POST['item'];
$whcode	= $_POST['whcode'];
$lotes = new BuscadorLotes();

header('Content-type: application/json');
echo $lotes->getLotes( $item, $whcode );