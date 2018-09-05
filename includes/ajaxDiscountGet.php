<?php  

/**
 * Realiza consultas a la base de datos de Notas 
 *
 * @author Sergio Mireles
 * @version v0.1
 */	

include 'mssqlconn.php';

class Discounts{

  /**
   * Obtener los datos en JSON
   *
   * @param Query 
   * @return JSON
   */

  public function getData($query) {

	$result = mssql_query($query);
	
	$data = array();

	while($row = mssql_fetch_assoc($result)) {
		array_push($data,['id' => utf8_encode($row["Code"]) , 'name' => utf8_encode($row["Name"]), 'u_discount' => utf8_encode($row["U_discount"])]);
	}

	return json_encode(['data' => $data]);
  } 

}

$Discount = new Discounts();

echo $Discount->getData("SELECT Code, Name, U_discount FROM [@DISCOUNT]");