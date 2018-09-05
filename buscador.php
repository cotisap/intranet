<?php
include "head.php";

$empl = $_SESSION["salesPrson"];

$search = $_GET["search"];
echo $search;
?>

<form method="post" action="cotizaciones.php">
<table border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
  <tr>
  	<td colspan="2" class="qSec">Par&aacute;metros de b&uacute;squeda</td>
  </tr>
  <?php
  $queryEmps = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'Y' AND (U_manager = '$empl' OR SlpCode = '$empl') ORDER BY SlpName";
  $resultEmps = mssql_query($queryEmps);
  
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
	while($row = mssql_fetch_assoc($result))
	{
	  echo "<option value=".$row["SlpCode"].">".$row["SlpName"]."</option>";
	}
	echo "</optgroup>";
	echo "<optgroup label='Inactivos'>";
	while($rowIna = mssql_fetch_assoc($resultIna))
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
	} elseif (mssql_num_rows($resultEmps) > 0) { // Manager -> Employee relationship
	?>
    <tr>
      <td>Vendedor</td>
      <td>
      <?php		
	echo "<select multiple id='salesPerson' name='salesPerson[]' required class='selectMultiple'>";
	//display the results
	while($rowEmps = mssql_fetch_assoc($resultEmps))
	{
	  echo "<option value=".$rowEmps["SlpCode"].">".$rowEmps["SlpName"]."</option>";
	}
	echo "</select>";
	  ?>
        <button type="button" id="selectall">Todos</button>
        <button type="button" id="deselectall">Ninguno</button>
      </td>
    </tr>
    <?php	
	}
	?>
    <tr>
      <td>Fecha desde:</td>
      <td><input type="text" id="fromDate" name="fromDate" required></td>
    </tr>
    <tr>
      <td>Fecha hasta:</td>
      <td><input type="text" id="toDate" name="toDate" required></td>
    </tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Generar reporte" class="button green"></td>
    </tr>
    <tr>
  	  <td colspan="2" class="qSec">B&uacute;squeda directa por folio de referencia</td>
    </tr>
    <tr>
    	<td valign="top">Selecciona folio:</td>
        <td>
        	<select id="fSearch" name="fSearch">
            	<option value="" selected disabled>Selecciona...</option>
                <?php
				if ($_SESSION["admin"] == 'Y') {
					$queryF = "SELECT Id_Cot1, CardName FROM COTI WHERE company = '".$_SESSION["company"]."' ORDER BY Id_Cot1 DESC";
				} else {
					$queryF = "SELECT Id_Cot1, CardName FROM COTI WHERE Empl_Ven = '$empl' AND company = '".$_SESSION["company"]."' ORDER BY Id_Cot1 DESC";
				}
				$resultF = mysql_query($queryF);
				while ($rowF = mysql_fetch_array($resultF)) {
					echo "<option value='".$rowF["Id_Cot1"]."'>".$rowF["Id_Cot1"]." - ".$rowF["CardName"]."</option>";
				}
				?>
            </select>
            <br><br>
            <button type="button" class="button green" id="fSearchBT" disabled>Ir al folio</button>
        </td>
    </tr>
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
	$('#subHeaderTitle').html('Buscar cotizaciones');
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