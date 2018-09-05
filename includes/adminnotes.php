<div>
	<table id="adminnotes" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Condiciones</th>
				<th>Editar</th>
				<th>Eliminar</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Nombre</th>
				<th>Condiciones</th>
				<th>Editar</th>
				<th>Eliminar</th>
			</tr>
		</tfoot>
	</table>
</div>

<script type="text/javascript">

$(document).ready(function() {


	/**
 	 * Llenar tabla de Notas
 	 */

 	var tableNotes = $('#adminnotes').DataTable({
 	 	"ajax": 'includes/ajaxNotesGet.php',
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

	/**
	 * Eliminar registro
	 *
	 */

	$('#adminnotes tbody').on('click', 'tr a.removeDoc', function () {
    	var dataId = $(this).attr('data-id');

    	var datosRow = $(this).parents('tr');

    	$.ajax({
    		type: 'post',
    		url: 'includes/ajaxNotesDelete.php',
    		data: {
    			id : dataId
    			},
    		success: function () {
    			location.reload();
    		},
    		error: function () {
    			alert('Se detecto un error durante el proceso');
    		}
    	});

    });

	
	/**
	 * Actualizar Registro
	 *
	 */

	$('#adminnotes tbody').on('click', 'tr a.updateDoc', function () {
    	var dataId = $(this).attr('data-id');

    	var datosRow = $(this).parents('tr');

    	$(datosRow[0].children[0]).html("<input type='text' data-initial='"+ 
    										datosRow[0].children[0].innerText +"' value='"+ 
    											datosRow[0].children[0].innerText +"'>");

    	$(datosRow[0].children[1]).html("<textarea type='text' data-initial='"+ 
    										datosRow[0].children[1].innerHTML +"'>"+ datosRow[0].children[1].innerHTML +"</textarea>");
    	
    	$(datosRow[0].children[2]).html("<img class='guardar' data-id='"+ dataId +"' src='images/icons/save.png'>" 
    										+ "<img class='cancelar' data-id='"+ dataId +"' src='images/icons/close.png'>");

    });


	$('#adminnotes tbody').on('click', 'tr img.cancelar', function () {
		var dataId = $(this).attr('data-id');

		var datosRow = $(this).parents('tr');

		$(datosRow[0].children[2]).html("<a data-id='" + dataId + "' class='updateDoc'><img src='images/pencil.png'></a>");
		$(datosRow[0].children[0]).html($(datosRow[0].children[0].children[0]).attr('data-initial'));
		$(datosRow[0].children[1]).html($(datosRow[0].children[1].children[0]).attr('data-initial'));

    });

	$('#adminnotes tbody').on('click', 'tr img.guardar', function () {
		var dataId = $(this).attr('data-id');

		var datosRow = $(this).parents('tr');
		console.log(dataId);
		$(datosRow[0].children[2]).html("<a data-id='" + dataId + "' class='updateDoc'><img src='images/pencil.png'></a>");
		console.log($(datosRow[0].children[1].children[0]).val());
		$.ajax({
			type: 'post',
			url: 'includes/ajaxNotesUpdate.php',
			data: {
				id : dataId,
				condiciones : $(datosRow[0].children[1].children[0]).val()
			},
			success: function() {
					$(datosRow[0].children[1]).val($(datosRow[0].children[1].children[0]).val());
			},
			error: function () {
				alert('Se detecto un error durante el proceso');
			}

		});

    });


});


</script>