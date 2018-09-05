<?php
include "head.php";

$customer = $_SESSION["customer"];
?>

<style>
	select, input {
		display: inline-block !important;
		width: initial !important;
	}
</style>
<!-- Data Tables -->
<link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>


<p class="pageTitle">Existencias</p>

<p>&nbsp;</p>

<table id="invoices" class="display" cellspacing="0" cellpadding="0" width="100%">
	<thead>
		<tr>
			<th>C&oacute;digo</th>
			<th>Descripci&oacute;n</th>
			<th>Marca</th>
			<th>Existencia</th>
			<th>Por recibir</th>
            <th>U.M.V.</th>
			<th>Precio de lista</th>
            <th>Moneda</th>
		</tr>
	</thead>
</table>
<p>&nbsp;</p>

<script>
$(document).ready(function() {
	var table = $("#invoices").DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json"
		},
		"aoColumnDefs": [
			{'className': 'dt-right', 'aTargets': [6] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/existencias.php",
			type: "post"
		},
		"order": [[0, 'asc']],
		"stateSave": true
	});
});
</script>
<?php
include 'footer.php';
?>