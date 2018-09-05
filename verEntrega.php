<?php
include "head.php";

$entNum = $_GET["num"];

$queryEnt = "SELECT NumAtCard, DocNum, DocEntry, CONVERT(VARCHAR(10),DocDate,103) DocDate, CONVERT(VARCHAR(10),DocDueDate,103) DocDueDate, CardCode, CardName, Comments, DocTotal, (CASE DocStatus WHEN 'O' THEN 'Abierto' WHEN 'C' THEN 'Cerrado' END) DocStatus FROM ODLN WHERE DocNum = '$entNum'";
$resultEnt = mssql_query($queryEnt);
$rowEnt = mssql_fetch_assoc($resultEnt);

$customer = $rowEnt["CardCode"];
$DocEntry = $rowEnt["DocEntry"];
$totDoc = $rowEnt["DocTotal"];
$subDoc = $totDoc / 1.16;
$ivaDoc = $subDoc * .16;

$querySNData = "SELECT E_Mail FROM OCRD WHERE CardCode = '$customer'";
$resultSNData = mssql_query($querySNData);
$rowSNData = mssql_fetch_assoc($resultSNData);

$email = $rowSNData["E_Mail"];

$queryDlvReg = "SELECT DlvPerson, DlvPhone, DlvIdType, DlvId, remarks FROM DLVS WHERE DocNum = '$entNum' ORDER BY DateCreated DESC LIMIT 1";
$resultDlvReg = mysql_query($queryDlvReg);
$rowDlvReg = mysql_fetch_assoc($resultDlvReg);

?>
<style>
input {
	width:100%;
}
</style>

<p class="pageTitle">Entrega <?php echo $rowEnt["DocNum"]; ?></p>

<div class="fullWidth">
	<div class="thirdFirst">
    	<table class="formTable">
        	<tr>
            	<td>Cliente</td>
            </tr>
            <tr>
            	<td><input type="text" id="customer" name="customer" readonly value="<?php echo $rowEnt["CardCode"]." - ".$rowEnt["CardName"]; ?>"></td>
            </tr>
        </table>
    </div>
    <div class="thirdSecond">
    	<table class="formTable">
        	<tr>
            	<td>Folio referencia</td>
            </tr>
            <tr>
            	<td><input type="text" id="refNum" name="refNum" readonly value="<?php echo $rowEnt["NumAtCard"]; ?>">
                	<input type="hidden" id="invNum" name="invNum"  value="<?php echo $invNum; ?>">
                </td>
            </tr>
        </table>
    </div>
    <div class="thirdLast">
    	<table class="formTable">
        	<tr>
            	<td>Estatus</td>
            </tr>
            <tr>
            	<td><input type="text" id="status" name="status" readonly value="<?php echo $rowEnt["DocStatus"]; ?>"></td>
            </tr>
        </table>
    </div>
</div>
<div class="fullWidth">
	<div class="halfFirst">
    	<table class="formTable">
        	<tr>
            	<td>Fecha de documento</td>
            </tr>
            <tr>
            	<td><input type="text" id="docDate" name="docDate" readonly value="<?php echo $rowEnt["DocDate"]; ?>"></td>
            </tr>
        </table>
    </div>
    <div class="halfLast">
    	<table class="formTable">
        	<tr>
            	<td>Fecha de entrega</td>
            </tr>
            <tr>
            	<td><input type="text" id="dueDate" name="dueDate" readonly value="<?php echo $rowEnt["DocDueDate"]; ?>"></td>
            </tr>
        </table>
    </div>
</div>
<div class="fullWidth">
	<div class="halfFirst">
    	
    </div>
    <div class="halfLast">
    </div>
</div>
<div class="fullWidth">
	<div class="halfFirst">
    	
    </div>
    <div class="halfLast">
    </div>
</div>

<table class="formTable">
    <tr>
      <td class="qSec" colspan="3">Datos de entrega</td>
    </tr>
</table>

<div class="fullWidth">
	<div class="thirdFirst">
    	<table class="formTable">
            <tr>
                <td>Persona que recoge</td>
            </tr>
            <tr>
                <td><input type="text" id="DlvPerson" name="DlvPerson" readonly value="<?php echo $rowDlvReg["DlvPerson"]; ?>"></td>
            </tr>
        </table>
    </div>
    <div class="thirdSecond">
    	<table class="formTable">
            <tr>
                <td>Teléfono</td>
            </tr>
            <tr>
                <td><input type="text" id="DlvPhone" name="DlvPhone" readonly value="<?php echo $rowDlvReg["DlvPhone"]; ?>"></td>
            </tr>
        </table>
    </div>
    <div class="thirdLast">
    	<table class="formTable">
            <tr>
                <td>Identificación</td>
            </tr>
            <tr>
                <td><?php echo $rowDlvReg["DlvIdType"]; ?> - <a href="ftp/entregas/<?php echo $rowDlvReg["DlvId"]; ?>" target="_blank"><?php echo $rowDlvReg["DlvId"]; ?></a></td>
            </tr>
        </table>
    </div>
</div>






<table class="formTable">
    <tr>
      <td colspan="2">Comentarios de quien entrega</td>
    </tr>
    <tr>
      <td colspan="2"><textarea id="remarks" name="remarks" readonly><?php echo $rowDlvReg["remarks"]; ?></textarea></td>
    </tr>
</table>

<table class="formTable"><tr><td class="qSubSec">Artículos</td></tr></table>
<input type="hidden" name="DocEntry" value="<?php echo $DocEntry; ?>">
<div class="fullWidth">
	<table class="reportTable">
    	<thead>
        	<th></th>
            <th>Art&iacute;culo</th>
            <th>Cantidad</th>
            <th>Importe</th>
        </thead>
        <tfoot>
        	<th></th>
            <th></th>
            <th></th>
            <th></th>
        </tfoot>
        <?php
		$queryLines = "SELECT BaseAtCard, LineNum, ItemCode, Dscription, Quantity, LineTotal FROM DLN1 WHERE DocEntry = '$DocEntry' ORDER BY LineNum ASC";
		$resultLines = mssql_query($queryLines);
		while ($rowLines = mssql_fetch_assoc($resultLines)) {
			echo "<tr><td class='lineNum'>".($rowLines["LineNum"] + 1)."</td><td>".$rowLines["ItemCode"]." - ".$rowLines["Dscription"]."</td><td>".number_format($rowLines["Quantity"], 2)."</td><td>".number_format($rowLines["LineTotal"], 2, ".", ",")."</td></tr>";
		}
		?>
    </table>
</div>

<div class="fullWidth">
    	<table width="300px" border="0" cellspacing="0" cellpadding="0" class="quoteTotals" align="right">
              <tbody>
                <tr>
                  <td colspan="2" class="qSec">Total MXN</td>
                </tr>
                <tr>
                  <td width="50%">Subtotal MXN</td>
                  <td>$ <?php echo number_format($subDoc, 2, ".", ","); ?></td>
                </tr>
                <tr>
                  <td>IVA MXN</td>
                  <td>$ <?php echo number_format($ivaDoc, 2, ".", ","); ?></td>
                </tr>
                <tr>
                  <td>Total MXN</td>
                  <td>$ <?php echo number_format($totDoc, 2, ".", ","); ?></td>
                </tr>
              </tbody>
            </table>
</div>

<table class="formTable"><tr><td class="qSubSec">Comentarios</td></tr></table>
<div class="fullWidth">
	<textarea id="comments" name="comments" readonly><?php echo $rowEnt["Comments"];?></textarea>
</div>



<div class="fullWidth">
	<ul class="buttonBar">
    	<li><button type="button" class="button red" id="cancel" name="cancel" onClick="getback();"><i class="fa fa-hand-o-left" aria-hidden="true"></i> Regresar</button></li>
    </ul>
</div>


<script>
// Show Email Overlay
$(document).on('click', '#showEmail', function() {
	$(".overlay").fadeIn("fast");
});
// Submit Forms
$().ready(function() {
	$("#sendInvoice").validate({
		rules: {
			email: {
				required: true,
				multiemail: true
			},
			subject: {
				required: true,
				minlength: 5
			},
			eMessage: {
				required: true,
				minlength: 5
			}
		}
	});
	
	//$("#quote").validate({
		
	//});
	
});
$("#sendEmail").click(function () {
	$("#sendInvoice").attr("action", "sendInvoice.php?idInv=<?php echo $invNum; ?>");
	$("#sendInvoice").submit();
});
</script>


<?php
include "footer.php";
?>