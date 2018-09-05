<form method="post" action="edoCta.php">
<table border="0" cellspacing="0" cellpadding="0" class="formTable">
	<tr>
  	<td colspan="2" class="qSec">Par&aacute;metros de b&uacute;squeda</td>
  </tr>
  <tbody>
    <tr>
      <td>Socio de negocios</td>
      <td>
      <?php
	  //declare the SQL statement that will query the database
	$query = "SELECT CardCode, CardName, (CardCode + ' - ' + CardName) as Name FROM OCRD WHERE VatStatus = 'Y' AND CardType = 'C'";
	$result = mssql_query($query);
		
	echo "<select id='bPartner' name='bPartner' required>";
	//display the results
	while($row = mssql_fetch_array($result))
	{
	  echo "<option value=".$row["CardCode"].">".$row["Name"]."</option>";
	}
	echo "</select>";
	  ?>
      </td>
    </tr>
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
	$("#bPartner").select2();
	
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