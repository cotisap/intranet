﻿<?php
include 'head.php'; 

$currencies = array();
$queryCur = "SELECT T1.ISOCurrCod, CASE WHEN T1.ISOCurrCod = 'MXN' THEN 1 ELSE T2.Rate END Rate FROM OCRN T1 LEFT JOIN ORTT T2 ON T1.ISOCurrCod = T2.Currency
WHERE T2.RateDate = CONVERT(DATE, GETDATE()) AND T1.ISOCurrCod = 'USD' OR T1.ISOCurrCod = 'MXN'";
$resultCur = mssql_query($queryCur);
while ($rowCur = mssql_fetch_assoc($resultCur)) {
	$currencies[] = $rowCur;
}
?>

<div id="newQuote">
<form method="post" name="quote" id="quote">
<input type="hidden" name="export" id="export" value="N">
<table class="formTable">
  <tbody>
  	<tr>
    	<td colspan="2" class="qSec">Cliente *</td>
    </tr>
    <tr>
      <td colspan="2">
      	<table class="formTable">
        	<tr>
            	<td class="tdCardCode">
                	<input type="text" id="CardCode" name="CardCode" class="CardCode" required placeholder="CODIGO">
                </td>
                <td class="tdCardName">
                    <input type="text" id="CardName" name="CardName" class="CardName" required placeholder="NOMBRE">
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
		<td colspan="2" class="qSec">Productos a cotizar <span class="sDocCur"><div class="curFlag">Selecciona moneda</div><select class='DocCur' name="DocCur" id="DocCur" required>
                                	<option value='' selected disabled>Selecciona...</option>
                                    <?php
									foreach($currencies as $currency) {
										echo "<option value='".$currency["ISOCurrCod"]."'>".$currency["ISOCurrCod"]."</option>";
									}
									?>
                                </select></span>
        </td>
    </tr>
    <tr>
      <td colspan="2">
        <div id="itemContainer" class="itemContainer">
        	<div class="itemLeft">
                <table class="itemListHead" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td>Art&iacute;culo *</td>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="itemRight">
                <table class="itemListHead" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <td class="tdlPrice" style="text-align:left !important">P. Lista*</td>
                            <td class="tdCurrency">Moneda*</td>
                            <td class="tdDiscount">Factor</td>
                            <td class="tduPrice">P. Venta</td>
                            <td class="tdQuant">Cantidad*</td>
                            <td class="tdnPrice">Importe</td>
                            <td class="tdRemove">&nbsp;</td>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- Item append -->
        </div>
        <div id="addItem" class="button blue"><i class="fa fa-plus" aria-hidden="true"></i> Agregar partida</div>
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
					<td>Descuento <input type="number" min="0" max="100" step="any" id="gDiscP" name="gDiscP" class="gDiscP" style='display: inline-block; width:60px;' value="0" <?php if($_SESSION["discounts"] != "Y") {echo "readonly";}?>>% $</td>
                  <td><div id="gDisc" style='display: inline-block;'></div> <span class="chCur"></span></td>
                </tr>
                <tr>
                  <td>Subtotal $</td>
                  <td><div id="subDisc" style='display: inline-block;'></div> <span class="chCur"></span></td>
                </tr>
                <tr>
                  <td>IVA <input type="number" min="0" max="100" step="any" id="ivaP" name="ivaP" class="ivaP" style='display: inline-block; width:60px;' value="16" <?php if($_SESSION["export"] != "Y") {echo "readonly";}?>>% $</td>
                  <td><div id="iva" style='display: inline-block;'></div> <span class="chCur"></span></td>
                </tr>
                <tr>
                  <td>Total $</td>
                  <td><div id="tot" style='display: inline-block;'></div> <span class="chCur"></span></td>
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
                                <option value="" selected disabled>Selecciona...</option>
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
							if ($_SESSION["admin"] == 'Y') {
							?>
                            <select id="account" name="account" required>
                                <option value="" selected disabled>Selecciona...</option>
                                <?php
								$queryBA = "SELECT AcctCode, AcctName, AccntntCod, U_account, U_clabe FROM OACT WHERE FatherNum = '1120-000-000' AND AccntntCod <> '' ORDER BY AccntntCod";
                                $resultBA = mssql_query($queryBA);
                                while ($rowBA = mssql_fetch_assoc($resultBA)) {
                                    echo "<option value='".$rowBA["AccntntCod"]."'>".$rowBA["AccntntCod"]." - ".$rowBA["AcctName"]."</option>";
                                }
                                ?>
                            </select>
                            <?php
							} else {
								$queryBA = "SELECT AcctCode, AcctName, AccntntCod, U_account, U_clabe FROM OACT WHERE FatherNum = '1120-000-000' AND AccntntCod = '".$_SESSION["branch"]."'";
                                $resultBA = mssql_query($queryBA);
								$rowBA = mssql_fetch_assoc($resultBA);
								echo $rowBA["AccntntCod"]." - ".$rowBA["AcctName"];
								echo "<input type='hidden' name='account' id='account' value='".$rowBA["AccntntCod"]."'>";
							}
							?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        </td>
    </tr>
    <tr>
      <td colspan="2" class="qSec">Informaci&oacute;n de entrega</td>
    </tr>
    <tr>
    	<td colspan="2"><div class="footNotes"><img src="images/info-icon.png">Todos los gastos que se deriven de la entrega los deber&aacute; cubrir el cliente.</div></td>
    </tr>
    <tr>
    	<td colspan="2">Tipo de entrega
        	<select id="dlvType" name="dlvType">
            	<option value="" selected disabled>Selecciona...</option>
                <option value="Recoge en sucursal">Recoge en sucursal</option>
                <option value="Entrega en oficina">Entrega en oficina</option>
                <option value="Entrega en obra">Entrega en obra</option>
                <option value="Ocurre">Ocurre</option>
            </select>
        </td>
    </tr>
    <tr>
    	<td colspan="2"><div id="dlvdataDiv"></div></td>
    </tr>
    <tr>
      <td colspan="2" class="qSec">Comentarios</td>
    </tr>
    <tr>
      <td colspan="2"><textarea id="remarks" name="remarks" maxlength="256"></textarea></td>
    </tr>
    <tr>
      <td colspan="2">
      	<ul class="buttonBar">
        	<li><button type="button" class="button red" onClick="cancel();"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar</button></li>
            <li><button type="button" class="button green" id="saveQuote"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button></li>
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
var DocCur = "";
<?php
foreach($currencies as $currency) {
	echo "var ".$currency["ISOCurrCod"]." = ".$currency["Rate"].";";
}
?>

$("#DocCur").change(function() {
	DocCur = $(this).val();
	$(".chCur").html(DocCur);
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
	
var validateSAP = function() {
	//
}

$(".CardCode").on("input", function() {
	$(this).autocomplete({
		minLength: 3,
		source: "includes/searchBP.php?by=code",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(this).val(ui.item.value);
			} else {
				$(this).val(ui.item.value);
			}
			getBPDetails();
		},
		close: function() {
			getBPDetails();
		}
	});
	if ($(this).val().length >= 3) {
		getBPDetails();
	}
});

$(".CardName").on("input", function() {
	$(this).autocomplete({
		minLength: 3,
		source: "includes/searchBP.php?by=name",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(this).val(ui.item.value);
			} else {
				$(this).val(ui.item.value);
			}
			getCardCode();
		},
		close: function() {
			getCardCode();
		}
	});
	if ($(this).val().length >= 3) {
		getCardCode();
	}
});

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
	
	$(".linePrice").each(function() {
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
	$("#iQTotal").html(localeString(tot));
};

// Get Com Notes
var getComNotes = function() {
	$("#comNotes").html(jComNotes[$("#comNots").val()]);
};

var recoge = "<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Persona autorizada</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvPerson' name='dlvPerson' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
				<div class='halfLast'>\
					<table class='formTable'>\
						<tr>\
							<td>Sucursal de recolecci&oacute;n</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvAddress' name='dlvAddress' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>\
			<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Tel&eacute;fono de contacto</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvPhone' name='dlvPhone' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
				<div class='halfLast'>\
					<table class='formTable'>\
						<tr>\
							<td>E-mail de contacto</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvEmail' name='dlvEmail' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>";
			
var oficina = "<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Persona que recibe</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvPerson' name='dlvPerson' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
				<div class='halfLast'>\
					<table class='formTable'>\
						<tr>\
							<td>Direcci&oacute;n de entrega</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvAddress' name='dlvAddress' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>\
			<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Tel&eacute;fono de contacto</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvPhone' name='dlvPhone' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
				<div class='halfLast'>\
					<table class='formTable'>\
						<tr>\
							<td>E-mail de contacto</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvEmail' name='dlvEmail' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>";
			
var obra = "<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Persona que recibe</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvPerson' name='dlvPerson' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
				<div class='halfLast'>\
					<table class='formTable'>\
						<tr>\
							<td>Direcci&oacute;n de entrega</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvAddress' name='dlvAddress' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>\
			<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Tel&eacute;fono de contacto</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvPhone' name='dlvPhone' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
				<div class='halfLast'>\
					<table class='formTable'>\
						<tr>\
							<td>E-mail de contacto</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvEmail' name='dlvEmail' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>";
			
var ocurre = "<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Persona que recibe</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvPerson' name='dlvPerson' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
				<div class='halfLast'>\
					<table class='formTable'>\
						<tr>\
							<td>Direcci&oacute;n de entrega</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvAddress' name='dlvAddress' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>\
			<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Tel&eacute;fono de contacto</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvPhone' name='dlvPhone' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
				<div class='halfLast'>\
					<table class='formTable'>\
						<tr>\
							<td>E-mail de contacto</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvEmail' name='dlvEmail' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>\
			<div class='fullWidth'>\
				<div class='halfFirst'>\
					<table class='formTable'>\
						<tr>\
							<td>Fletera</td>\
						</tr>\
						<tr>\
							<td><input type='text' id='dlvFlet' name='dlvFlet' style='width:100% !important;'></td>\
						</tr>\
					</table>\
				</div>\
			</div>";


// Select delivery type
var fillDlvData = function() {
	var dlvType = $(this).val();
	switch(dlvType) {
		case "Recoge en sucursal":
			$("#dlvdataDiv").html(recoge);
			break;
		case "Entrega en oficina":
			$("#dlvdataDiv").html(oficina);
			break;
		case "Entrega en obra":
			$("#dlvdataDiv").html(obra);
			break;
		case "Ocurre":
			$("#dlvdataDiv").html(ocurre);
			break;
	}
};

$(document).ready(function() {
	addItem();
	$('#newCotBT').addClass('active');
	$('#subHeaderTitle').html('Nueva cotizaci&oacute;n');
	$("#infoBar").draggable({
		cursor: "move"
	});
});
	
$("#saveQuote").click(function () {
	$.validator.messages.required = '';
	$("#quote").validate({
		
	});
	$("#quote").attr("action", "includes/createQuote.php");
	$("#quote").submit();
});
	
$("#dlvType").on('change', fillDlvData);
$("#comNots").on('change', getComNotes);
	

// Calculate according to wich element was changed: List Price, Currency, Discount, Quantity; on each item
$(document).on("change", ".listPrice", function() {
	var ind = $(".listPrice").index(this);
	calculateLine(ind);
});
$(document).on("change", ".currency", function() {
	var ind = $(".currency").index(this);
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
	
$("#gDiscP").on("change", calculateSubs);
$("#ivaP").on("change", calculateSubs);
</script>

<?php include 'footer.php'; ?>