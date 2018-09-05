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

<form method="get" action="cotizaciones.php">
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
    <?php
				if ($_SESSION["admin"] == 'Y' || $isManager) {
		?>
					
	<tr>
     <!-- <td>Mayores a:<input type="radio" id="may100" name="may100" value="Y" ></td> -->
      <td>Mayores a:</td>
      <td><input type="text" id="montomin" name="montomin" ></td>
    </tr>
		<?php		} 
				?>
     
      <td>&nbsp;</td>
	  <td><button type="submit" class="button green"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Generar reporte</button></td>
    </tr>
    <tr>
  	  <td colspan="2" class="qSec">B&uacute;squeda directa por folio de referencia</td>
    </tr>
    <tr>
    	<td>Periodo: </td>
    	<td><input type="text" name="DSearch" id="DSearch" placeholder="Todo" value=""></td>
    </tr>
    <tr>
    	<td valign="top">Selecciona folio o cliente:</td>
        <td>
        	<select id="fSearch" name="fSearch"></select>
            <br><br>
            <button type="button" class="button green" id="fSearchBT" disabled><i class="fa fa-arrow-right" aria-hidden="true"></i> Ir al folio</button>
        </td>
    </tr>
  </tbody>
</table>
</form>

<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/css/daterangepicker.css" />

<script>
$(document).ready(function(){


/*
 *	@Author sergiomireles.com
 */

$("#fSearch").select2({
	placeholder: 'Ingresa el folio o cliente',
	minimumInputLength: 5,
	ajax: {
    	url: "/includes/ajaxSearch.php",
    	dataType: 'json',
    	delay: 250,
    	data: function (params) {
    		console.log($("#DSearch").val());
    			return {
        			q: params.term,
        			date: $("#DSearch").val() 
      			};
    	},
    	processResults: function (data) {
            return {
              results: data
            };
    	},
    	cache: true
  	}
});

$("#DSearch").daterangepicker({
    "locale": {
        "format": "YYYY/MM/DD",
        "separator": "-",
        "applyLabel": "Aceptar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Desde",
        "toLabel": "Hasta",
        "customRangeLabel": "Avanzada",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Deciembre"
        ]
    },
	"drops": "up"
});

$("#DSearch").val('Todos');

	
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