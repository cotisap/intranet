<?php  

/**
 * Realiza consultas a la base de datos de Notas 
 *
 * @author Sergio Mireles
 * @version v0.1
 */	

$id = $_POST['id'];
$condiciones = $_POST['condiciones'];

include 'mssqlconn.php';

class Notes{

  /**
   * Obtener los datos en JSON
   *
   * @param Query 
   * @return JSON
   */

  public function updateData($query, $id_) {

	$result = mssql_query($query.$id_);

	return json_encode(['data' => $result]);
  } 

}

$notas = new Notes();

echo $notas->updateData("UPDATE dbo.[@CNOT] SET U_conditions = ".$condiciones." WHERE Code = ", $id);