<?php
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

<p class="pageTitle">Documentos</p>

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
</select> Marca <select id="selectB" class="" name="">
	<?php
	echo "<option value='0'>Todas</option>";
	foreach ($brandIDs as $i => $brandID) {
		echo "<option value='".$brandID."'>".$brandNames[$i]."</option>";
	}
	?>
</select><br>
Busqueda por
<input type="radio" name="searchBy[]" class="searchBy" value="TITULO" checked> T&iacute;tulo
<input type="radio" name="searchBy[]" class="searchBy" value="ARCHIVO"> Archivo

</div>

<table id="docsList" class="display" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th>Categor&iacute;a</th>
            <th>Marca</th>
            <th>T&iacute;tulo</th>
            <th>Archivo</th>
            <th width="25px">Nombre</th>
            <th width="25px">Link</th>
        </tr>
    </thead>
</table>

<script>
$(document).ready( function () {	
	// DataTable
	var table = $('#docsList').DataTable({
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [4] },
			{'className': 'dt-center', 'aTargets': [4] }//,
			//{'visible': false, 'aTargets': [0] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/docsList.php?cat="+$("#selectC").val()+"&brand="+$("#selectB").val()+"&by="+$(".searchBy:checked").val(),
			type: "post"
		},
		"order": [[1, 'asc']]
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
</script>


<?php include 'footer.php'; ?>