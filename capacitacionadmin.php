<link rel="stylesheet" type="text/css" href="css/selectfile/component.css" />

<script type="text/javascript">
$(document).ready( function () {
	$(".reportTable").dataTable({
		"bLengthChange": false,
		"bPaginate": false
	}).rowGrouping({
		bExpandableGrouping: true,
		bExpandSingleGroup: false,
		asExpandedGroups: []
	});
	GridRowCount();
});

function GridRowCount() {
	$('input.expandedGroup').remove();
	$('input.collapsedGroup').remove();

	$('.dataTables_wrapper').find('.dataTables_filter').append($('<input />', { 'type': 'button', 'class': 'expandedGroup clpsXpndBT collapsed green', 'value': 'Expandir todo' }));
	$('.dataTables_wrapper').find('.dataTables_filter').append($('<input />', { 'type': 'button', 'class': 'collapsedGroup clpsXpndBT expanded red', 'value': 'Colapsar todo' }));

	$('.expandedGroup').live('click', function () {
		$(this).parents('.dataTables_wrapper').find('.collapsed-group').trigger('click');
	});
	$('.collapsedGroup').live('click', function () {
		$(this).parents('.dataTables_wrapper').find('.expanded-group').trigger('click');
	});
};
</script>

<?php	
$query = "SELECT T1.id, T1.title, T2.category, T1.file, T1.active, T1.emp_id, T1.created_at, T1.remarks FROM TRNG T1 INNER JOIN CCAT T2 ON T1.category = T2.id ORDER BY T2.category ASC";
$result = mysql_query($query);

//$querySP = "SELECT SlpName FROM OSLP WHERE SlpCode = $sp";
//$resultSP = mssql_query($querySP);
//$rowSN = mssql_fetch_array($resultSP);
	
echo "<div class='reportContainer'><table class='reportTable' width='100%'><thead><tr><th>Categor&iacute;a</th><th>T&iacute;tulo</th><th width='400px'>Archivo</th><th width='30px'></th></tr></thead><tfoot><tr><th></th><th></th><th></th><th></th></tr></tfoot><tbody>";
date_default_timezone_set('America/Mexico_City');
//display the results 
while($row = mysql_fetch_array($result)) {	
  echo "<tr><td>".$row["category"]."</td><td>".$row["title"]."</td><td>".$row["file"]."</td><td align='center'><a href='' class='viewDetails'><img src='images/details.png'></a></td></tr>";
}
echo "</tbody></table></div>";
echo "<p></p>";
?>
<p>&nbsp;</p>
<form action="includes/admintools.php" method="post" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
  	<tr>
    	<td colspan="2" class="qSec">Nuevo documento</td>
    </tr>
    <tr>
      <td>T&iacute;tulo</td>
      <td>Categor&iacute;a</td>
    </tr>
    <tr>
      <td><input type="text" id="title" name="title" required style="width:100%"></td>
      <td>
      	<select id="category" name="category" class="category" required>
        	<option value="" selected disabled>Selecciona...</option>
            <?php
			$queryCat = "SELECT id, category FROM TCAT ORDER BY id ASC";
			$resultCat = mysql_query($queryCat);
			while ($rowCat = mysql_fetch_array($resultCat)) {
				echo "<option value='".$rowCat["id"]."'>".$rowCat["category"]."</option>";
			}
			?>
        </select>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="file" name="file" id="file" class="inputfile inputfile-1" />
        <label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Selecciona un archivo</span></label></td>
    </tr>
    <tr>
    	<td colspan="2" class="qSec">Descripci&oacute;n</td>
    </tr>
    <tr>
    	<td colspan="2"><textarea id="remarks" name="remarks" maxlength="250"></textarea></td>
    </tr>
    <tr>
      <td align="left"><input type="reset" value="Cancelar" class="button red"></td>
      <td align="right"><input type="submit" value="Guardar" class="button green"></td>
    </tr>
    <tr>
    	<td colspan="2"></td>
    </tr>
    <tr>
    	<td colspan="2"><div class="footNotes"><img src="images/info-icon.png">Los formatos de archivo aceptados son XLS, XLSX, DOC, DOCX, PPT, PPTX, PDF, JPG y PNG.<br>El peso m&aacute;ximo del archivo no debe ser mayor a 10 MB.</div></td>
    </tr>
  </tbody>
</table>

</form>

<script src="js/custom-file-input.js"></script>