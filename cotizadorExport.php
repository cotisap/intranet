<?php
include 'head.php';

$currencies = array();
$queryCur = "SELECT ChkName, CurrCode FROM OCRN WHERE ChkName = 'Dollars'";
$resultCur = mssql_query($queryCur);
while ($rowCur = mssql_fetch_assoc($resultCur)) {
	$currencies[$rowCur["ChkName"]] = $rowCur["CurrCode"];
}
?>

<div id="newQuote">
<form method="post" action="includes/createQuote.php">
<input type="hidden" name="export" id="export" value="Y">
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
                	<div id="bpDetail">
                    	<?php include "modules/bpDetails.php"; ?>
                    </div>
                </td>
            </tr>
        </table>
      </td>
    </tr>
    <tr>
    	<td colspan="2" class="qSec">Productos a cotizar</td>
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
                            <td class="tdlPrice" style="text-align:left !important">Precio lista *</td>
                            <td class="tdCurrency">Moneda *</td>
                            <td class="tdDiscount">% Desc.</td>
                            <td class="tduPrice">Precio unitario</td>
                            <td class="tdQuant">Cantidad *</td>
                            <td class="tdnPrice">Importe</td>
                            <td class="tdRemove">&nbsp;</td>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- Item append -->
        </div>
        <div id="addItem" class="button blue">Agregar partida</div>
	  </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<table width="300px" border="0" cellspacing="0" cellpadding="0" class="quoteTotals" align="right">
              <tbody>
                <tr>
                  <td colspan="2" class="qSec">Total USD</td>
                </tr>
                <tr>
                  <td><div id="subUSD"></div></td>
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
                            <select id="account" name="account" required>
                                <?php
								$queryBA = "SELECT AcctCode, AcctName, AccntntCod, U_account, U_clabe FROM OACT WHERE FatherNum = '1120-000-000' AND ActCurr = 'USD' AND AccntntCod <> '' ORDER BY AccntntCod";
                                $resultBA = mssql_query($queryBA);
                                while ($rowBA = mssql_fetch_assoc($resultBA)) {
                                    echo "<option value='".$rowBA["AccntntCod"]."' selected>".$rowBA["AccntntCod"]." - ".$rowBA["AcctName"]."</option>";
                                }
                                ?>
                            </select>
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
        	<li><button type="button" class="button red" onClick="cancel();">Cancelar</button></li>
            <li><button type="submit" class="button green">Guardar</button></li>
        </ul>
      </td>
    </tr>
  </tbody>
</table>
</form>
</div>



<script type="text/javascript">
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

$(".CardCode").autocomplete({
	minLength: 3,
	source: "includes/searchBP.php?by=code"
});
$(".CardName").autocomplete({
	minLength: 3,
	source: "includes/searchBP.php?by=name"
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
					$("#CardCode").val(CardCode["CardCode"]).trigger("change");
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
				$("#lastDate").val(bpDetail["DocDate"]);
				$("#lastAmmount").val(localeString(bpDetail["TrsfrSum"]));
			}
		});
	}
};

$(document).on("focusout", ".CardCode", getBPDetails);
$(document).on("change", ".CardCode", getBPDetails);
$(document).on("click", ".CardCode", getBPDetails);
$(document).on("keyup", ".CardCode", getBPDetails);
$(document).on("focusout", "#CardName", getCardCode);


// Show BP Details
$(document).on('click', '.bpDetailTrigger', function() {
	$("#bpDetail").slideToggle("fast");
	$(this).children("img").toggleClass("rotate");
});

//========== Calculate Subtotals
var calculateSubs = function() {
	<?php
	foreach($currencies as $currency => $val) {
	?>
	var sub<?php echo $val; ?> = 0;
	
	$('.<?php echo $val; ?>').each(function() {
		sub<?php echo $val; ?> += parseFloat(this.value);
	});
	
	$("#sub_<?php echo $val; ?>").val(sub<?php echo $val; ?>);
		
	$("#sub<?php echo $val; ?>").html("$"+localeString(sub<?php echo $val; ?>));
	
	<?php
	}
	?>
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

$("#dlvType").on('change', fillDlvData);
$("#comNots").on('change', getComNotes);
$(".listPrice").on('change', calculateByListPrice);
$(".discount").on('change', calculateByProdDIS);
$(".currency").on('change', calculateByCurrency);
$(".quantity").on('change', calculateByProdQTY);

$(document).ready(function() {
	addItem();
	$('#newCotExpBT').addClass('active');
	$('#subHeaderTitle').html('Cotizaci&oacute;n para exportaci&oacute;n');
});
</script>

<?php include 'footer.php'; ?>