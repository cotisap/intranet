<?php
include "mysqlconn.php";

$slCode = $_GET["slCode"];

$query = "SELECT id, title, link, file, active, for_customer, remarks FROM SLDR WHERE id = '$slCode'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);

?>
<script src="js/custom-file-input.js"></script>
<form action="includes/deleteslide.php?slCode=<?php echo $slCode; ?>" method="post" enctype="multipart/form-data" id="deleteslide" name="deleteslide">
    <input type="hidden" name="filename" value="<?php echo $row['file']; ?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
      <tbody>
        <tr>
            <td class="qSec">Eliminar slide No. <?php echo $slCode; ?></td>
            
        <tr>
          <td>T&iacute;tulo</td>
          <td>Link</td>
        </tr>
        <tr>
          <td><input type="text" id="uTitle" disabled="disabled" name="uTitle" required style="width:100%" value="<?php echo utf8_encode($row["title"]); ?>"></td>
          <td><input type="text" id="uLink" disabled="disabled" name="uLink" required style="width:100%" value="<?php echo utf8_encode($row["link"]); ?>"></td>
        </tr>
       
        <tr>
            <td align="center" colspan="2" class="qSec">¿Está seguro de eliminar este contenido?</td>
        </tr>
        
        <tr>
          <td align="left"><button type="button" class="button red" onClick="javascript:$(this).closest('.overlay').fadeOut('fast')">Cancelar</button></td>
          <td align="right"><button type="button" class="button green" onClick="deleteslide.submit();">Eliminar</button></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        
      </tbody>
    </table>
</form>
<script>
$(document).ready(function() {
    active = "<?php echo $row["active"]; ?>";
	if (active == "Y") {
		$("#uActive").prop("checked", true).val("Y");
	} else {
		$("#uActive").prop("checked", false).val("N");
	}
	client = "<?php echo $row["for_customer"]; ?>";
	if (client == "Y") {
		$("#uClient").prop("checked", true).val("Y");
	} else {
		$("#uClient").prop("checked", false).val("N");
	}
});
$("#uActive").change(function(){
	$(this).val(this.checked ? "Y" : "N");
});
$("#uClient").change(function(){
	$(this).val(this.checked ? "Y" : "N");
});
</script>