<?php
session_start();
if ($_SESSION["admin"] != 'Y') {
	header("Location: no-auth.php");
	die();
}

include 'head.php';

// Query Categories
$catIDs = array();
$catNames = array();
$queryCat = "SELECT id, category FROM TCAT ORDER BY category ASC";
$resultCat = mysql_query($queryCat);
while ($rowCat = mysql_fetch_assoc($resultCat)) {
	$catIDs[] = $rowCat["id"];
	$catNames[] = utf8_encode($rowCat["category"]);
}

// Query Brands
$brandIDs = array();
$brandNames = array();
$queryBrand = "SELECT id, brand FROM TBNS ORDER BY brand ASC";
$resultBrand = mysql_query($queryBrand);
while ($rowBrand = mysql_fetch_assoc($resultBrand)) {
	$brandIDs[] = $rowBrand["id"];
	$brandNames[] = utf8_encode($rowBrand["brand"]);
}
?>

<link rel="stylesheet" type="text/css" href="css/selectfile/component.css" />
<link rel="stylesheet" type="text/css" href="js/dt/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>

<div class="searchDiv">
Mostrar documentos por Categor&iacute;a <select id="selectC" class="" name="">
    <?php
	echo "<option value='0'>Todas</option>";
	foreach ($catIDs as $i => $catID) {
		echo "<option value='".$catID."'>".$catNames[$i]."</option>";
	}
	?>
</select> 
<br>
Busqueda por
<input type="radio" name="searchBy[]" class="searchBy" value="TITULO" checked> T&iacute;tulo
<input type="radio" name="searchBy[]" class="searchBy" value="ARCHIVO"> Archivo

</div>

<table id="docsList" class="display" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th>Categor&iacute;a</th>
            <th>T&iacute;tulo</th>
            <th>Descripci&oacute;n</th>
            <th>Archivo</th>   
            <th width="25px">Link</th>
            <th>Editar</th>
            <th>Eliminar</th>
        </tr>
    </thead>
</table>

<p>&nbsp;</p>

<form method="post" enctype="multipart/form-data" id="newToolForm" name="newToolForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
  	<tr>
    	<td colspan="3" class="qSec">Nuevo documento</td>
    </tr>
    <tr>
      <td>T&iacute;tulo</td>
      <td>Categor&iacute;a</td>
    </tr>
    <tr>
      <td><input type="text" id="title" name="title" style="width:100%"></td>
      <td>
      	<select id="category" name="category" class="category">
        	<?php
			echo "<option value=''>Selecciona...</option>";
			foreach ($catIDs as $i => $catID) {
				echo "<option value='".$catID."'>".$catNames[$i]."</option>";
			}
			?>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="3" align="center"><input type="file" name="file" id="file" class="inputfile inputfile-1" />
        <label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Selecciona un archivo</span></label>
        </td>
    </tr>
    <tr>
    	<td colspan="3" class="qSec">Descripci&oacute;n</td>
    </tr>
    <tr>
    	<td colspan="3"><textarea id="remarks" name="remarks" maxlength="250"></textarea></td>
    </tr>
    <tr>
      <td colspan="3">
      	<ul class="buttonBar">
        	<li><input type="reset" value="Cancelar" class="button red"></li>
            <li><button type="button" class="button green" id="newTool">Guardar</button></li>
        </ul>
      </td>
    </tr>
    <tr>
    	<td colspan="3"></td>
    </tr>
    <tr>
    	<td colspan="3"><div class="footNotes"><img src="images/info-icon.png">Los formatos de archivo aceptados son XLS, XLSX, DOC, DOCX, PPT, PPTX, PDF, JPG y PNG.<br>El peso m&aacute;ximo del archivo no debe ser mayor a 20 MB.</div></td>
    </tr>
  </tbody>
</table>

</form>
<script src="js/custom-file-input.js"></script>
<script>
$(document).ready( function () {
	$('#trainBT').addClass('active');
	$('#subHeaderTitle').html('Documentos');
	
	$("#newToolForm").validate({
		rules: {
			title: {
				required: true,
				minlength: 3
			},
			category: {
				required: true,
			},
			brand: {
				required: true,
			},
			file: {
				required: true,
				extension: "xls,xlsx,doc,docx,ppt,pptx,pdf,jpg,png"
			}
		}
	});
	
	// DataTable
	var table = $('#docsList').DataTable({
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [6] },
			{'className': 'dt-center', 'aTargets': [6] }//,
			//{'visible': false, 'aTargets': [0] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/docsListTrng.php?cat="+$("#selectC").val()+"&brand="+$("#selectB").val()+"&by="+$(".searchBy:checked").val(),
			type: "post"
		},
		"order": [[1, 'asc']]
	});
	
	/**
	 * Actualizar Registro
	 *
	 */

	$('#docsList tbody').on('click', 'tr a.updateDoc', function () {
    	var dataId = $(this).attr('data-id');

    	var datosRow = $(this).parents('tr');

    	$(datosRow[0].children[1]).html("<input type='text' data-initial='"+ 
    										datosRow[0].children[1].innerText +"' value='"+ 
    											datosRow[0].children[1].innerText +"'>");

    	$(datosRow[0].children[2]).html("<input type='text' data-initial='"+ 
    										datosRow[0].children[2].innerText +"' value='"+ 
    											datosRow[0].children[2].innerText +"'>");
    	
    	$(datosRow[0].children[5]).html("<img class='guardar' data-id='"+ dataId +"' src='images/icons/save.png'>" 
    										+ "<img class='cancelar' data-id='"+ dataId +"' src='images/icons/close.png'>");

    });


	$('#docsList tbody').on('click', 'tr img.cancelar', function () {
		var dataId = $(this).attr('data-id');

		var datosRow = $(this).parents('tr');

		$(datosRow[0].children[5]).html("<a data-id='" + dataId + "' class='updateDoc'><img src='images/pencil.png'></a>");
		$(datosRow[0].children[1]).text($(datosRow[0].children[1].children[0]).attr('data-initial'));
		$(datosRow[0].children[2]).text($(datosRow[0].children[2].children[0]).attr('data-initial'));

    });

	$('#docsList tbody').on('click', 'tr img.guardar', function () {
		var dataId = $(this).attr('data-id');

		var datosRow = $(this).parents('tr');

		$(datosRow[0].children[5]).html("<a data-id='" + dataId + "' class='updateDoc'><img src='images/pencil.png'></a>");

		console.log($(datosRow[0].children[1].children[0]).val());
		console.log($(datosRow[0].children[2].children[0]).val());

		$.ajax({
			async: false,
			type: 'post',
			url: 'includes/ajaxTrngUpdate.php',
			data: {
				id : dataId,
				titulo : $(datosRow[0].children[1].children[0]).val(),
				descripcion : $(datosRow[0].children[2].children[0]).val()
			},
			success: function() {
					$(datosRow[0].children[1]).text($(datosRow[0].children[1].children[0]).val());
					$(datosRow[0].children[2]).text($(datosRow[0].children[2].children[0]).val());
			},
			error: function () {
				alert('Se detecto un error durante el proceso');
			}

		});

    });

	/**
	 * Eliminar registro
	 *
	 */

	$('#docsList tbody').on('click', 'tr a.removeDoc', function () {
    	var dataId = $(this).attr('data-id');

    	var datosRow = $(this).parents('tr');

    	$.ajax({
    		type: 'post',
    		url: 'includes/ajaxTrngDelete.php',
    		data: {
    			id : dataId,
    			filename : datosRow[0].children[3].innerText
    			},
    		success: function (data) {
    			table
        			.row( $(this).parents('tr') )
        				.remove()
        					.draw();
    		},
    		error: function () {
    			alert('Se detecto un error durante el proceso');
    		}
    	});

    });




	$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/docsList.php?cat="+$("#selectC").val()+"&brand="+$("#selectB").val()+"&by="+$(".searchBy:checked").val()).load();
	};
	
	$("#selectC").on("change", sendValues);
	$("#selectB").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
});

$("#newTool").click(function () {
	$("#newToolForm").attr("action", "includes/admintrng.php");
	$("#newToolForm").submit();
});



</script>



<?php include 'footer.php'; ?>