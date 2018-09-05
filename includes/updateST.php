<?php
/**
 * Cambiar estatus de la cotizacion
 * @author Sergio Mireles
 * @version v0.1
 */

$id = $_POST['id'];
$st = $_POST['st'];
$razon = $_POST['razon'];
$otro = $_POST['otro'];

$stArray = array('Cotizada','Negociando','Ganada','Perdida');

include 'mysqlconn.php';

$stN = array_search($st, $stArray);

class St{

  /**
   * Obtener los datos en JSON
   *
   * @param Query 
   * @return JSON
   */

  public function updateST($query, $id_) {

	$result = mysql_query($query.$id_);

	return json_encode(['data' => $result]);
  } 

}

$notas = new St();

echo $notas->updateST("UPDATE COTI SET st = ". ($stN + 1) .", razon='$razon', otro='$otro' WHERE Id_Cot1 = ", $id);