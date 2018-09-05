<?php
include "head.php";

$invNum = $_GET["num"];

$queryInv = "SELECT NumAtCard, DocNum, DocEntry, EDocNum, CardCode, CardName, DocDate, DocDueDate, DocTotal, Comments FROM OINV WHERE DocNum = '$invNum'";
$resultInv = mssql_query($queryInv);
$rowInv = mssql_fetch_assoc($resultInv);

$DocEntry = $rowInv["DocEntry"];
$customer = $rowInv["CardCode"];
$dateFolder = date('Y-m', strtotime(str_replace('/', '-', $rowInv["DocDate"])));
$totDoc = $rowInv["DocTotal"];
$subDoc = $totDoc / 1.16;
$ivaDoc = $subDoc * .16;

$querySNData = "SELECT E_Mail FROM OCRD WHERE CardCode = '$customer'";
$resultSNData = mssql_query($querySNData);
$rowSNData = mssql_fetch_assoc($resultSNData);

switch ($_SESSION["company"]) {
	case "fg":
		$idComp = "0010000102";
		break;
	case "alianza":
		$idComp = "0010000100";
	//$myDB = "INTRANET_FG_AL";//Test DB
		break;
	case "sureste":
		$idComp = "0010000101";
		break;
	case "pacifico":
		$idComp = "0010000103";
		break;
}


$url = "http://corporativo-vallejo-tntchvdtpq.dynamic-m.com:8082/".$idComp."/".$dateFolder."/".$rowInv["CardCode"]."/IN/".$rowInv["EDocNum"];

?>

 <p class="pageTitle">Factura <?php echo $rowInv["DocNum"]; ?></p>

<form id="invLines" method="post" action="">

<div class="fullWidth third">
	<div>
    	<table class="formTable">
        	<tr>
            	<td>Cliente</td>
            </tr>
            <tr>
            	<td><input type="text" id="customer" name="customer" readonly value="<?php echo $rowInv["CardCode"]." - ".$rowInv["CardName"]; ?>"></td>
            </tr>
        </table>
    </div>
    <div>
    	<table class="formTable">
        	<tr>
            	<td>Folio referencia</td>
            </tr>
            <tr>
            	<td><input type="text" id="refNum" name="refNum" readonly value="<?php echo $rowInv["NumAtCard"]; ?>">
                	<input type="hidden" id="invNum" name="invNum"  value="<?php echo $invNum; ?>">
                </td>
            </tr>
        </table>
    </div>
    <div>
    	<table class="formTable">
        	<tr>
            	<td>No. Documento electr&oacute;nico</td>
            </tr>
            <tr>
            	<td><input type="text" id="EDocNum" name="EDocNum" readonly value="<?php echo $rowInv["EDocNum"]; ?>"></td>
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
            	<td><input type="text" id="docDate" name="docDate" readonly value="<?php echo $rowInv["DocDate"]; ?>"></td>
            </tr>
        </table>
    </div>
    <div class="halfLast">
    	<table class="formTable">
        	<tr>
            	<td>Fecha de vencimiento</td>
            </tr>
            <tr>
            	<td><input type="text" id="dueDate" name="dueDate" readonly value="<?php echo $rowInv["DocDueDate"]; ?>"></td>
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


<table class="formTable"><tr><td class="qSubSec">Artículos</td></tr></table>
<input type="hidden" name="DocEntry" value="<?php echo $DocEntry; ?>">
<div class="fullWidth">
	<table class="reportTable">
    	<thead>
        	<th>#</th>
            <th>Art&iacute;culo</th>
            <th>Cantidad</th>
            <th>Importe</th>
            <th>Sigla 03</th>
        </thead>
        <tfoot>
        	<th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tfoot>
        <?php
		$queryLines = "SELECT BaseAtCard, LineNum, ItemCode, Dscription, Quantity, LineTotal, U_Sigla03 FROM INV1 WHERE DocEntry = '$DocEntry' ORDER BY LineNum ASC";
		$resultLines = mssql_query($queryLines);
		$readonly = "";
		if ($_SESSION["admin"] != "A" && $_SESSION["admin"] != "Y") {
			$readonly = "readonly";
		}
		while ($rowLines = mssql_fetch_assoc($resultLines)) {
			echo "<tr><td class='lineNum'>".($rowLines["LineNum"] + 1)."</td><td>".$rowLines["ItemCode"]." - ".$rowLines["Dscription"]."</td><td>".number_format($rowLines["Quantity"], 2)."</td><td>".number_format($rowLines["LineTotal"], 2, ".", ",")."</td><td><input type='text' class='s03' id='s03' name='s03[".$rowLines["LineNum"]."]' value='".$rowLines["U_Sigla03"]."' ".$readonly."></td></tr>";
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
	<textarea id="comments" name="comments" readonly><?php echo $rowInv["Comments"];?></textarea>
</div>

<div class="fullWidth">
	<ul class="buttonBar">
   		<li><button id="backButton" name="backButton" type="button" class="button red" onClick="getback();"><i class="fa fa-hand-o-left" aria-hidden="true"></i> Regresar</button></li>
        <li><button type="button" class="button blue" onClick="window.open('<?php echo $url; ?>.pdf')"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button></li>
        <li><button type="button" class="button blue" onClick="window.open('<?php echo $url; ?>.xml')"><i class="fa fa-file-code-o" aria-hidden="true"></i> XML</button></li>
    </ul>
</div>
</form>

<?php
include "footer.php";
?>