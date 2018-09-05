<?php
include "includes/mssqlconn.php";
?>

<table class="reportTable">
	<thead>
    	<th>ID</th>
        <th>Porcentaje de descuento</th>
        <th>Default</th>
        <th></th>
    </thead>
    <tfoot>
    	<th></th>
        <th></th>
        <th></th>
        <th></th>
    </tfoot>
    <tbody>
    	<?php
		$query = "SELECT Code, Name, U_discount FROM [@DISCOUNT] ORDER BY Name ASC";
		$result = mssql_query($query);
		while($row = mssql_fetch_assoc($result)) {
			$checked = "";
			if ($row["U_discount"] == 1) {
				$checked = "checked";
			}
			echo "<tr><td class='idDisc'>".$row["Code"]."</td><td class='discount'>".$row["Name"]."</td><td><input type='radio' name='defaultDisc' class='defaultDisc' value='".$row["Name"]."' ".$checked."></td><td align='center'><img src='images/pencil.png' class='viewDetails' data-id='".$row["Code"]."'></td></tr>";
		}
		?>
    </tbody>
</table>

<form id="discounts">
<table class="formTable">
  <tbody>
    <tr>
      <td class="qSubSec">Nuevo descuento<input type="hidden" name="idDisc" id="idDisc"></td>
    </tr>
    <tr>
      <td>Porcentaje de descuento</td>
    </tr>
    <tr>
      <td><input type="number" required step="any" min="0" id="percent" name="percent"></td>
    </tr>
    <tr>
      <td>
      	<ul class="buttonBar">
        	<li><button type="button" class="button red" id="discCancel">Cancelar</button></li>
            <li><button type="button" class="button green" id="discSave">Guardar</button></li>
        </ul>
      </td>
    </tr>
  </tbody>
</table>
</form>

<script>
// Submit Form
$().ready(function() {
	$("#discounts").validate({
		rules: {
			percent: {
				required: true
			}
		},
		submitHandler: function(form) {
			$.post('includes/genadmin/discounts.php', $("#discounts").serialize())
			.done(function(data) {
				$.post("discounts.php", function(data) {
					$("#discountDiv").html(data + "<div class='inMessage'>Cambios guardados con &eacute;xito<div class='inCloseMessage'>[Aceptar]</div></div>");
				});
			})
			.fail(function(data) {
				console.log(data);
			});
			event.preventDefault();
		}
	});
});
$("#discCancel").click(function () {
	$.post("discounts.php", function(data) {
		$("#discountDiv").html(data);
	});
});
$("#discSave").click(function () {
	$("#discounts").submit();
});
$(".inCloseMessage").click(function() {
	$(this).closest(".inMessage").remove();
});

$(".viewDetails").click(function() {
	$("#idDisc").val($(this).closest("tr").find(".idDisc").html());
	$("#percent").val($(this).closest("tr").find(".discount").html());
});

// Default discount
$(".defaultDisc").change(function() {
	$.post("includes/genadmin/defaultDisc.php?disc="+$(this).val())
	.done(function(data) {
		$.post("discounts.php", function(data) {
			$("#discountDiv").html(data + "<div class='inMessage'>Cambios guardados con &eacute;xito<div class='inCloseMessage'>[Aceptar]</div></div>");
		});
	})
	.fail(function(data) {
		console.log(data);
	});
});

</script>