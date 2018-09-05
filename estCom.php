<?php
include 'head.php';

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
	$('.dataTables_wrapper').find("th").click(false);
	$('.dataTables_wrapper').find(".group").attr("colspan",3);
	$('.dataTables_wrapper').find(".group").parent("tr").append("<td class='cDoc' align='center'></td><td class='bptot'></td><td class='bp0'></td><td class='bp30'></td><td class='bp121'></td>");
	
	// Count
	$('.dataTables_wrapper').find('[id|=group-id]').each(function () {
		var rowCount = $(this).nextUntil('[id|=group-id]').length;
		$(this).find(".cDoc").append($("<span />", { "class": "rowCount-grid" }).append($("<b />", { "text": rowCount + " documentos" })));
	});
	
	// BP Sum
	$('.dataTables_wrapper').find('[id|=group-id]').each(function () {
		var bpSum = 0;
		var ranges = ['tot', '0', '30', '121'];
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

	$('.expandedGroup').live('click', function () {
		$(this).parents('.dataTables_wrapper').find('.collapsed-group').trigger('click');
	});
	$('.collapsedGroup').live('click', function () {
		$(this).parents('.dataTables_wrapper').find('.expanded-group').trigger('click');
	});
};
</script>

<p class='reportTitle'>Comisiones estimadas</p>

<?php
if ($_SESSION["admin"] == 'Y' || $isManager) {
	$salesPerson = $_POST["salesPerson"];
} else {
	$salesPerson = [$empl];
}
$fromDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $_POST["fromDate"])));
$toDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $_POST["toDate"])));
	
foreach ($salesPerson as $sp) {
		
	//declare the SQL statement that will query the database
	$query = "DECLARE @P1 varchar(30) = '$fromDate';
				DECLARE @P2 varchar(30) = '$toDate';
				DECLARE @P3 varchar(2) = '$sp';
				SELECT T1.CardCode, T1.CardName, T1.NumAtCard, T1.Series, T1.DocNum, T1.DocDate, T1.ExtraDays, T1.DocTotal, * FROM OINV T1 WHERE T1.DocDate >= (@P1) AND T1.DocDate <= (@P2) AND T1.SlpCode = (@P3) ORDER BY T1.DocDate DESC";
	
	//execute the SQL query and return records
	$result = mssql_query($query);
	
	$querySPN = "SELECT SlpCode, SlpName, Commission FROM OSLP WHERE SlpCode = $sp";
	$resultSPN = mssql_query($querySPN);
	$rowSPN = mssql_fetch_array($resultSPN);
	$commission = $rowSPN["Commission"];
	
	
	echo "Vendedor: ".$rowSPN["SlpName"]."<br>";
		
	echo "<div class='reportContainer'><table class='reportTable' id='".$sp."'><thead><tr><th>Cliente</th><th>No. Documento</th><th>Fecha de documento</th><th>Fecha límite de pago</th><th>Fecha de vencimiento de comisión</th><th class='htot'>Total</th><th class='h0'>Subtotal documento</th><th class='h30'>Comisi&oacute;n</th><th class='h121'>Comisión vencida</th></tr></thead><tfoot><tr><th></th><th></th><th></th><th></th><th></th><th class='cdtot'></th><th class='cd0'></th><th class='cd30'></th><th class='cd121'></th></tr></tfoot><tbody>";
	date_default_timezone_set('America/Mexico_City');
	//display the results 
	while($row = mssql_fetch_array($result))
	{
		$docDate = date_create($row["DocDate"]);
		$credDate = clone $docDate;
		$credDate->add(new DateInterval("P".$row["ExtraDays"]."D"));
		$dueDate = clone $credDate;
		$dueDate->add(new DateInterval("P15D"));
		$tot = $row["DocTotal"];
		$sub = $tot / 1.16;
		$com = $sub / 100 * $commission;
		$halfCom = $com / 2;
		
	  echo "<tr><td>".$row["CardName"]."</td><td>".$row["DocNum"]."</td><td>".date_format($docDate, "d/m/Y")."</td><td>".date_format($credDate, "d/m/Y")."</td><td>".date_format($dueDate, "d/m/Y")."</td><td class='dtot'>".number_format($tot, 2, '.', ',')."</td><td class='d0'>".number_format($sub, 2, '.', ',')."</td><td class='d30'>".number_format($com, 2, '.', ',')."</td><td class='d121'>".number_format($halfCom, 2, '.', ',')."</td></tr>";
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
		var ranges = ['tot','0','30','121'];
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
</script>

<?php
include 'footer.php';
?>