<?php
include "head.php";
?>

<link rel="stylesheet" type="text/css" href="js/dt/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>


<div>
	<table id="cotizacionesSearch" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Nombre del cliente</th>
				<th># de Folio</th>
				<th>RFC</th>
				<th>Sucursal</th>
				<th>Vendedor</th>
				<th>Mayores a</th>
				<th>Desde</th>
				<th>Hasta</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Nombre del cliente</th>
				<th># de Folio</th>
				<th>RFC</th>
				<th>Sucursal</th>
				<th>Vendedor</th>
				<th>Mayores a</th>
				<th>Desde</th>
				<th>Hasta</th>
			</tr>
		</tfoot>
	</table>
</div>

<script type="text/javascript">

$(document).ready(function() {

	/**
	 * Habilitar buscadores
	 */

	$('#cotizacionesSearch thead th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="'+title+'" />' );
    } );

	/**
 	 * Llenar tabla de Notas
 	 */

 	 $('#cotizacionesSearch').DataTable({
 	 	"ajax": 'includes/ajaxnn.php',
 	 	"columnDefs": [
		{
 	 		"targets": 0,
 	 		"data": "name",
 	 		"render": function (data) {
 	 			return data;
 	 		},
 	 		"className": "dt-center"
 	 	},{
 	 		"targets": 1,
 	 		"data": "u_conditions",
 	 		"render": function (data) {
 	 			return data;
 	 		}
 	 	},{
 	 		"targets": 2,
 	 		"data": "id",
 	 		"render": function (data) {
 	 			return '<a data-id="'+ data +'" class="updateDoc"><img src="images/pencil.png" ></a>';
 	 		},
 	 		"className": "dt-center"
 	 	},{
 	 		"targets": 3,
 	 		"data": "id",
 	 		"render": function (data) {
 	 			return '<a data-id="'+ data +'" class="removeDoc"><img src="images/remove-icon.png" ></a>';
 	 		},
 	 		"className": "dt-center"
 	 	}]
  	 });


});


</script>

<?php
	include "footer.php";
?>