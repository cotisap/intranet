<?php  

/**
 * Realiza consultas a la base de datos de Notas 
 *
 * @author Sergio Mireles
 * @version v0.1
 */	

$id = $_POST['id'];

include 'mssqlconn.php';

class Notes{

  /**
   * Obtener los datos en JSON
   *
   * @param Query 
   * @return JSON
   */

  public function deleteData($query, $id_) {

	$result = mssql_query($query.$id_);

	return json_encode(['data' => $result]);
  } 

}

$notas = new Notes();

echo $notas->deleteData("DELETE FROM dbo.[@CNOT] WHERE Code = ", $id);