<?php  

include 'docsAjax.php';

$file_path = '../ftp/capacitacion/';

$idTool = $_POST['id'];
$filename = $_POST['filename'];

	/**
	 * Realiza consultas a la base de datos de Herramientas
	 *
	 */	

class herramientasAjaxDelete extends dbConsulta{
	
	/**
	 * Insertar el Query a la DB
	 *
	 * @param String Query
	 */	

	public function deleteTool($id, $filename) {
		
		$sql = "DELETE FROM TRNG WHERE id = ". $id;

		self::setQuery($sql);

		unlink('../ftp/capacitacion/'.$filename);

		return json_encode(self::getData());
	}

}


$eliminarTools = new herramientasAjaxDelete();

 echo $eliminarTools->deleteTool((int) $idTool, $filename);