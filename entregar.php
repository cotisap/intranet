<?php
include "head.php";

$idDLV = $_GET["idDLV"];
$msg = $_GET["msg"];
if ($msg == "dlvsuccess") {
	echo "<style>#saveDlv { display:none }</style>";
}

$queryDlv = "SELECT NumAtCard, DocNum, DocEntry, CardCode, CardName, CONVERT(VARCHAR(10),DocDate,103) DocDate, U_ShipToContact, U_ShipToTelephone FROM ODLN WHERE DocNum = $idDLV";
$resultDlv = mssql_query($queryDlv);
$rowDlv = mssql_fetch_assoc($resultDlv);
$DocEntry = $rowDlv["DocEntry"];

$queryDlvC = "SELECT RefNum, DocNum, CardCode, CardName, DlvPerson, DlvPhone, DlvIdType, DlvId, DateCreated, EmpId, remarks FROM DLVS WHERE DocNum = '$idDLV'";
$resultDlvC = mysql_query($queryDlvC);
$rowDlvC = mysql_fetch_assoc($resultDlvC);

$queryLines = "SELECT LineNum, DocEntry, ItemCode, Dscription, Quantity FROM DLN1 WHERE DocEntry = '$DocEntry'";
$resultLines = mssql_query($queryLines);
?>

<style>
input {
	width:100%;
}
</style>

<p class="pageTitle">Entrega de almac&eacute;n No. <?php echo $idDLV; ?></p>
<form id="deliver" method="post" enctype="multipart/form-data">
<input type="hidden" id="numAtCard" name="numAtCard" value="<?php echo $rowDlv["NumAtCard"]; ?>">
<input type="hidden" id="docNum" name="docNum" value="<?php echo $rowDlv["DocNum"]; ?>">
<input type="hidden" id="CardCode" name="CardCode" value="<?php echo $rowDlv["CardCode"]; ?>">
<input type="hidden" id="CardName" name="CardName" value="<?php echo $rowDlv["CardName"]; ?>">
<table class="formTable">
  <tbody>
    <tr>
      <td width="50%">Cliente</td>
      <td>Fecha de documento</td>
    </tr>
    <tr>
      <td><input type="text" id="customer" name="customer" readonly value="<?php echo $rowDlv["CardCode"]; ?> - <?php echo $rowDlv["CardName"]; ?>"></td>
      <td><input type="text" id="DocDate" name="DocDate" readonly value="<?php echo $rowDlv["DocDate"]; ?>"></td>
    </tr>
    <tr>
      <td>Persona de contacto</td>
      <td>Tel&eacute;fono de contacto</td>
    </tr>
    <tr>
      <td><input type="text" id="CntPerson" name="CntPerson" readonly value="<?php echo $rowDlv["U_ShipToContact"]; ?>"></td>
      <td><input type="text" id="CntPhone" name="CntPhone" readonly value="<?php echo $rowDlv["U_ShipToTelephone"]; ?>"></td>
    </tr>
    <tr>
      <td class="qSec" colspan="2">Art&iacute;culos</td>
    </tr>
    <tr>
      <td colspan="2">
      <table class="reportTable">
		  <thead>
          	<th width="30px"></th>
            <th>C&oacute;digo de art&iacute;culo</th>
            <th>Descripci&oacute;n</th>
            <th>Cantidad</th>
		  </thead>
		  <tfoot>
          	<th></th>
            <th></th>
            <th></th>
            <th></th>
		  </tfoot>
		  <tbody>
		  <?php
          while ($rowLines = mssql_fetch_assoc($resultLines)) {
			  echo "<tr><td>".$rowLines["LineNum"]."</td><td>".$rowLines["ItemCode"]."</td><td>".$rowLines["Dscription"]."</td><td>".$rowLines["Quantity"]."</td></tr>";
		  }
          ?>
          </tbody>
      </table>
      </td>
    </tr>
    <tr>
      <td class="qSec" colspan="2">Datos de entrega</td>
    </tr>
    <tr>
      <td>Persona que recibe</td>
      <td>Tel&eacute;fono</td>
    </tr>
    <tr>
      <td><input type="text" id="DlvPerson" name="DlvPerson" value="<?php echo $rowDlvC["DlvPerson"]; ?>"></td>
      <td><input type="text" id="DlvPhone" name="DlvPhone" value="<?php echo $rowDlvC["DlvPhone"]; ?>"></td>
    </tr>
    <tr>
      <td>Tipo de documento</td>
      <td>Adjunta el archivo de evidencia</td>
    </tr>
    <tr>
      <td>
      	<select id="idType" name="idType">
        	<option value="" selected disabled>Selecciona...</option>
            <option value="Gu&iacute;a de embarque">Gu&iacute;a de embarque</option>
            <option value="INE">INE</option>
            <option value="Licencia">Licencia</option>
            <option value="Pasaporte">Pasaporte</option>
            <option value="Cartilla">Cartilla</option>
        </select>
        <input type="hidden" id="DlvIdType" name="DlvIdType" value="<?php echo $rowDlvC["DlvIdType"]; ?>">
      </td>
      <td><input type="file" name="file" id="file"></td>
    </tr>
    <tr>
      <td colspan="2">Comentarios</td>
    </tr>
    <tr>
      <td colspan="2"><textarea id="remarks" name="remarks"><?php echo $rowDlvC["remarks"]; ?></textarea></td>
    </tr>
    <tr>
      <td colspan="2">
      	<ul class="buttonBar">
        	<li><button type="reset" class="button red">Cancelar</button></li>
            <li><button type="button" class="button green" id="saveDlv" name="saveDlv">Entregar</button></li>
        </ul>
      </td>
    </tr>
  </tbody>
</table>
</form>

<script>
$(document).ready( function () {
	$("#deliver").validate({
		rules: {
			DlvPerson: {
				required: true,
				minlength: 5
			},
			DlvPhone: {
				required: true,
				minlength: 5
			},
			idType: {
				required: true
			},
			file: {
				required: true,
				extension: "jpg,jpeg,png,pdf,doc,docx"
			}
		}
	});
	
	
	$("#idType").change(function () {
		$("#DlvIdType").val($(this).find(":selected").text());
	});
	$("#idType").val("<?php echo $rowDlvC["DlvIdType"]; ?>").trigger("change");
});

$("#saveDlv").click(function () {
	$("#deliver").attr("action", "includes/deliver.php");
	$("#deliver").submit();
});
</script>

<?php
include "footer.php";
?>