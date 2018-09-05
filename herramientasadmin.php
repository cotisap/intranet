<?php
session_start();
if ($_SESSION["admin"] != 'Y') {
	header("Location: no-auth.php");
	die();
}

include 'head.php';

$query = "SELECT T1.id, T1.title, T2.category, T3.brand, T1.file, T1.active, T1.emp_id, T1.created_at, T1.remarks FROM TOOL T1 JOIN TCAT T2 ON T1.category = T2.id JOIN TBNS T3 ON T1.brand = T3.id ORDER BY T2.category ASC";
$result = mysql_query($query);

//$querySP = "SELECT SlpName FROM OSLP WHERE SlpCode = $sp";
//$resultSP = mssql_query($querySP);
//$rowSN = mssql_fetch_array($resultSP);
	
echo "<div class='reportContainer'><table class='reportTable' width='100%'><thead><tr><th>Categor&iacute;a</th><th>Marca</th><th>T&iacute;tulo</th><th width='400px'>Archivo</th><th width='30px'></th></tr></thead><tfoot><tr><th></th><th></th><th></th><th></th><th></th></tr></tfoot><tbody>";
date_default_timezone_set('America/Mexico_City');
//display the results 
while($row = mysql_fetch_assoc($result)) {	
  echo "<tr><td>".utf8_encode($row["category"])."</td><td>".utf8_encode($row["brand"])."</td><td>".utf8_encode($row["title"])."</td><td>".utf8_encode($row["file"])."</td><td align='center'><img src='images/details.png' class='viewDetails'></td></tr>";
}
echo "</tbody></table></div>";
echo "<p></p>";
?>
<link rel="stylesheet" type="text/css" href="css/selectfile/component.css" />
<p>&nbsp;</p>
<form method="post" enctype="multipart/form-data" id="newToolForm" name="newToolForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
  	<tr>
    	<td colspan="3" class="qSec">Nueva herramienta</td>
    </tr>
    <tr>
      <td>T&iacute;tulo</td>
      <td>Categor&iacute;a</td>
      <td>Marca</td>
    </tr>
    <tr>
      <td><input type="text" id="title" name="title" style="width:100%"></td>
      <td>
      	<select id="category" name="category" class="category">
        	<option value="" selected disabled>Selecciona...</option>
            <?php
			$queryCat = "SELECT id, category FROM TCAT ORDER BY id ASC";
			$resultCat = mysql_query($queryCat);
			while ($rowCat = mysql_fetch_assoc($resultCat)) {
				echo "<option value='".$rowCat["id"]."'>".utf8_encode($rowCat["category"])."</option>";
			}
			?>
        </select>
      </td>
      <td>
      	<select id="brand" name="brand" class="brand">
        	<option value="" selected disabled>Selecciona...</option>
            <option value=" ">N/A</option>
            <?php
			$queryBrand = "SELECT id, brand FROM TBNS ORDER BY brand ASC";
			$resultBrand = mysql_query($queryBrand);
			while ($rowBrand = mysql_fetch_assoc($resultBrand)) {
				echo "<option value='".$rowBrand["id"]."'>".utf8_encode($rowBrand["brand"])."</option>";
			}
			?>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="3" align="center"><input type="file" name="file" id="file" class="inputfile inputfile-1" />
        <label for="file"><i class="fa fa-upload" aria-hidden="true"></i> <span>Selecciona un archivo</span></label>
        </td>
    </tr>
    <tr>
    	<td colspan="3" class="qSec">Descripci&oacute;n</td>
    </tr>
    <tr>
    	<td colspan="3"><textarea id="remarks" name="remarks" maxlength="250"></textarea></td>
    </tr>
    <tr>
      <td colspan="3">
      	<ul class="buttonBar">
        	<li><input type="reset" value="Cancelar" class="button red"></li>
            <li><button type="button" class="button green" id="newTool">Guardar</button></li>
        </ul>
      </td>
    </tr>
    <tr>
    	<td colspan="3"></td>
    </tr>
    <tr>
    	<td colspan="3"><div class="footNotes"><img src="images/info-icon.png">Los formatos de archivo aceptados son XLS, XLSX, DOC, DOCX, PPT, PPTX, PDF, JPG y PNG.<br>El peso m&aacute;ximo del archivo no debe ser mayor a 20 MB.</div></td>
    </tr>
  </tbody>
</table>

</form>
<script src="js/custom-file-input.js"></script>
<script>
$(document).ready( function () {
	$('#toolsBT').addClass('active');
	$('#subHeaderTitle').html('Herramientas');
	
	$("#newToolForm").validate({
		rules: {
			title: {
				required: true,
				minlength: 3
			},
			category: {
				required: true,
			},
			brand: {
				required: true,
			},
			file: {
				required: true,
				extension: "xls,xlsx,doc,docx,ppt,pptx,pdf,jpg,png"
			}
		}
	});
});

$("#newTool").click(function () {
	$("#newToolForm").attr("action", "includes/admintools.php");
	$("#newToolForm").submit();
});
</script>

<?php include 'footer.php'; ?>