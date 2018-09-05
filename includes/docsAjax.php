<?php 

	/**
	 *
	 * @author Sergio Mireles 
	 * @version 0.1 
	 */

	session_start();

	include "mysqlconn.php";

	/**
	 * Realiza consultas a la base de datos de MySQL
	 *
	 */	

	class dbConsulta
	{

		/**
		 * Insertar el Query a la DB
		 *
		 * @param String Query
		 */

		private $resultado;

		public function setQuery ($consulta){
			$this->resultado = mysql_query($consulta);
		}

		/**
		 * Obtener la data de la DB
		 *
		 * @return Array Data
		 */

		public function getData (){

			if (!$resultado) {
    			$mensaje  = 'Vacia' . mysql_error() . "\n";

    			return $mensaje;
			}

			return mysql_fetch_assoc($this->resultado);
		}

	}
