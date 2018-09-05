<form method="post" action="antSalCli.php">
<table border="0" cellspacing="0" cellpadding="0" class="formTable">
	<tr>
  	<td colspan="2" class="qSec">Par&aacute;metros de b&uacute;squeda</td>
  </tr>
  <tbody>
  	<?php
	  if ($_SESSION["admin"] == 'Y' || $isManager) {
	  ?>
    <tr>
      <td>Vendedor</td>
      <td>
      <?php
	  //declare the SQL statement that will query the database
	$query = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'Y' ";
	  if($isManager){
		  $query.= "AND (U_manager = '$empl' OR SlpCode = '$empl') ";
	  }
	  $query.= "ORDER BY SlpName";
	$queryIna = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'N' ";
	  if($isManager){
		  $queryIna.= "AND (U_manager = '$empl' OR SlpCode = '$empl') ";
	  }
	  $queryIna.= "ORDER BY SlpName";
	
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
    <tr>
      <td>Fecha de contabilizaci&oacute;n desde:</td>
      <td><input type="text" id="fromDate" name="fromDate" required></td>
    </tr>
    <tr>
      <td>Fecha de contabilizaci&oacute;n hasta:</td>
      <td><input type="text" id="toDate" name="toDate" required></td>
    </tr>
    <tr>
      <td>Informe a la fecha:</td>
      <td><input type="text" id="repDate" name="repDate" required></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Generar reporte" class="button green"></td>
    </tr>
  </tbody>
</table>
</form>

<script>
$(document).ready(function(){
	$("#selectall").click(function() {
		$("#salesPerson option").prop("selected", "selected");
	});   
	
	$("#deselectall").click(function() {
		$("#salesPerson option").removeAttr("selected");
	});
	
	$("#repDate").datepicker({
		maxDate: '0',
		dateFormat: "dd/mm/yy"
	});
	$("#fromDate").datepicker({
		numberOfMonths: 2,
		maxDate: '0',
		dateFormat: "dd/mm/yy",
		onClose: function(selectedDate) {
			$("#toDate").datepicker("option", "minDate", selectedDate);
		}
	});
	
	$("#toDate").datepicker({
		numberOfMonths: 2,
		maxDate: '0',
		dateFormat: "dd/mm/yy",
		onClose: function(selectedDate) {
			$("#fromDate").datepicker("option", "maxDate", selectedDate);
			$("#repDate").datepicker("option", "maxDate", selectedDate);
		}
	});
});
</script>