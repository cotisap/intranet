<?php
include "head.php";

$empl = $_SESSION["salesPrson"];
?>

<form method="get" action="entregasalmacen.php">
<table border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
  <tr>
  	<td colspan="2" class="qSec">Par&aacute;metros de b&uacute;squeda</td>
  </tr>
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
	location.href="/entregar.php?idDLV="+$("#dlvCode").val();
});
</script>
<?php
include "footer.php";
?>