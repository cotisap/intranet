<?php
include "head.php";

$empl = $_SESSION["salesPrson"];

$isManager = false;

$queryMng = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'Y' AND SlpCode > 0 AND U_manager = '$empl'";
$resultMng = mssql_query($queryMng);
if(mssql_num_rows($resultMng) > 0) {
	$isManager = true;
}
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
	$('.dataTables_wrapper').find(".group").attr("colspan",1);
	$('.dataTables_wrapper').find(".group").parent("tr").append("<td class='cDoc' align='center'></td><td class='bp0'></td><td class='dView'></td>");
	
	// Count
	$('.dataTables_wrapper').find('[id|=group-id]').each(function () {
		var rowCount = $(this).nextUntil('[id|=group-id]').length;
		$(this).find(".cDoc").append($("<span />", { "class": "rowCount-grid" }).append($("<b />", { "text": rowCount + " documentos" })));
	});
	
	// BP Sum
	$('.dataTables_wrapper').find('[id|=group-id]').each(function () {
		var bpSum = 0;
		var ranges = ['0'];
		for (var i=0; i<ranges.length; i++) {
			bpSum = 0;
			var rowValue = $(this).nextUntil('[id|=group-id]').find(".d" + ranges[i]).each(function() {
				var value = $(this).text();
				value = value.replace(/,/g, '');
				if(!isNaN(value) && value.length != 0) {
					bpSum += parseFloat(value);
				}
			});
			$(this).find(".bp" + ranges[i]).html(localeString(bpSum.toFixed(2)));
		}
	});
	getTotSums();
	
	$('.dataTables_wrapper').find('.dataTables_filter').append($('<input />', { 'type': 'button', 'class': 'expandedGroup clpsXpndBT collapsed green', 'value': 'Expandir todo' }));
	$('.dataTables_wrapper').find('.dataTables_filter').append($('<input />', { 'type': 'button', 'class': 'collapsedGroup clpsXpndBT expanded red', 'value': 'Colapsar todo' }));
	$('.dataTables_wrapper').find('.dataTables_filter').append($('<input />', { 'type': 'button', 'class': 'csvExport clpsXpndBT blue', 'value': 'Descargar CSV' }));

	$('.expandedGroup').live('click', function () {
		$(this).parents('.dataTables_wrapper').find('.collapsed-group').trigger('click');
	});
	$('.collapsedGroup').live('click', function () {
		$(this).parents('.dataTables_wrapper').find('.expanded-group').trigger('click');
	});
	$('.csvExport').live('click', function () {
		window.open("includes/export2csv.php?slpCode="+$(this).parents('.dataTables_wrapper').find(".reportTable").attr("id"),"_blank");
	});
};
</script>

<table class="formTable">
    	<td align="left" class="pageTitle">Cotizaciones</td>
        <td align="right">
        	<div class="csvAll button blue">Descargar todo como CSV</div>
        </td>
    </tr>
</table>
<p>&nbsp;</p>

<?php
if ($_SESSION["admin"] == 'Y' || $isManager) {
	$salesPerson = $_GET["salesPerson"];
} else {
	$salesPerson = [$empl];
}

$fromDate = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $_GET["fromDate"])));
$toDate = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $_GET["toDate"])));
$Montominimo = $_GET["montomin"];
	
foreach ($salesPerson as $sp) {
	
 /*if ($_GET["may100"] == 'Y' ){
	 
	 $query = "SELECT Id_Cot1, CONCAT(CardName, ' - ', Codigo_SN) AS Cliente, FechaCreacion, Total_Doc, Empl_Ven FROM COTI
WHERE Empl_Ven = $sp
AND FechaCreacion >= '$fromDate'
AND FechaCreacion <= '$toDate'
AND status = 'Q'
AND Total_Doc >= '$Montominimo'
AND company = '".$_SESSION["company"]."'
ORDER BY FechaCreacion DESC";
	 
 }
	else{}
	*/
	
	$query = "SELECT Id_Cot1, CONCAT(CardName, ' - ', Codigo_SN) AS Cliente, FechaCreacion, Total_Doc, Empl_Ven FROM COTI
WHERE Empl_Ven = $sp
AND FechaCreacion >= '$fromDate'
AND FechaCreacion <= '$toDate'
AND status = 'Q'
AND Total_Doc >= '$Montominimo'
AND company = '".$_SESSION["company"]."'
ORDER BY FechaCreacion DESC";
	
	
	$result = mysql_query($query);
	
	$querySP = "SELECT SlpName FROM OSLP WHERE SlpCode = $sp";
	$resultSP = mssql_query($querySP);
	$rowSN = mssql_fetch_array($resultSP);
	
	
	echo "Vendedor: ".$rowSN["SlpName"]."<br>";
		
	echo "<div class='reportContainer'><table class='reportTable' id='".$sp."'><thead><tr><th>Cliente</th><th>Folio cotizaci&oacute;n</th><th>Fecha</th><th class='h0' width='160px'>Monto total</th><th width='40px'></th></tr></thead><tfoot><tr><th></th><th></th><th>Fecha</th><th class='cd0'></th><th></th></tr></tfoot><tbody>";
	date_default_timezone_set('America/Mexico_City');
	//display the results 
	while($row = mysql_fetch_array($result)) {
		$cotDate = date_create($row["FechaCreacion"]);
		$tot = $row["Total_Doc"];
		
	  echo "<tr><td>".$row["Cliente"]."</td><td>".$row["Id_Cot1"]."</td><td>".date_format($cotDate, "d/m/Y")."</td><td class='d0'>".number_format($tot, 2, '.', ',')."</td><td><a href='vercotizacion.php?idCot=".$row["Id_Cot1"]."' class='viewDetails'><img src='images/details.png'></a></td></tr>";
	}
	echo "</tbody></table></div>";
	echo "<p></p>";
}
?>
<script type='text/javascript'>
function getTotSums() {
	var tableIds = <?php echo '["' . implode('", "', $salesPerson) . '"]' ?>;
	for (var t=0; t<tableIds.length; t++) {
		var sum = 0;
		var ranges = ['0'];
		$.each( ranges, function( i, val ) {
			sum = 0;
			// iterate through each td based on class and add the values
			$('#'+tableIds[t]+' .bp' + val).each(function() {
				var value = $(this).text();
				value = value.replace(/,/g, '');
				// add only if the value is number
				if(!isNaN(value) && value.length != 0) {
					sum += parseFloat(value);
				}
			});
			$('#'+tableIds[t]+' .cd' + val).html(localeString(sum.toFixed(2)));
		});
	}
}
$('.csvAll').live('click', function () {
	$('.dataTables_wrapper').find('.csvExport').trigger('click');
});
</script>

<?php
include 'footer.php';
?>