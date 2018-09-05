<?php
include "mysqlconn.php";

$slCode = $_GET["slCode"];

$query = "SELECT id, title, link, file, active, for_customer, remarks FROM SLDR WHERE id = '$slCode'";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);

?>
<script src="js/custom-file-input.js"></script>
<form action="includes/editSlide.php?slCode=<?php echo $slCode; ?>" method="post" enctype="multipart/form-data" id="editSlide" name="editSlide">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
      <tbody>
        <tr>
            <td class="qSec">Editar slide No. <?php echo $slCode; ?></td>
            <td class="qSec" align="right">Interno <input type="checkbox" id="uActive" name="uActive"> 
            | Clientes 
              <input type="checkbox" id="uClient" name="uClient"></td>
        </tr>
        <tr>
          <td>T&iacute;tulo</td>
          <td>Link</td>
        </tr>
        <tr>
          <td><input type="text" id="uTitle" name="uTitle" required style="width:100%" value="<?php echo utf8_encode($row["title"]); ?>"></td>
          <td><input type="text" id="uLink" name="uLink" required style="width:100%" value="<?php echo utf8_encode($row["link"]); ?>"></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="file" name="uFile" id="uFile" class="inputfile inputfile-1"/>
            <label for="uFile"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Selecciona una imagen</span></label></td>
        </tr>
        <tr>
            <td colspan="2" class="qSec">Comentarios</td>
        </tr>
        <tr>
            <td colspan="2"><textarea id="uRemarks" name="uRemarks"><?php echo utf8_encode($row["remarks"]); ?></textarea></td>
        </tr>
        <tr>
          <td align="left"><button type="button" class="button red" onClick="javascript:$(this).closest('.overlay').fadeOut('fast')">Cancelar</button></td>
          <td align="right"><button type="button" class="button green" onClick="editSlide.submit();">Guardar</button></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"><div class="footNotes">Los formatos de imagen aceptados son JPG y PNG &uacute;nicamente.<br>El tama&ntilde;o de las im&aacute;genes debe ser 960px x 560px a una resoluci&oacute;n de 72dpi.<br>El peso del archivo no debe ser mayor a 500 KB.</div></td>
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