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

<form method="get" action="entregas.php">
<table border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
  <tr>
  	<td colspan="2" class="qSec">Par&aacute;metros de b&uacute;squeda</td>
  </tr>
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
      <td>&nbsp;</td>
      <td><input type="submit" value="Generar reporte" class="button green"></td>
    </tr>
    <tr>
  	  <td colspan="2" class="qSec">B&uacute;squeda directa por folio de entrega</td>
    </tr>
    <tr>
    	<td valign="top">Selecciona folio:</td>
        <td>
        	<input type="text" class="dlvCode" id="dlvCode" name="dlvCode" placeholder="FOLIO"><input type="text" class="dlvCustomer" name="dlvCustomer" id="dlvCustomer" placeholder="CLIENTE" readonly>
            <br><br>
            <button type="button" class="button green" id="fSearchBT"><i class="fa fa-arrow-right" aria-hidden="true"></i> Ir al folio</button>
        </td>
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
});

var getCustomer = function() {
	var dlvCode = $("#dlvCode").val();
	if (dlvCode != "") {
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "includes/getDlvCustomer.php?dlvCode="+encodeURI(dlvCode),  
			dataType: "json",
			cache: false,                
			success: function(dlvCustomer){
				// BP
				$("#dlvCustomer").val(dlvCustomer["CardName"]);
			}
		});		
	}
};

$("#dlvCode").on("input", function() {
	$(this).autocomplete({
		minLength: 3,
		source: "includes/searchDelivery.php",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(this).val(ui.item.value);
			} else {
				$(this).val(ui.item.value);
			}
			getCustomer();
		},
		close: function() {
			getCustomer();
		}
	});
	if ($(this).val().length >= 3) {
		getCustomer();
	}
});

$("#fSearchBT").click(function() {
	location.href="/verEntrega.php?num="+$("#dlvCode").val();
});
</script>
<?php
include "footer.php";
?>