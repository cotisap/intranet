<?php
include "includes/mysqlconn.php";

$queryFile = "SELECT id, file, empl, dateCreated FROM PLST ORDER BY dateCreated DESC LIMIT 1";
$resultFile = mysql_query($queryFile);
$rowFile = mysql_fetch_assoc($resultFile);
$curFile = $rowFile["file"];

?>

<form id="pListForm" enctype="multipart/form-data" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>Lista de claves y productos actual: <a href="/ftp/prodList/<?php echo $curFile; ?>" target="_blank"><?php echo $curFile; ?></a></td>
    </tr>
    <tr>
      <td><label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></label> <input type="file" name="file" id="file" class="inputfile inputfile-1"/>
        </td>
    </tr>
    <tr>
      <td>
        <ul class="buttonBar">
        	<li><button type="button" class="button red" id="prodListCancel">Cancelar</button></li>
            <li><button type="button" class="button green" id="prodListSave">Guardar</button></li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</form>


<script>
$(document).ready( function () {	
	$("#pListForm").validate({
		rules: {
			file: {
				required: true,
				extension: "xls,xlsx"
			}
		}
	});
});

$("#prodListSave").click(function () {
	$("#pListForm").attr("action", "includes/genadmin/pList.php");
	$("#pListForm").submit();
});


$("#prodListCancel").click(function () {
	$.post("prodList.php", function(data) {
		$("#prodListDiv").html(data);
	});
});
</script>