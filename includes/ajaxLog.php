<?php  
/**
 * Archivo que filtra las cotizaciones y realiza un find 
 *
 * @version v0.1
 * @author GerardoSteven 
 */

include 'mysqlconn.php';
session_start(); 
$codigoArticulo = $_POST['codArt'];
$timestamp = date("Y-m-d H:i:s");
$usr = $_SESSION["salesPrson"];

/**
 * Se ingresa los logs a la tabla LOG 
 *
 */

class Log{

	/**
	 * Inserta un registro a la bitácora de la base de datos
	 *
	 * @param String tiempo, empl, codigoaArt, mensaje
	 * @return Void
	 */

	public function insert($tiempo, $empl, $codigoArt, $msg){
		
		$query = "INSERT INTO LOG (time, usuario, articulo, mensaje) VALUES('$tiempo', '$empl', '$codigoArt', '$msg' )";
		
		mysql_query($query);

		return '';
	}

}

$bitacora = new Log();
$bitacora->insert($timestamp, $usr, $codigoArticulo, "Se hizo una cotizacion con un precio de lista de artículo menor al que se encuentra en SAP");