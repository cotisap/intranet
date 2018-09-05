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

<p class='reportTitle'>Monitor de avance</p>

<?php
if ($_SESSION["admin"] == "Y" || $isManager) {
	$salesPerson = $_GET["salesPerson"];
} else {
	$salesPerson = [$empl];
}

$fromDate = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $_GET["fromDate"])));
$toDate = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $_GET["toDate"])));
	
foreach ($salesPerson as $sp) {
	$query = "SELECT Id_Cot1, CONCAT(CardName, ' - ', Codigo_SN) AS Cliente, FechaCreacion, Empl_Ven FROM COTI
WHERE Empl_Ven = $sp
AND FechaCreacion >= '$fromDate'
AND FechaCreacion <= '$toDate'
ORDER BY FechaCreacion DESC";
	$result = mysql_query($query);
	
	$querySP = "SELECT SlpName FROM OSLP WHERE SlpCode = $sp";
	$resultSP = mssql_query($querySP);
	$rowSN = mssql_fetch_array($resultSP);
	
	
	echo "Vendedor: ".$rowSN["SlpName"]."<br>";
		
	echo "<div class='reportContainer'>
			<table class='reportTable trackTable' id='".$sp."'>
			<thead>
			<tr>
				<th>Folio Ref.</th>
				<th>Fecha cotizaci&oacute;n</th>
				<th>Oferta de venta SAP</th>
				<th>Pago</th>
				<th>Pedido solicitado</th>
				<th>En camino</th>
				<th>Entregado</th>
				<th>Factura</th>
				<th>Sigla 03</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			</tfoot>
			<tbody>";
	//display the results 
	while($row = mysql_fetch_assoc($result)) {
		  echo "<tr><td>".$row["Id_Cot1"]."</td><td>".$row["FechaCreacion"]."</td><td colspan='7'>
				<div class='trackBar' id='".$row["Id_Cot1"]."'>
					<ul>
						<li class='Q'><span>1</span></li>
						<li class='P'><span>2</span></li>
						<li class='R'><span>3</span></li>
						<li class='D'><span>4</span></li>
						<li class='C'><span>5</span></li>
						<li class='I'><span>6</span></li>
						<li class='S'><span>7</span></li>
					</ul>
				</div>
			</td></tr>";	
		$queryStages = "DECLARE @cotID varchar(30) = '%".$row["Id_Cot1"]."%';
		SELECT (SELECT COUNT(*) FROM OQUT WHERE NumAtCard LIKE @cotID AND DocDate > '2016-01-01 00:00:00.000') Q,
		(SELECT COUNT(*) FROM ORCT WHERE CounterRef LIKE @cotID AND DocDate > '2016-01-01 00:00:00.000') P,
		(SELECT COUNT(*) FROM ORDR WHERE NumAtCard LIKE @cotID AND DocDate > '2016-01-01 00:00:00.000') R,
		(SELECT TOP 1 DocNum FROM ORDR WHERE NumAtCard LIKE @cotID AND DocDate > '2016-01-01 00:00:00.000') Rnum,
		(SELECT COUNT(*) FROM ODLN WHERE NumAtCard LIKE @cotID AND DocDate > '2016-01-01 00:00:00.000') D,
		(SELECT TOP 1 DocNum FROM ODLN WHERE NumAtCard LIKE @cotID AND DocDate > '2016-01-01 00:00:00.000') Dnum,
		(SELECT COUNT(*) FROM ODLN WHERE NumAtCard LIKE @cotID AND DocStatus = 'C' AND DocDate > '2016-01-01 00:00:00.000') C,
		(SELECT TOP 1 DocNum FROM ODLN WHERE NumAtCard LIKE @cotID AND DocStatus = 'C' AND DocDate > '2016-01-01 00:00:00.000') Cnum,
		(SELECT COUNT(*) FROM OINV WHERE NumAtCard LIKE @cotID AND DocDate > '2016-01-01 00:00:00.000') I,
		(SELECT TOP 1 DocNum FROM OINV WHERE NumAtCard LIKE @cotID AND DocDate > '2016-01-01 00:00:00.000') Inum";
		$resultStages = mssql_query($queryStages);
		//var_dump($resultStages);
		$rowStages = mssql_fetch_assoc($resultStages);		
		?>
        <script type="text/javascript">
		var stages = <?php echo json_encode($rowStages); ?>;
		console.log(stages);
		var docLink = "";
		for (var key in stages) {
			if (stages[key] != 0) {
				switch (key) {
					case "Q":
						docLink = "<a href='vercotizacion.php?idCot=<?php echo $row["Id_Cot1"]; ?>'>4</a>";
						break;
					case "P":
						docLink = "2";
						break;
					case "R":
						docLink = "3";
						break;
					case "D":
						docLink = "<a href='verEntrega.php?num=<?php echo $rowStages["Dnum"]; ?>'>4</a>";
						break;
					case "C":
						docLink = "<a href='verEntrega.php?num=<?php echo $rowStages["Dnum"]; ?>'>5</a>";
						break;
					case "I":
						docLink = "<a href='verfactura.php?num=<?php echo $rowStages["Inum"]; ?>'>6</a>";
						break;
					case "S":
						docLink = "7";
						break;
				}
				$("#<?php echo $sp; ?>").find("#<?php echo $row["Id_Cot1"]; ?>").find("."+key).find("span").addClass("adv");
				$("#<?php echo $sp; ?>").find("#<?php echo $row["Id_Cot1"]; ?>").find("."+key).find("span").html(docLink);
				stages[key] = 0;
			}
		}	
		</script>
        <?php
		
		}
	echo "</tbody></table></div>";
	echo "<p></p>";
}
?>



<?php include 'footer.php'; ?>