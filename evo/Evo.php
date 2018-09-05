<?php
require "head_evo.php";
include "../includes/mssqlevo.php";
?>

<div class="container-fluid">
<h4 class="text-center">Reporte Evo FG</h4>
</div><br>
<div class="container">
<table id="tableFg" class="display" cellpadding="0" cellspacing="0" width="100%" style="font-size:8px" >
	<thead>
    <tr>
		<th>TIPO DE CAMBIO</th>
        <th>STATUS</th>
        <th>FECHA</th>
        <th>TIPO DE DOC</th>
        <th>CLAVE</th>
        <th>DESCRIPCION</th>
        <th>UOM</th>
        <th>N° DE FACTURA</th>
        <th>N° DE CLIENTE</th>
        <th>CANTIDAD</th>
        <th>PRECIO UNIT. MXN</th>
        <th>PRECIO TOTAL MXN</th>
        <th>TOTAL USD</th>
        <th>PRECIO UNIT. REVISADO MXN</th>
        <th>PRECIO TOTAL REVISADO MXN</th>
        <th>NOMBRE CLIENTE</th>
        <th>VENDEDOR</th>
        <th>SUCURSAL</th>
        <th>ESTADO</th>
        <th>MARCA</th>
        <th>APLICACION</th>
        <th>FAMILIA</th>
        <th>MES</th>
        <th>AÑO</th>
        <th>SEMANA</th>
        <th>SEMANA CONSECUTIVA</th>
	</tr>
    </thead>
    <tfoot>
    	<tr>
		<th>TIPO DE CAMBIO</th>
        <th>STATUS</th>
        <th>FECHA</th>
        <th>TIPO DE DOC</th>
        <th>CLAVE</th>
        <th>DESCRIPCION</th>
        <th>UOM</th>
        <th>N° DE FACTURA</th>
        <th>N° DE CLIENTE</th>
        <th>CANTIDAD</th>
        <th>PRECIO UNIT. MXN</th>
        <th>PRECIO TOTAL MXN</th>
        <th>TOTAL USD</th>
        <th>PRECIO UNIT. REVISADO MXN</th>
        <th>PRECIO TOTAL REVISADO MXN</th>
        <th>NOMBRE CLIENTE</th>
        <th>VENDEDOR</th>
        <th>SUCURSAL</th>
        <th>ESTADO</th>
        <th>MARCA</th>
        <th>APLICACION</th>
        <th>FAMILIA</th>
        <th>MES</th>
        <th>AÑO</th>
        <th>SEMANA</th>
        <th>SEMANA CONSECUTIVA</th>
	</tr>
    </tfoot>

	<?php
    $evoAz = $_POST['evoFg'];
    $query = "SELECT TOP 50 * FROM EVO_FG ";
    $result = mssql_query($query);    
	while ($row = mssql_fetch_assoc($result)){	  
        echo "<tbody>";
		echo "<tr>";
        echo "<td>".utf8_encode($row["TIPO DE CAMBIO"])."</td>";
        echo "<td>".utf8_encode($row["STATUS"])."</td>";
        echo "<td>".utf8_encode($row["FECHA"])."</td>";
        echo "<td>".utf8_encode($row["TIPO DE DOC"])."</td>";
        echo "<td>".utf8_encode($row["CLAVE"])."</td>";
        echo "<td>".utf8_encode($row["DESCRIPCION"])."</td>";
        echo "<td>".utf8_encode($row["UOM"])."</td>";
        echo "<td>".utf8_encode($row["N° DE FACTURA"])."</td>";
        echo "<td>".utf8_encode($row["N° DE CLIENTE"])."</td>";
        echo "<td>".utf8_encode($row["CANTIDAD"])."</td>";
        echo "<td>".utf8_encode($row["PRECIO UNIT. MXN"])."</td>";
        echo "<td>".utf8_encode($row["PRECIO TOTAL MXN"])."</td>";
        echo "<td>".utf8_encode($row["TOTAL USD"])."</td>";
        echo "<td>".utf8_encode($row["PRECIO UNIT. REVISADO MXN"])."</td>";
        echo "<td>".utf8_encode($row["PRECIO TOTAL REVISADO MXN"])."</td>";
        echo "<td>".utf8_encode($row["NOMBRE CLIENTE"])."</td>";
        echo "<td>".utf8_encode($row["VENDEDOR"])."</td>";
        echo "<td>".utf8_encode($row["SUCURSAL"])."</td>";
        echo "<td>".utf8_encode($row["ESTADO"])."</td>";
        echo "<td>".utf8_encode($row["MARCA"])."</td>";
        echo "<td>".utf8_encode($row["APLICACION"])."</td>";
        echo "<td>".utf8_encode($row["FAMILIA"])."</td>";
        echo "<td>".utf8_encode($row["MES"])."</td>";
        echo "<td>".utf8_encode($row["AÑO"])."</td>";
        echo "<td>".utf8_encode($row["SEMANA"])."</td>";
        echo "<td>".utf8_encode($row["SEMANA CONSECUTIVA"])."</td>";		
        echo "</tr>";
		echo "</tbody>";	
    }
	?>
</table><br><br>
</div>
 <script>
$(document).ready(function() {
   var table = $('#tableFg').DataTable({
	   "paging": false,
	 
		"order": [[ 1, "asc" ]],
		//"pagingTpe": "full_numbers",
		"ordering": false,
		"scrollY": 350,
		"scrollX": true,
		"lengthMenu": false,
		"info": false,
		"search": false
		//"lengthMenu": [[10,25,50,-1],[10,25,50]],
		/*"language":{
			"info": "Mostrando pagina _PAGE_ de _PAGES_",
			"lengthMenu": "Mostrar _MENU_ entradas",
			"search": "Buscar:",
			"paginate": {
					"First": "Inicio",
					"Last":  "Ultimo",
					"next":  "Siguiente",
					"previous": "Previo" 
			}
			},*/
			
		
		});	
});

</script> 

<?php
require 'footer.php';
?>

