<?php  

include 'docsAjax.php';

$idTool = $_POST['id'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];

	/**
	 * Realiza consultas a la base de datos de Herramientas
	 *
	 */	

class herramientasAjaxUpdate extends dbConsulta{
	
	/**
	 * Insertar el Query a la DB
	 *
	 * @param String Query
	 */	

	public function updateTool($id, $titulo, $descripcion) {
		
		$sql = "UPDATE TRNG SET title = '$titulo', remarks = '$descripcion' WHERE id = ".$id;

		self::setQuery($sql);

		return json_encode(self::getData());
	}

}


$updateTools = new herramientasAjaxUpdate();

 echo $updateTools->updateTool((int) $idTool, $titulo, $descripcion);