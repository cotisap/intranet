<?php
$total = 0;
include "head.php";

$empl = $_SESSION["salesPrson"];

$isManager = false;

$queryMng = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'Y' AND SlpCode > 0 AND U_manager = '$empl'";
$resultMng = mssql_query($queryMng);
if(mssql_num_rows($resultMng) > 0) {
	$isManager = true;
}
?>
<h2>Articulos registrados en: <span id="company"><?php session_start(); echo $_SESSION['company']; ?></span></h2>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<table border="0" cellspacing="0" cellpadding="0" class="formTable" id="artsTable">
	<thead>
		<th>Codigo Art.</th>
		<th>Descripcion</th>
		<th>Unidades</th>
		<th>Precio</th>
		<th>Moneda</th>
		<th>Activo</th>
	</thead>
	<tbody style="text-align: center;">


	<?php
	session_start();

$timeout = 28800;

if(isset($_SESSION['timeout'])) {
    $duration = time() - (int)$_SESSION['timeout'];
    if($duration > $timeout) {
        session_destroy();
        session_start();
    }
}

$_SESSION['timeout'] = time();
$company = $_SESSION['company'];

include 'includes/mysqlconn.php';

/**
*  Buscar productos 
*/

class BuscarProductos
{

	/**
	 * Ejecuta la consulta en la Base de datos
	 *
	 * @param String Query
	 * @return Array Data
	 */
	public function getNumRows($cmp){
		$query = "SELECT Codigo_Art, Descripcion, UMV, precio, moneda, company FROM ART1 WHERE company LIKE '%$cmp%' ORDER BY 1";
		$result = mysql_query($query);
		return mysql_num_rows($result);
	}
	public function exeQuery($query){

		$data = array();
		$result = mysql_query($query);
		while ($rowF = mysql_fetch_array($result)){
				echo"<tr>";
				echo "<td>".$rowF['Codigo_Art']."</td>";
				echo "<td>".self::shortify($rowF['Descripcion'])."</td>";
				echo "<td>".$rowF['UMV']."</td>";
				echo "<td>".$rowF['precio']."</td>";
				echo "<td>".$rowF['moneda']."</td>";
				/*echo "<td id='".$rowF['Codigo_Art']."'>"."<input type='checkbox' class='status' />"."</td>";*/
				


				echo "<td>"."<input id='".$rowF['Codigo_Art']."' type='checkbox' class='status' ";
			
				if( $rowF["st"] == 1 ){
					echo "checked='checked'";
				}
				echo " />"."</td>";



				echo "</tr>";
		}

		return $data;
	}
	public function shortify( $str ){
		$max = 70;
		if( strlen( $str) > $max ){
			$str = substr($str, 0, $max)."...";
		}
		return $str;
	}
	
	/**
	 * Regresa todos los regitros en JSON 
	 *
	 * @param 
	 * @return JSON Data
	 */
//SELECT distinct CardName, Total_MN FROM COTI where company = 'alianza' AND Total_MN > 100000 ORDER BY Total_MN ASC LIMIT 10;

	public function getProductos($cmp){
		//$queryF = "SELECT Codigo_Art, Descripcion, UMV, precio, moneda, company, status FROM ART1 WHERE company LIKE '%$cmp%'";
		$queryF = "SELECT Codigo_Art, Descripcion, UMV, precio, moneda, company, st FROM ART1 WHERE company LIKE '%$cmp%'";
		//$queryF = "SELECT Codigo_Art, Descripcion, UMV, precio, moneda FROM ART1 LIMIT 10";
		
		return json_encode(self::exeQuery($queryF));
	}

}


$productos = new BuscarProductos();
$productos->getProductos( $company );


	?>



	</tbody>
</table>
<script type="text/javascript">
	var company = $("#company").text();
</script>
<script>
	$(document).ready(function(){
		$("#artsTable").DataTable();
		$(".status").on("change", function(){
			var id = $(this).attr("id");
			var status = $(this).prop("checked") ? 1 : 0;
			console.log(id);
			console.log(status);
			$.ajax({
				url : "includes/ajaxStatusProd.php",
				method : "POST",
				data : {
					"id" : id,
					"status" : status
				},
				success : function( response ){
					console.log( response );
				}
			});
		});
	});
</script>
<?php
include "footer.php";
?>