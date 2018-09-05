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
	$('.dataTables_wrapper').find(".group").parent("tr").append("<td class='cDoc' align='center'></td><td class='bptot'></td><td class='bp0'></td><td class='bp30'></td>");
	
	// Count
	$('.dataTables_wrapper').find('[id|=group-id]').each(function () {
		var rowCount = $(this).nextUntil('[id|=group-id]').length;
		$(this).find(".cDoc").append($("<span />", { "class": "rowCount-grid" }).append($("<b />", { "text": rowCount + " documentos" })));
	});
	
	// BP Sum
	$('.dataTables_wrapper').find('[id|=group-id]').each(function () {
		var bpSum = 0;
		var ranges = ['tot', '0', '30'];
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

<p class='reportTitle'>Resumen de comisiones</p>

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
	$query = "DECLARE @P1 varchar(30) = '$fromDate'; --fecha de contabilización desde
	DECLARE @P2 varchar(30) = '$toDate'; --fecha de contabilización hasta
	DECLARE @P3 varchar(2) = '$sp' --$salesPerson --Vendedor
	
	SELECT          T0.[transid] AS Documento,
                Max(T0.[shortname]) as CodSN, 
                Max(T0.[transtype]) as Tipodoc,
                Max(T0.[createdby]) as NoPago,
                Max(T0.[refdate]) as [Fecha Contabilización],
                Max(T0.[duedate])as fechavencimiento,
                Max(T0.[taxdate]) as fechadocumento,
                Max(T0.[balduecred]) + Sum(T1.[reconsum]) as PagoMN,
				Max(T0.[balsccred])  + Sum(T1.[reconsumsc]) as PagoUSD,
                Max(T5.[cardname]) as NombreSN,
                Max(T7.[slpcode]) as CodVend,
                Max(T4.[currency]) as Moneda,
                Max(T7.[slpname] )as Vendedor
FROM            [dbo].[jdt1] T0
				INNER JOIN      [dbo].[itr1] T1 ON T1.[transid] = T0.[transid] AND T1.[transrowid] = T0.[line_id]
				INNER JOIN      [dbo].[oitr] T2 ON T2.[reconnum] = T1.[reconnum]
				INNER JOIN      [dbo].[ojdt] T3 ON T3.[transid] = T0.[transid]
				INNER JOIN      [dbo].[ocrd] T4 ON T4.[cardcode] = T0.[shortname]
				INNER  JOIN [dbo].[OSLP]/**empleados de Ventas**/ T7  ON  T7.[SlpCode] = T4.[SlpCode]
		        LEFT OUTER JOIN [dbo].[b1_journaltranssourceview] T5 ON T5.[objtype] = T0.[transtype]
				AND              T5.[docentry] = T0.[createdby]
				AND             (T5.[transtype] <> N'I'
                OR              (T5.[transtype] = N'I'
                AND              T5.[instlmntid] = T0.[sourceline] ))
				LEFT OUTER JOIN [dbo].[oslp] T6 ON T6.[slpcode] = T5.[slpcode]
				OR              (
                                T6.[slpname] = N'-Ningún empleado del departament'
                AND             (
                                                T0.[transtype] = N'30'
                                OR              T0.[transtype] = N'321'
                                OR              T0.[transtype] = N'-5'
                                OR              T0.[transtype] = N'-2'
                                OR              T0.[transtype] = N'-3'
                                OR              T0.[transtype] = N'-4' ))
                                
 WHERE T0.[RefDate] >= (@P1)  --fecha de contabilización
 AND  T0.[RefDate] <= (@P2)  --fecha de contabilización
 AND  T4.[CardType] = ('C')  --tipo de socio de negocio C(cliente) (s)Proveedor
 AND  T0.[TransType] = '24'
 AND  T1.[IsCredit] = 'C'
 AND  T4.[SlpCode] = (@P3)



GROUP BY        T0.[transid],
                T0.[line_id],
                T0.[bplname]
                
HAVING          Max(T0.[balfccred]) <> - Sum(T1.[reconsumfc])
OR              Max(T0.[balduecred]) <>- Sum(T1.[reconsum])

UNION ALL

SELECT          T0.[transid],
                Max(T0.[shortname]),
                Max(T0.[transtype]),
                Max(T0.[createdby]),
                Max(T0.[refdate]),
                Max(T0.[duedate]),
                Max(T0.[taxdate]),
                Max(T0.[balduecred]) - Max(T0.[balduedeb]) as [Pago MN],
                Max(T0.[balsccred])  - Max(T0.[balscdeb]),
                Max(T3.[cardname]),
                Max(T7.[slpcode]),
                Max(T2.[currency]),
                Max(T7.[slpname])
FROM            [dbo].[jdt1] T0
			INNER JOIN      [dbo].[ojdt] T1 ON T1.[transid] = T0.[transid]
			INNER JOIN      [dbo].[ocrd] T2 ON T2.[cardcode] = T0.[shortname]
			INNER  JOIN [dbo].[OSLP]/**empleados de Ventas**/ T7  ON  T7.[SlpCode] = T2.[SlpCode]
			LEFT OUTER JOIN [dbo].[b1_journaltranssourceview] T3 ON T3.[objtype] = T0.[transtype]
			AND             T3.[docentry] = T0.[createdby]
			AND             (T3.[transtype] <> N'I'  OR     (T3.[transtype] = N'I'   AND   T3.[instlmntid] = T0.[sourceline] ))
			LEFT OUTER JOIN [dbo].[oslp] T4 ON T4.[slpcode] = T3.[slpcode]
			OR              (    T4.[slpname] = N'-Ningún empleado del departament'
                AND             (
                                                T0.[transtype] = N'30'
                                OR              T0.[transtype] = N'321'
                                OR              T0.[transtype] = N'-5'
                                OR              T0.[transtype] = N'-2'
                                OR              T0.[transtype] = N'-3'
                                OR              T0.[transtype] = N'-4' ))


WHERE T0.[RefDate] >= (@P1)  --fecha de contabilización--
 AND  T0.[RefDate] <= (@P2)  --fecha de contabilización
 AND  T2.[CardType] = ('C')  --socio de negocio
 AND  T0.[TransType] = '24'
 AND  T7.[SlpCode] = (@P3)
 AND             (
                                T0.[balduecred] <> T0.[balduedeb]
                OR              T0.[balfccred] <> T0.[balfcdeb] )
AND             NOT EXISTS
                (
                           SELECT     U0.[transid],
                                      U0.[transrowid]
                           FROM       [dbo].[itr1] U0
                           INNER JOIN [dbo].[oitr] U1
                           ON         U1.[reconnum] = U0.[reconnum]
                           WHERE      T0.[transid] = U0.[transid]
                           AND        T0.[line_id] = U0.[transrowid]

                           GROUP BY   U0.[transid],
                                      U0.[transrowid])
                                      
group BY        T0.[transid]";
	
	//execute the SQL query and return records
	$result = mssql_query($query);
	
	$querySPN = "SELECT SlpCode, SlpName, Commission FROM OSLP WHERE SlpCode = $sp";
	$resultSPN = mssql_query($querySPN);
	$rowSPN = mssql_fetch_array($resultSPN);
	$commission = $rowSPN["Commission"];
	
	
	echo "Vendedor: ".$rowSPN["SlpName"]."<br>";
		
	echo "<div class='reportContainer'><table class='reportTable' id='".$sp."'><thead><tr><th>Cliente</th><th>No. Transacci&oacute;n</th><th>No. Pago</th><th>Fecha de documento</th><th>Fecha de Vencimiento</th><th class='htot'>Total</th><th class='h0'>Subtotal documento</th><th class='h30'>Comisi&oacute;n</th></tr></thead><tfoot><tr><th></th><th></th><th></th><th></th><th></th><th class='cdtot'></th><th class='cd0'></th><th class='cd30'></th></tr></tfoot><tbody>";
	date_default_timezone_set('America/Mexico_City');
	//display the results 
	while($row = mssql_fetch_array($result))
	{
		$cntDate = date_create($row["fechadocumento"]);
		$dueDate = date_create($row["fechavencimiento"]);
		$tot = $row["PagoMN"];
		$sub = $tot / 1.16;
		$com = $sub / 100 * $commission;
		
	  echo "<tr><td>".$row["NombreSN"]."</td><td>".$row["Documento"]."</td><td>".$row["NoPago"]."</td><td>".date_format($cntDate, "d/m/Y")."</td><td>".date_format($dueDate, "d/m/Y")."</td><td class='dtot'>".number_format($tot, 2, '.', ',')."</td><td class='d0'>".number_format($sub, 2, '.', ',')."</td><td class='d30'>".number_format($com, 2, '.', ',')."</td></tr>";
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
		var ranges = ['tot','0','30'];
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