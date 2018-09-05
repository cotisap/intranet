<?php
include "head.php";
?>

<script src="/js/jquery-1.4.4.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.7/jquery-ui.js"></script>
<script src="/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/js/jquery.dataTables.rowGrouping.js" type="text/javascript"></script>


<script type="text/javascript" charset="utf-8">
$(document).ready( function () {
	$(".reportTable").dataTable({
		"bLengthChange": false,
		"bPaginate": false
	}).rowGrouping({
		bExpandableGrouping: true,
		bExpandSingleGroup: false,
		asExpandedGroups: []
	});
	GridRowCount();
});

function GridRowCount() {
	$('input.expandedGroup').remove();
	$('input.collapsedGroup').remove();
	$('.dataTables_wrapper').find(".sorting").removeClass("sorting");
	$('.dataTables_wrapper').find(".group").attr("colspan",2);
	$('.dataTables_wrapper').find(".group").parent("tr").append("<td class='cDoc' align='center'></td><td class='dView'></td>");
	
	// Count
	$('.dataTables_wrapper').find('[id|=group-id]').each(function () {
		var rowCount = $(this).nextUntil('[id|=group-id]').length;
		$(this).find(".cDoc").append($("<span />", { "class": "rowCount-grid" }).append($("<b />", { "text": rowCount + " documentos" })));
	});
	
	$('.dataTables_wrapper').find('.dataTables_filter').append($('<input />', { 'type': 'button', 'class': 'expandedGroup clpsXpndBT collapsed green', 'value': 'Expandir todo' }));
	$('.dataTables_wrapper').find('.dataTables_filter').append($('<input />', { 'type': 'button', 'class': 'collapsedGroup clpsXpndBT expanded red', 'value': 'Colapsar todo' }));

	$('.expandedGroup').live('click', function () {
		$(this).parents('.dataTables_wrapper').find('.collapsed-group').trigger('click');
	});
	$('.collapsedGroup').live('click', function () {
		$(this).parents('.dataTables_wrapper').find('.expanded-group').trigger('click');
	});
};
</script>

<p class='reportTitle'>Entregas</p>

<?php
$fromDate = date('Y-m-d H:i:s.000', strtotime(str_replace('/', '-', $_GET["fromDate"])));
$toDate = date('Y-m-d H:i:s.000', strtotime(str_replace('/', '-', $_GET["toDate"])));
	
$query = "SELECT DocNum, NumAtCard, (CardName + ' - ' + CardCode) AS Cliente, DocDate, SlpCode, DocTotal FROM ODLN
		WHERE DocDate >= '$fromDate'
		AND DocDate <= '$toDate'
		ORDER BY DocDate DESC";
$result = mssql_query($query);
	
echo "<div class='reportContainer'><table class='reportTable'><thead><tr><th>Cliente</th><th>No. de documento</th><th>Folio pedido</th><th>Fecha</th><th width='40px'></th></tr></thead><tfoot><tr><th></th><th></th><th></th><th></th><th></th></tr></tfoot><tbody>";
date_default_timezone_set('America/Mexico_City');
//display the results 
while($row = mssql_fetch_array($result)) {
	$cotDate = date_create($row["DocDate"]);
	$tot = $row["DocTotal"];
	
  echo "<tr><td>".$row["Cliente"]."</td><td>".$row["DocNum"]."</td><td>".$row["NumAtCard"]."</td><td>".date_format($cotDate, "d/m/Y")."</td><td><a href='entregar.php?idDLV=".$row["DocNum"]."' class='viewDetails'><img src='images/truck.png'></a></td></tr>";
}
echo "</tbody></table></div>";
echo "<p></p>";

include 'footer.php';
?>