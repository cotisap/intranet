<?php
include "includes/mssqlconn.php";

$query = "SELECT code, name, U_email FROM [@EMLS]";
$result = mssql_query($query);

$codes = array();

?>
<style>
input {
	width:100%;
}
</style>
<form id="emails" method="post">
<table class="formTable">
  <tbody>
  	<?php
	while ($row = mssql_fetch_assoc($result)) {
		$codes[] = $row["code"];
		echo "<tr><td width='200px'>".utf8_encode($row["name"])."</td><td><input type='text' id='".$row["code"]."' name='".$row["code"]."' value='".$row["U_email"]."'></td></tr>";
	}
	?>
    <tr>
      <td colspan="2">
      	<ul class="buttonBar">
        	<li><button type="button" class="button red" id="emailsCancel">Cancelar</button></li>
            <li><button type="button" class="button green" id="emailsSave">Guardar</button></li>
        </ul>
      </td>
    </tr>
  </tbody>
</table>
</form>

<script>
// Submit Form
$().ready(function() {
	$("#emails").validate({
		rules: {
			<?php
			foreach ($codes as $code) {
				echo $code.": {
					required: false,
					multiemail: true
				},";
			}
			?>
		},
		submitHandler: function(form) {
			$.post('includes/genadmin/emails.php', $("#emails").serialize())
			.done(function(data) {
				$.post("emails.php", function(data) {
					$("#emailsDiv").html(data + "<div class='inMessage'>Cambios guardados con &eacute;xito<div class='inCloseMessage'>[Aceptar]</div></div>");
				});
			})
			.fail(function(data) {
				console.log(data);
			});
			event.preventDefault();
		}
	});
});
$("#emailsCancel").click(function () {
	$.post("emails.php", function(data) {
		$("#emailsDiv").html(data);
	});
});
$("#emailsSave").click(function () {
	$("#emails").submit();
});
$(".inCloseMessage").click(function() {
	$(this).closest(".inMessage").remove();
});
</script>