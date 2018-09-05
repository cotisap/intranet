<?php
include 'head.php';

$idCot = $_REQUEST["idCot"];

$currencies = array();
$queryCur = "SELECT T1.ISOCurrCod, CASE WHEN T1.ISOCurrCod = 'MXN' THEN 1 ELSE T2.Rate END Rate FROM OCRN T1 LEFT JOIN ORTT T2 ON T1.ISOCurrCod = T2.Currency
WHERE T2.RateDate = CONVERT(DATE, GETDATE()) AND T1.ISOCurrCod = 'USD' OR T1.ISOCurrCod = 'MXN'";
$resultCur = mssql_query($queryCur);
while ($rowCur = mssql_fetch_assoc($resultCur)) {
	$currencies[] = $rowCur;
}

$queryMN = "SELECT TOP 1 CurrCode FROM OCRN WHERE ISOCurrCod = 'MXN'";
$resultMN = mssql_query($queryMN);
$rowMN = mssql_fetch_assoc($resultMN);
$sysMN = $rowMN["CurrCode"];

$queryQuote = "SELECT Codigo_SN, CardName, Serie, DiscPrcnt, tax, Total_MN, Total_USD, Comentarios, Empl_Ven, Id_Cot1, DocNum, TC, account, cnotes, dlvType, dlvPerson, dlvPhone, dlvEmail, dlvAddress, dlvFlet, company, DocCur, status FROM COTI WHERE Id_Cot1 = '$idCot'";
$resultQuote = mysql_query($queryQuote);
$rowQuote = mysql_fetch_assoc($resultQuote);

$DocNum = $rowQuote["DocNum"];
$QuoteStatus = $rowQuote["status"];
$DocCur = $rowQuote["DocCur"];
$DiscPrcnt = $rowQuote["DiscPrcnt"];
$tax = $rowQuote["tax"];
$CardCode = $rowQuote["Codigo_SN"];
$CardName = $rowQuote["CardName"];
$Empl_Ven = $rowQuote["Empl_Ven"];
$company = $rowQuote["company"];
$account = $rowQuote["account"];
$comments = $rowQuote["Comentarios"];
$cNotes = $rowQuote["cnotes"];
$dlvType = $rowQuote["dlvType"];
$dlvPerson = utf8_decode($rowQuote["dlvPerson"]);
$dlvPhone = $rowQuote["dlvPhone"];
$dlvEmail = $rowQuote["dlvEmail"];
$dlvAddress = utf8_decode($rowQuote["dlvAddress"]);
$dlvFlet = utf8_decode($rowQuote["dlvFlet"]);

$querySNData = mssql_query("SELECT E_Mail FROM OCRD WHERE CardCode = '$customer'");
$rowSNData = mssql_fetch_assoc($querySNData);

$email = $rowSNData["E_Mail"];

$querySlp = mssql_query("SELECT SlpName FROM OSLP WHERE SlpCode = '".$rowQuote["Empl_Ven"]."'");
$rowSlp = mssql_fetch_assoc($querySlp);

$SlpName = $rowSlp["SlpName"];
?>
<div id="newQuote">
<form method="post" enctype="multipart/form-data" name="quote" id="quote">
<table class="formTable">
  <tbody>
    <tr>
    	<td colspan="2" class="pageTitle" align="right">Cotizaci&oacute;n <?php echo $idCot; ?><input id="idCot" name="idCot" type="hidden" value="<?php echo $idCot; ?>"></td>
        <input type="hidden" id="company" name="company" value="<?php echo $company; ?>">
    </tr>
    <tr>
    	<td colspan="2" class="qSec">
    	<div class="fullWidth third">
			<div>Vendedor</div>
			<div>&nbsp;</div>
			<div>Oferta de Venta</div>
		</div>
   		</td>
    </tr>
    <tr>
		<td colspan="2">
		<div class="fullWidth third">
			<div><input type="text" readonly id="Empl_Ven" name="Empl_Ven" value="<?php echo $Empl_Ven; ?>"></div>
			<div><input type="text" readonly id="Empl_Name" name="Empl_Name" value="<?php echo $SlpName; ?>"></div>
			<div><input type="text" readonly id="DocNum" name="DocNum" value="<?php echo $DocNum; ?>"></div>
		</div>
		</td>
	</tr>
  	<tr>
    	<td colspan="2" class="qSec">Cliente *</td>
    </tr>
    <tr>
      <td colspan="2">
      	<table class="formTable">
        	<tr>
            	<td class="tdCardCode">
                	<input type="text" id="CardCode" name="CardCode" class="CardCode" required placeholder="CODIGO" value="<?php echo $CardCode; ?>">
                </td>
                <td class="tdCardName">
                    <input type="text" id="CardName" name="CardName" class="CardName" required placeholder="NOMBRE" value="<?php echo $CardName; ?>">
                </td>
                <td width="20px" align="right" class="bpDetailTrigger"><img src="images/dropDown.png"></td>
            </tr>
            <tr>
            	<td colspan="3">
                	<!-- Credit Info -->
                    <div class="fullWidth third">
                        <div>
                            <strong>Informaci&oacute;n de cr&eacute;dito</strong>
                        </div>
                    </div>
                    <div class="fullWidth sixth">
                        <div>
                            L&iacute;mite de cr&eacute;dito (MN)<br>
                            <input type="text" id="creditLimit" name="creditLimit" readonly>
                        </div>
                        <div>
                            Saldo deudor (MN)<br>
                            <input type="text" id="balance" name="balance" readonly>
                        </div>
                        <div>
                            Cr&eacute;dito disponible (MN)<br>
                            <input type="text" id="available" name="available" readonly>
                        </div>
                       <div>
                            D&iacute;as de cr&eacute;dito<br>
                            <input type="text" id="credDays" name="credDays" readonly>
                        </div>
                        <div>
                            Fecha &uacute;ltimo pago<br>
                            <input type="text" id="lastDate" name="lastDate" readonly>
                        </div>
                        <div>
                            Monto &uacute;ltimo pago<br>
                            <input type="text" id="lastAmmount" name="lastAmmount" readonly>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
            	<td colspan="3">
                	<div id="bpDetail">
                    	<?php include "modules/bpDetails.php"; ?>
                    </div>
                </td>
            </tr>
        </table>
      </td>
    </tr>
    <tr>
    	<td colspan="2" class="qSec">Art&iacute;culos cotizados <span class="sDocCur"><select class='DocCur' name="DocCur" id="DocCur" required disabled>
                                	<option value='' selected disabled>Selecciona...</option>
                                    <?php
									foreach($currencies as $currency) {
										echo "<option value='".$currency["ISOCurrCod"]."'>".$currency["ISOCurrCod"]."</option>";
									}
									?>
                                </select><input type="hidden" name="sysCur" id="sysCur"></span>
        </td>
    </tr>
    <tr>
      <td colspan="2">
        <div id="itemContainer" class="itemContainer">
        	<div class="itemLeft">
                <table class="itemListHead" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td>Art&iacute;culo</td>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="itemRight">
                <table class="itemListHead" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="tdlPrice" style="text-align:left !important;">Precio lista</td>
                            <td class="tdCurrency">Moneda</td>
                            <td class="tdDiscount">Factor</td>
                            <td class="tduPrice">Precio unitario</td>
                            <td class="tdQuant">Cantidad</td>
                            <td class="tdnPrice">Importe</td>
                            <td class="tdRemove">&nbsp;</td>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- Item append -->
        </div>
	  </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<!-- Totals -->
        	<table width="300px" border="0" cellspacing="0" cellpadding="0" class="quoteTotals" align="right">
              <tbody>
                <tr>
					<td colspan="2" class="qSec">Totales (<span class="chCur"></span>)</td>
                </tr>
                <tr>
                  <td width="50%">Subtotal antes de descuento $</td>
                  <td><div id="sub" style='display: inline-block;'></div> <span class="chCur"></span></td>
                </tr>
                <tr>
					<td>Descuento <input type="number" min="0" max="100" step="any" id="gDiscP" name="gDiscP" class="gDiscP" style='display: inline-block; width:60px;' value="<?php echo $DiscPrcnt; ?>" <?php if($_SESSION["discounts"] != "Y") {echo "readonly";}?>>% $</td>
                  <td><div id="gDisc" style='display: inline-block;'></div> <span class="chCur"></span></td>
                </tr>
                <tr>
                  <td>Subtotal $</td>
                  <td><div id="subDisc" style='display: inline-block;'></div> <span class="chCur"></span></td>
                </tr>
                <tr>
                  <td>IVA
                  <select name="ivaP" id="ivaP" style='display: inline-block; width:60px;'>
                      <?php
					  $queryTax = "SELECT Code, Rate FROM OSTC WHERE ValidForAR = 'Y' AND Lock = 'N'";
					  if($_SESSION["export"] != "Y") {
						  $queryTax.= " AND Rate = 16";  
					  }
					  $resultTax = mssql_query($queryTax);
					  while($rowTax = mssql_fetch_assoc($resultTax)) {
						  echo "<option value='".number_format($rowTax["Rate"], 2, ".", "")."' data-code='".$rowTax["Code"]."'>".number_format($rowTax["Rate"], 2, ".", "")."</option>";
					  }
					  ?>
                  </select>% $<input type="hidden" id="sysTax" name="sysTax"></td>
                  <td><div id="iva" style='display: inline-block;'></div> <span class="chCur"></span></td>
                </tr>
                <tr>
                  <td>Total $</td>
                  <td><div id="tot" style='display: inline-block;'></div> <span class="chCur"></span><input type="hidden" id="DocTotal" name="DocTotal" readonly></td>
                </tr>
              </tbody>
            </table>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
        <div class="fullWidth">
        	<div class="halfFirst">
            	<table class="formTable">
                	<tr>
                    	<td class="qSec">Notas comerciales *</td>
                    </tr>
                    <tr>
                    	<td>
                            <select id="comNots" class="comNots" name="comNots" required>
                                <option value="">Selecciona...</option>
                                <?php
                                $queryNotes = "SELECT Code, Name, U_conditions FROM [@CNOT] ORDER BY Name";
                                $resultNotes = mssql_query($queryNotes);
                                $comNotes = Array();
                                while ($rowNotes = mssql_fetch_assoc($resultNotes)) {
                                    echo "<option value='".$rowNotes["Code"]."'>".$rowNotes["Name"]."</option>";
                                    $comNotes[$rowNotes["Code"]] = utf8_encode($rowNotes["U_conditions"]); ?>
                                    <script>
                                    var jComNotes = <?php echo json_encode($comNotes); ?>;
                                    </script>
                                <?php
                                };
                                ?>
                            </select>
                            <div id="comNotes" class="comNotes"></div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="halfLast">
            	<table class="formTable">
                	<tr>
                    	<td class="qSec">Cuenta de pago *</td>
                    </tr>
                    <tr>
                    	<td>
                        	<?php
								$queryBA = "SELECT AcctCode, AcctName, AccntntCod, U_account, U_clabe FROM OACT WHERE FatherNum = '".$_SESSION["FatherNum"]."' AND AccntntCod = '$account'";
                                $resultBA = mssql_query($queryBA);
								$rowBA = mssql_fetch_assoc($resultBA);
								echo $rowBA["AccntntCod"]." - ".$rowBA["AcctName"];
								echo "<input type='hidden' name='account' id='account' value='".$rowBA["AccntntCod"]."'>";
							?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        </td>
    </tr>
    <tr>
      <td colspan="2" class="qSec">Comentarios</td>
    </tr>
    <tr>
      <td colspan="2"><textarea id="remarks" name="remarks" maxlength="256"><?php echo $comments; ?></textarea></td>
    </tr>
    <tr>
    	<td colspan="2">
        	<ul class="buttonBar">
            	<li><button type="button" class="button red" id="btCancel" onClick="getback()"><i class="fa fa-hand-o-left" aria-hidden="true"></i> Regresar</button></li>
                <li><button type="button" class="button blue" onClick="window.open('pdf.php?idcot=<?php echo $idCot; ?>')"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Ver PDF</button></li>
        	</ul>
        </td>
    </tr>
  </tbody>
</table>
</form>
</div>

<div id="infoBar">
	<i class="fa fa-info-circle" aria-hidden="true"></i> Cotizaci&oacute;n <div id="iQNumber"><?php echo $idCot; ?></div> Partidas: <div id="iQLines"></div> Total: $<div id="iQTotal"></div> <span class="chCur"></span>
</div>


<script type="text/javascript">
var itemAttach = "<div class='attachFile'><input type='file' name='attach[]'> <i class='fa fa-trash-o delete' aria-hidden='true'></i></div>";

var addAttach = function() {
	$("#filesDiv").append(itemAttach);
};

$("#addAttach").on("click", addAttach);

// Remove Attachment Line
$(document).on("click", ".delete", function() {
	$(this).closest(".attachFile").hide("fast").delay(1000).queue(function(){$(this).remove();});
});

/// Document Currency
var DocCur = "";
<?php
foreach($currencies as $currency) {
	echo "var ".$currency["ISOCurrCod"]." = ".$currency["Rate"].";";
}
?>

$("#DocCur").change(function() {
	DocCur = $(this).val();
	$(".chCur").html(DocCur);
	if(DocCur == "USD") {
		$("#sysCur").val("USD");
	} else {
		$("#sysCur").val("<?php echo $sysMN; ?>");
	}
	$(".linePrice").each(function() {
		var ind = $(".linePrice").index(this);
		calculateLine(ind);
	});
})




<?php
$whCodes = array();
$whNames = array();
$queryWH = "SELECT WhsCode, LEFT(WhsName, 3) AS WhsName FROM OWHS WHERE Inactive <> 'Y' AND U_usable = 'Y' ORDER BY WhsCode ASC";
$resultWH = mssql_query($queryWH);
while ($rowWH = mssql_fetch_assoc($resultWH)) {
	$whCodes[] = $rowWH["WhsCode"];
	$whNames[] = $rowWH["WhsName"];
}

include_once "modules/itemLine.php";
?>


var getCardCode = function() {
	var name = $("#CardName").val();
	if (name != "") {
		$.ajax({
			type: "GET",
			url: "includes/cardCode.php?name="+encodeURI(name),
			dataType: "json",
			cache: false,
			success: function(CardCode){
				if (CardCode["CardCode"] != null) {
					$("#CardCode").val(CardCode["CardCode"]);
					getBPDetails();
				}
			}
		});
	}
};

var getBPDetails = function() {
	var code = $("#CardCode").val();
	if (code != "") {
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "includes/getBPDetails.php?bpCode="+encodeURI(code),  
			dataType: "json",
			cache: false,                
			success: function(bpDetail){
				// BP
				$("#CardName").val(bpDetail["CardName"]);
				$("#bpCode").val(bpDetail["CardCode"]);
				$("#bpName").val(bpDetail["CardName"]);
				$("#bpRFC").val(bpDetail["LicTradNum"]);
				$("#bpPhone").val(bpDetail["Phone1"]);
				$("#bpEmail").val(bpDetail["E_Mail"]);
				$("#bpWeb").val(bpDetail["IntrntSite"]);
				if(bpDetail["isSAP"] == "Y") {
					$(".CardCode").addClass("isSAP");
					$(".CardName").addClass("isSAP");
				} else {
					$(".CardCode").removeClass("isSAP");
					$(".CardName").removeClass("isSAP");
				}
				// CP
				$("#cpName").val(bpDetail["cpName"]);
				$("#cpPhone").val(bpDetail["cpPhone"]);
				$("#cpEmail").val(bpDetail["cpEmail"]);
				// Fiscal
				$("#bpBStreet").val(bpDetail["bStreet"]);
				$("#bpBCol").val(bpDetail["bCol"]);
				$("#bpBCity").val(bpDetail["bCity"]);
				$("#bpBCounty").val(bpDetail["bCounty"]);
				$("#bpBState").val(bpDetail["bState"]);
				$("#bpBCountry").val(bpDetail["bCountry"]);
				$("#bpBZip").val(bpDetail["bZip"]);
				// Envío
				$("#bpSStreet").val(bpDetail["sStreet"]);
				$("#bpSCol").val(bpDetail["sCol"]);
				$("#bpSCity").val(bpDetail["sCity"]);
				$("#bpSCounty").val(bpDetail["sCounty"]);
				$("#bpSState").val(bpDetail["sState"]);
				$("#bpSCountry").val(bpDetail["sCountry"]);
				$("#bpSZip").val(bpDetail["sZip"]);
				// Credit Info
				$("#creditLimit").val(localeString(bpDetail["CreditLine"]));
				$("#balance").val(localeString(bpDetail["Balance"]));
				$("#available").val(localeString(bpDetail["Available"]));
				$("#credDays").val(bpDetail["ExtraMonth"]+" meses, "+bpDetail["ExtraDays"]+" d\u00edas");
				$("#lastDate").val(bpDetail["DocDate"]);
				$("#lastAmmount").val(localeString(bpDetail["TrsfrSum"]));
			}
		});		
	}
};

// Show BP Details
$(document).on('click', '.bpDetailTrigger', function() {
	$("#bpDetail").slideToggle("fast");
	$(this).children("img").toggleClass("rotate");
});

//========== Calculate Subtotals
var calculateSubs = function() {
	var sub = 0;
	var gDiscP = $("#gDiscP").val();
	var gDisc = 0;
	var subDisc = 0;
	var ivaP = $("#ivaP").val(); 
	var iva = 0;
	var tot = 0;
	
	$(".lineDiscPrice").each(function() {
		sub += parseFloat(this.value);
	});
	gDisc = sub * gDiscP / 100;
	subDisc = sub - gDisc;
	iva = subDisc * ivaP / 100;
	tot = subDisc + iva;
	
	$("#sub").html(localeString(sub));
	$("#gDisc").html(localeString(gDisc));
	$("#subDisc").html(localeString(subDisc));
	$("#iva").html(localeString(iva));
	$("#tot").html(localeString(tot));
	$("#DocTotal").val(tot);
	$("#iQTotal").html(localeString(tot));
};

// Get Com Notes
var getComNotes = function() {
  $("#comNotes").html(jComNotes[$("#comNots").val()]);
};


$("#comNots").on('change', getComNotes);


$(document).ready(function() {
	getBPDetails();
	$('#listCotBT').addClass('active');
	$('#subHeaderTitle').html('Buscar cotizaciones');
	if($("#pContainer").children(".payment").length < 1) {
		$("#valPmnt").prop("disabled", true);
	}
	if($("#pContainer").children(".payment").length < 1) {
		$("#saOrder").prop("disabled", true);
	}
	getQuoteInfo();
	getSelectedProd();
	calculateSubs();
	$("#infoBar").draggable({
		cursor: "move"
	});
	$(".tdlPrice, .tdDiscount").hide();
});

// Get Quote info
var getQuoteInfo = function() {
	var DocCur = <?php echo json_encode($DocCur); ?>;
	var Tax = <?php echo json_encode($tax); ?>;
	var account = <?php echo json_encode($account); ?>;
	var cNotes = <?php echo json_encode($cNotes); ?>;
	var dlvType = <?php echo json_encode($dlvType); ?>;
	var dlvPerson = <?php echo json_encode($dlvPerson); ?>;
	var dlvAddress = <?php echo json_encode($dlvAddress); ?>;
	var dlvPhone = <?php echo json_encode($dlvPhone); ?>;
	var dlvEmail = <?php echo json_encode($dlvEmail); ?>;
	var dlvFlet = <?php echo json_encode($dlvFlet); ?>;
	$("#DocCur").val(DocCur).trigger("change");
	if(DocCur == "USD") {
		$("#sysCur").val("USD");
	} else {
		$("#sysCur").val("<?php echo $sysMN; ?>");
	}
	$("#ivaP").val(Tax).trigger("change");
	$("#comNots").val(cNotes).trigger("change");
	$("#account").val(account).trigger("change");
	$("#dlvType").val(dlvType).trigger("change");
	$("#dlvPerson").val(dlvPerson);
	$("#dlvAddress").val(dlvAddress);
	$("#dlvPhone").val(dlvPhone);
	$("#dlvEmail").val(dlvEmail);
	if (dlvType == "Ocurre" ) {
		$("#dlvFlet").val(dlvFlet);
	}
}

// Get selected products
var getSelectedProd = function() {
	var indexes = $("#itemContainer").children(".item").length;
	var prodCodeW = "";
	for (i = 0; i < indexes; i++) {
		$(".itemCode:eq("+i+")").val(jProdCodes[i]);
		$(".itemName:eq("+i+")").val(jProdNames[i]);
		$(".listPrice:eq("+i+")").val(jListPrices[i]).trigger("change");
		$(".currency:eq("+i+")").val(jCurrencies[i]).trigger("change");
		$(".discount:eq("+i+")").val(jDiscs[i]).trigger("change");
		$(".finalPriceDiv:eq("+i+")").html("$"+localeString(jFPrice[i]));
		$(".finalPrice:eq("+i+")").val(jFPrice[i]);
		$(".quantity:eq("+i+")").val(jQuant[i]).trigger("change");
		$(".linePriceDiv:eq("+i+")").html("$"+localeString(jNPrice[i]));
		$(".linePrice:eq("+i+")").val(jNPrice[i]);
		$(".uniMed:eq("+i+")").val(jUniMed[i]);
		$(".delivery:eq("+i+")").val(jDelivery[i]);
		$(".brand:eq("+i+")").html(jFirmName[i]);
		$(".FirmName:eq("+i+")").val(jFirmName[i]);

		$(".lineRemark:eq("+i+")").val(jRemark[i]);
		$(".lineDisc:eq("+i+")").val(jLDiscs[i]).trigger("change");
		getInventory(i);
	}
}

// Get payments
var getPayments = function() {
	var indexes = $("#pContainer").children(".payment").length;
	for (i = 0; i < indexes; i++) {
		$(".methodDiv:eq("+i+")").html(jMethods[i]);
		$(".vMethod:eq("+i+")").val(jMethods[i]);
		$(".ammountDiv:eq("+i+")").html(localeString(jAmmounts[i]));
		$(".vAmmount:eq("+i+")").val(localeString(jAmmounts[i]));
		$(".pCurrencyDiv:eq("+i+")").html(jPCurrencies[i]);
		$(".vCurrency:eq("+i+")").val(jPCurrencies[i]);
		$(".refDiv:eq("+i+")").html(jRefs[i]);
		$(".vRef:eq("+i+")").val(jRefs[i]);
		$(".pDateDiv:eq("+i+")").html(jPDates[i]);
		$(".vDate:eq("+i+")").val(jPDates[i]);
		$(".fileDiv:eq("+i+")").html("<a href='http://folium.idited.com/ftp/pagos/"+jFiles[i]+"' target='_blank'>"+jFiles[i]+"</a>");
		$(".vFile:eq("+i+")").val("http://folium.idited.com/ftp/pagos/"+jFiles[i]);
		$(".statusDiv:eq("+i+")").html(jStatus[i]);
	}
}
	
function validateSAP() {
	
};

// Calculate according to wich element was changed: List Price, Currency, Discount, Quantity; on each item
$(document).on("change", ".listPrice", function() {
	var ind = $(".listPrice").index(this);
	calculateLine(ind);
});
$(document).on("change", ".currency", function() {
	var ind = $(".currency").index(this);
	var lineCur = $(this).val();
	if(lineCur == "USD") {
		$(".lineCur:eq("+ind+")").val(lineCur);
	} else {
		$(".lineCur:eq("+ind+")").val("<?php echo $sysMN; ?>");
	}
	calculateLine(ind);
});
$(document).on("change", ".discount", function() {
	var ind = $(".discount").index(this);
	calculateLine(ind);
});
$(document).on("change", ".quantity", function() {
	var ind = $(".quantity").index(this);
	calculateLine(ind);
});
$(document).on("change", ".lastCur", function() {
	var ind = $(".lastCur").index(this);
	calculateLine(ind);
});
$(document).on("change", ".lineDisc", function() {
	var ind = $(".lineDisc").index(this);
	calculateLine(ind);
});
	
$("#gDiscP").on("change", calculateSubs);

$("#ivaP").on("change", function() {
	var TaxCode = $("option:selected", this).data("code");
	$("#sysTax").val(TaxCode);
	calculateSubs();
});
</script>



<?php

$queryLines = "SELECT Id_Cot1, lineNum, Codigo_Art, Nombre_Art, FirmName, CONCAT( Codigo_Art,  ' - ', Nombre_Art ) AS prodName, Cantidad, Moneda, Precio_Lista, UMV, Precio_Unidad, Factor, DiscPrcnt, Sub_Tot_Line, Almacen, TiempoEntrega, FechaCreacion, remarks FROM COT1 WHERE Id_Cot1 = '$idCot' ORDER BY lineNum ASC";
$resultLines = mysql_query($queryLines);
$prodCodes = Array();
$prodNames = Array();
$listPrices = Array();
$currencies = Array();
$discs = Array();
$lDiscs = Array();
$fPrice = Array();
$quant = Array();
$nPrice = Array();
$uniMed = Array();
$delivery = Array();
$FirmNames = Array();
$remark = Array();
while($rowLines = mysql_fetch_assoc($resultLines)){ 
	$prodCodes[] = utf8_encode($rowLines["Codigo_Art"]);
	$prodNames[] = utf8_encode($rowLines["Nombre_Art"]);
	$listPrices[] = $rowLines["Precio_Lista"];
	$currencies[] = $rowLines["Moneda"];
	$discs[] = $rowLines["Factor"];
	$lDiscs[] = $rowLines["DiscPrcnt"];
	$fPrice[] = $rowLines["Precio_Unidad"];
	$quant[] = $rowLines["Cantidad"];
	$nPrice[] = $rowLines["Sub_Tot_Line"];
	$uniMed[] = $rowLines["UMV"];
	$delivery[] = utf8_encode($rowLines["TiempoEntrega"]);
	$FirmNames[] = utf8_encode($rowLines["FirmName"]);
	$remark[] = utf8_encode($rowLines["remarks"]); ?>
	<script type="text/javascript">
	$("#itemContainer").append(itemLine);
	setLineNumber();
	var jProdCodes = <?php echo json_encode($prodCodes); ?>;
	var jProdNames = <?php echo json_encode($prodNames); ?>;
	var jListPrices = <?php echo json_encode($listPrices); ?>;
	var jCurrencies = <?php echo json_encode($currencies); ?>;
	var jDiscs = <?php echo json_encode($discs); ?>;
	var jLDiscs = <?php echo json_encode($lDiscs); ?>;
	var jFPrice = <?php echo json_encode($fPrice); ?>;
	var jQuant = <?php echo json_encode($quant); ?>;
	var jNPrice = <?php echo json_encode($nPrice); ?>;
	var jUniMed = <?php echo json_encode($uniMed); ?>;
	var jDelivery = <?php echo json_encode($delivery); ?>;
	var jFirmName = <?php echo json_encode($FirmNames); ?>;
	var jRemark = <?php echo json_encode($remark); ?>;
	</script>
<?php
};
?>
<script>
$("#CardCode, #CardName, .itemCode, .itemName, .listPrice, .quantity, .delivery, .lineDisc, .lineRemark, #gDiscP, #dlvPerson, #dlvAddress, #dlvPhone, #dlvEmail, #remarks").prop("readonly", true);
$("#DocCur, .currency, .discount, .uniMed, #comNots, #account, #ivaP, #dlvType").prop("disabled", true);
$("#addItem, .remove").hide();
</script>

<?php
include 'footer.php';
?>