<div>
	<table id="admindiscount" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Porcentaje de descuento</th>
				<th>Default</th>
				<th>Editar</th>
				<th>Eliminar</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Porcentaje de descuento</th>
				<th>Default</th>
				<th>Editar</th>
				<th>Eliminar</th>
			</tr>
		</tfoot>
	</table>
</div>

<script type="text/javascript">

$(document).ready(function() {


	/**
 	 * Llenar tabla de Discount
 	 */

 	var TableDiscount = $('#admindiscount').DataTable({
 	 	"ajax": 'includes/ajaxDiscountGet.php',
 	 	"columnDefs": [
		{
			"className": "dt-center", "targets": "_all"
		},{
 	 		"targets": 0,
 	 		"data": "name",
 	 		"render": function (data) {
 	 			return data;
 	 		}
 	 	},{
 	 		"targets": 1,
 	 		"data": "u_discount",
 	 		"render": function (data) {

 	 			if (data == 1) {
					return '<input type="radio" name="defaultdisc" value="'+ data + '" checked>'; 	 				
 	 			}

 	 			return '<input type="radio" name="defaultdisc" value="'+ data + '">';
 	 		},
 	 		"width": "10%"
 	 	},{
 	 		"targets": 2,
 	 		"data": "id",
 	 		"render": function (data) {
 	 			return '<a data-id="'+ data +'" class="updateDoc"><img src="images/pencil.png" ></a>';
 	 		}
 	 	},{
 	 		"targets": 3,
 	 		"data": "id",
 	 		"render": function (data) {
 	 			return '<a data-id="'+ data +'" class="removeDoc"><img src="images/remove-icon.png" ></a>';
 	 		}
 	 	}]
  	 });


});

</script>


