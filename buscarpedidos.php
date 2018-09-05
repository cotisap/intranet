<?php
include "head.php";

$empl = $_SESSION["salesPrson"];
?>

<form method="get" action="pedidos.php">
<table border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
  <tr>
  	<td colspan="2" class="qSec">Par&aacute;metros de b&uacute;squeda</td>
  </tr>
  <?php
  if ($_SESSION["admin"] == 'Y') {
  ?>
    <tr>
      <td>Vendedor</td>
      <td>
      <?php
	  //declare the SQL statement that will query the database
	$query = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'Y' ORDER BY SlpName";
	$queryIna = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'N' ORDER BY SlpName";
	
	//execute the SQL query and return records
	$result = mssql_query($query);
	$resultIna = mssql_query($queryIna);
		
	echo "<select multiple id='salesPerson' name='salesPerson[]' required class='selectMultiple'>";
	//display the results
	echo "<optgroup label='Activos'>";
	while($row = mssql_fetch_array($result))
	{
	  echo "<option value=".$row["SlpCode"].">".$row["SlpName"]."</option>";
	}
	echo "</optgroup>";
	echo "<optgroup label='Inactivos'>";
	while($rowIna = mssql_fetch_array($resultIna))
	{
	  echo "<option value=".$rowIna["SlpCode"].">".$rowIna["SlpName"]."</option>";
	}
	echo "</optgroup>";
	echo "</select>";
	  //mssql_close($dbhandle);
	  ?>
        <button type="button" id="selectall">Todos</button>
        <button type="button" id="deselectall">Ninguno</button>
      </td>
    </tr>
    <?php
	}
	?>
      <td>&nbsp;</td>
      <td><input type="submit" value="Generar reporte" class="button green"></td>
    </tr>
    <!--<tr>
  	  <td colspan="2" class="qSec">B&uacute;squeda directa por folio de referencia</td>
    </tr>
    <tr>
    	<td valign="top">Selecciona folio:</td>
        <td>
        	<select id="fSearch" name="fSearch">
            	<option value="" selected disabled>Selecciona...</option>
                <?php
				if ($_SESSION["admin"] == 'Y') {
					$queryF = "SELECT NumAtCard, * FROM ORDR WHERE NumAtCard LIKE '200%'";
				} else {
					$queryF = "SELECT NumAtCard, * FROM ORDR WHERE NumAtCard LIKE '200%' AND SlpCode = '$empl'";
				}
				$resultF = mssql_query($queryF);
				while ($rowF = mssql_fetch_assoc($resultF)) {
					echo "<option value='".$rowF["NumAtCard"]."'>".$rowF["NumAtCard"]." - ".$rowF["CardName"]."</option>";
				}
				?>
            </select>
            <br><br>
            <button type="button" class="button green" id="fSearchBT" disabled>Ir al folio</button>
        </td>
    </tr>-->
  </tbody>
</table>
</form>

<script>
$(document).ready(function(){
	$("#fSearch").select2();
	
	$("#selectall").click(function() {
		$("#salesPerson option").prop("selected", "selected");
	});   
	
	$("#deselectall").click(function() {
		$("#salesPerson option").removeAttr("selected");
	});
	
	$("#fromDate").datepicker({
		numberOfMonths: 2,
		maxDate: '0',
		dateFormat: "dd/mm/yy",
		onClose: function(selectedDate) {
			$("#toDate").datepicker("option", "minDate", selectedDate);
			$("#toDate").datepicker("option", "maxDate", "0");
		}
	});
	
	$("#toDate").datepicker({
		numberOfMonths: 2,
		maxDate: '0',
		dateFormat: "dd/mm/yy",
		onClose: function(selectedDate) {
			$("#fromDate").datepicker("option", "maxDate", "0");
			$("#fromDate").datepicker("option", "maxDate", selectedDate);
		}
	});
	
	$('#listCotBT').addClass('active');
	$('#subHeaderTitle').html('Ver cotizaciones');
});

$("#fSearch").change(function() {
	$("#fSearchBT").removeAttr('disabled');
});

$("#fSearchBT").click(function() {
	var fCot = $("#fSearch").val();
	location.href='/vercotizacion.php?idCot='+fCot;
});
</script>
<?php
include "footer.php";
?>