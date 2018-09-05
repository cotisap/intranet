<?php
session_start();
if ($_SESSION["admin"] != 'Y') {
	header("Location: no-auth.php");
	die();
}

include 'head.php';
?>

<link rel="stylesheet" type="text/css" href="css/selectfile/component.css" />

<div class="overlay">
    <div id="editSlideForm">
        <!-- Edit Slide Form -->
    </div>
</div>

<div class="reportContainer">
    <table class="reportTable">
        <thead><tr><th>ID</th><th>T&iacute;tulo</th><th>Archivo</th><th>Link</th>
        <th>Interno</th><th>Cliente</th><th>Editar</th><th>Eliminar</th></tr></thead>
        <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
        <tbody>
        <?php	
        $query = "SELECT id, title, link, file, active, for_customer, emp_id, created_at, remarks FROM SLDR WHERE company = '".$_SESSION["company"]."'";
        $result = mysql_query($query);
        //display the results 
        while($row = mysql_fetch_array($result)) {	
          echo "<tr><td>".$row["id"]."</td>
		  <td>".utf8_encode($row["title"])."</td>
		  <td>".utf8_encode($row["file"])."</td>
		  <td>".$row["link"]."</td>
		  <td>".$row["active"]."</td>
		  <td>".$row["for_customer"]."</td>
		  <td align='center'><img src='images/pencil.png' class='viewDetails' data-id='".$row["id"]."'></td>
		  <td align='center'><img src='images/remove-icon.png' class='removeDetails' data-id='".$row["id"]."'></td>
		  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<p>&nbsp;</p>
<form method="post" enctype="multipart/form-data" id="newSlide" name="newSlide">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
  	<tr>
    	<td class="qSec">Nuevo slide</td>
        <td class="qSec" align="right">Interno <input type="checkbox" id="sActive" name="sActive">
         |  Clientes          <input type="checkbox" id="sClient" name="sClient"></td>
    </tr>
    <tr>
      <td>T&iacute;tulo</td>
      <td>Link</td>
    </tr>
    <tr>
      <td><input type="text" id="sTitle" name="sTitle" style="width:100%"></td>
      <td><input type="text" id="sLink" name="sLink" style="width:100%"></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="file" name="sFile" id="sFile" class="inputfile inputfile-1"/>
        <label for="sFile"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Selecciona una imagen</span></label></td>
    </tr>
    <tr>
    	<td colspan="2" class="qSec">Comentarios</td>
    </tr>
    <tr>
    	<td colspan="2"><textarea id="remarks" name="remarks"></textarea></td>
    </tr>
    <tr>
      <td align="left"><input type="reset" value="Cancelar" class="button red"></td>
      <td align="right"><button type="button" class="button green" id="nSlide" name="nSlide">Guardar</button></td>
    </tr>
    <tr>
    	<td colspan="2"></td>
    </tr>
    <tr>
    	<td colspan="2"><div class="footNotes"><img src="images/info-icon.png">Los formatos de imagen aceptados son JPG y PNG &uacute;nicamente.<br>El tama&ntilde;o de las im&aacute;genes debe ser 960px x 560px a una resoluci&oacute;n de 72dpi.<br>El peso del archivo no debe ser mayor a 500 KB.</div></td>
    </tr>
  </tbody>
</table>
</form>

<script src="js/custom-file-input.js"></script>

<script type="text/javascript">
$(document).ready( function () {
	$('#slideBT').addClass('active');
	$('#subHeaderTitle').html('Slider');
	
	$("#newSlide").validate({
		rules: {
			sTitle: {
				required: true,
				minlength: 5
			},
			sLink: {
				required: true,
				minlength: 5
			},
			sFile: {
				extension: "jpg,png"
			}
		}
	});
});


$("#sActive").change(function(){
	$(this).val(this.checked ? "Y" : "N");
});

$("#sClient").change(function(){
	$(this).val(this.checked ? "Y" : "N");
});

$("#nSlide").click(function () {
	$("#newSlide").attr("action", "includes/adminslide.php");
	$("#newSlide").submit();
});
            
$(".viewDetails").click(function() {
	$(".overlay").fadeIn("fast");
	var slCode = $(this).attr("data-id");
	$.post("includes/editSlideForm.php?slCode="+slCode, function(data) {
		$("#editSlideForm").html(data);
	});
});
	
$(".removeDetails").click(function() {
	$(".overlay").fadeIn("fast");
	var slCode = $(this).attr("data-id");
	$.post("includes/deleteSlideForm.php?slCode="+slCode, function(data) {
		$("#editSlideForm").html(data);
	});
});
</script>

<?php include 'footer.php'; ?>