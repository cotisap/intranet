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


<p class="pageTitle">Mis cotizaciones</p>

<p>&nbsp;</p>

<table id="quotes" class="display" cellspacing="0" cellpadding="0" width="100%">
	<thead>
		<tr>
			<th>Folio</th>
			<th>Fecha</th>
			<th>Total</th>
			<th>Moneda</th>
			<th>Vendedor</th>
			<th></th>
		</tr>
	</thead>
</table>
<p>&nbsp;</p>

<script>
$(document).ready(function() {
	var table = $("#quotes").DataTable({
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json"
		},
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [5] },
			{'className': 'dt-right', 'aTargets': [2] }//,
			//{'visible': false, 'aTargets': [0] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/quotesList.php",
			type: "post"
		},
		"order": [[0, 'desc']],
		"stateSave": true
	});
});
</script>
<?php
include 'footer.php';
?>