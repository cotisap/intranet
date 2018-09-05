<?php 

/*

  _____                _         __  __ _          _           
 / ____|              (_)       |  \/  (_)        | |          
| (___   ___ _ __ __ _ _  ___   | \  / |_ _ __ ___| | ___  ___ 
 \___ \ / _ \ '__/ _` | |/ _ \  | |\/| | | '__/ _ \ |/ _ \/ __|
 ____) |  __/ | | (_| | | (_) | | |  | | | | |  __/ |  __/\__ \
|_____/ \___|_|  \__, |_|\___/  |_|  |_|_|_|  \___|_|\___||___/
                  __/ |                                        
                 |___/                                         
*/


/**
 * Cronjob para ejecutar los reportes a los usuarios
 *
 * @author Sergio Mireles
 * @version v0.1
 */

include 'includes/mysqlconn.php';


$myServer = "corporativo-vallejo-tntchvdtpq.dynamic-m.com:1433/";  //dinamica  axtel o join networks
$myUser = "intranet";
$myPass = "L0g0ut*";
$myDB = "SBO_Pruebas";

//connection to the database
$dbhandle = mssql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on ".$myServer); 

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB"); 


class generarReporte{

 	/**
 	 *
 	 *
 	 * @param
 	 * @return 
 	 */

 	public function getDatos($company, $seller){
 		$result = mysql_query("SELECT Codigo_SN, CardName, st, Total_MN, Total_USD, Empl_Ven FROM COTI 
								WHERE (st != 4 OR st != 3) AND company = '".$company."' AND Empl_Ven = '".$seller."'
								 ORDER BY Codigo_SN");
	
		$data = array();

		while($row = mysql_fetch_assoc($result)) {
			array_push($data,[
								'idCoti' => utf8_encode($row['Codigo_SN']),
								'nameCoti' => utf8_encode($row['CardName']),
								'montoCotiMN' => utf8_encode($row['Total_MN']),
								'montoCotiUSD' => utf8_encode($row['Total_USD']),
								'Empl_Ven' => utf8_encode($row['Empl_Ven']), 
								'estatusCoti' => utf8_encode($row['st'])
							]);
		}

		return $data;		
 	}

 	public function templateEmail($correo, $data){

		$para  = $correo;


		$datoscentrales =  '';

		foreach ($data as $key => $value) {
		
		$datoscentrales = '<tr><td>'.$value['idCoti'].'</td><td>'.$value['nameCoti'].'</td><td>'.$value['montoCotiMN'].'</td><td>'.$value['montoCotiUSD'].'</td><td>'.$value['estatusCoti'].'</td></tr>'. $datoscentrales;

		}

		
		$título = 'Cotizaciones inconclusas';

		$mensaje = '
<html>
<head><title>Lista de cotizaciones</title></head>
<body>
 
<h1>Lista de cotizaciones</h1>
 
<table>
<tr>
  <td><strong># Cotizacion</strong></td>
  <td><strong>Nombre</strong></td>
  <td><strong>Monto MXN</strong></td>
  <td><strong>Monto USD</strong></td>
  <td><strong>Estatus</strong></td>
</tr>
 
'.$datoscentrales.'
 
</table>
 
</body>
</html>

		';


		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$cabeceras .= 'To: Vendedor <'.$correo.'>' . "\r\n";
		$cabeceras .= 'From: Recordatorio <no-reply@idited.com>' . "\r\n";

		mail($para, $título, $mensaje, $cabeceras);

 	}

 	/**
 	 *
 	 *
 	 * @param
 	 * @return 
 	 */

 	public function sendDatos(){
		


		$resulta_sap = mssql_query("SELECT SlpCode, Email FROM OSLP ");

		while($row = mssql_fetch_assoc($resulta_sap)) {
	
			$codigoUsr = utf8_encode($row['SlpCode']);
			$emailUsr = utf8_encode($row['Email']);

			self::templateEmail($emailUsr ,self::getDatos("Alianza", $codigoUsr)) ;

		}	

 	}

}

$cotizacion = new generarReporte();

$cotizacion->sendDatos();