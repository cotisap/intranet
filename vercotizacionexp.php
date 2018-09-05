<?php
include 'head.php';

$idCot = $_REQUEST["idCot"];

$qExpQuot = "SELECT export FROM COTI WHERE Id_Cot1 = $idCot";
$rExpQuot = mysql_query($qExpQuot);
$rowExpQuot = mysql_fetch_assoc($rExpQuot);
if ($rowExpQuot["export"] != 'Y') {
	header("Location: vercotizacion.php?idCot=".$idCot);
}

$currencies = array();
$queryCur = "SELECT ChkName, CurrCode FROM OCRN WHERE ChkName = 'Dollars'";
$resultCur = mssql_query($queryCur);
while ($rowCur = mssql_fetch_assoc($resultCur)) {
	$currencies[$rowCur["ChkName"]] = $rowCur["CurrCode"];
}



$queryCot = mysql_query("SELECT Codigo_SN, CardName, Serie, FechaCreacion, Fecha_Entrega, Total_MN, Total_USD, Comentarios, Empl_Ven, Id_Cot1, TC, account, cnotes, dlvType, dlvPerson, dlvPhone, dlvEmail, dlvAddress, dlvFlet, company FROM COTI WHERE Id_Cot1 = $idCot");
$rowCoti = mysql_fetch_array($queryCot);

$CardCode = $rowCoti["Codigo_SN"];
$CardName = $rowCoti["CardName"];
$Empl_Ven = $rowCoti["Empl_Ven"];
$company = $rowCoti["company"];
$account = $rowCoti["account"];
$comments = $rowCoti["Comentarios"];
$cNotes = $rowCoti["cnotes"];
$dlvType = $rowCoti["dlvType"];
$dlvPerson = utf8_decode($rowCoti["dlvPerson"]);
$dlvPhone = $rowCoti["dlvPhone"];
$dlvEmail = $rowCoti["dlvEmail"];
$dlvAddress = utf8_decode($rowCoti["dlvAddress"]);
$dlvFlet = utf8_decode($rowCoti["dlvFlet"]);

$querySNData = mssql_query("SELECT E_Mail FROM OCRD WHERE CardCode = '$customer'");
$rowSNData = mssql_fetch_array($querySNData);

$email = $rowSNData["E_Mail"];
?>
<div class="overlay">
    <div class="sendEmail">
        <form method="post" name="sendQuote" id="sendQuote" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Destinatario(s) *</td>
                </tr>
                <tr>
                    <td><input type="text" id="email" name="email" value="<?php echo $email; ?>"></td>
                </tr>
                <tr>
                    <td>Asunto *</td>
                </tr>
                <tr>
                    <td><input type="text" name="subject" id="subject" value="Cotizaci&oacute;n <?php echo $idCot; ?> - <?php if ($_SESSION["company"] == "fg") {echo "FG Electrical";} else {echo "Alianza El&eacute;ctrica";}?>"></td>
                </tr>
                <tr>
                    <td>Mensaje *</td>
                </tr>
                <tr>
                    <td><textarea name="eMessage" id="eMessage" style="height:150px;">Por este medio le hacemos llegar nuestra solicitud de cotizaci&oacute;n requerida, en caso de cualquier duda o comentario seguimos a sus &oacute;rdenes.&#13;&#13;
Saludos cordiales.&#13;&#13;
<?php echo $_SESSION["name"]; ?>&#13;
Tel: <?php echo $_SESSION["phone"] ?>&#13;
E-mail: <?php echo $_SESSION["email"]?></textarea></td>
                </tr>
                <tr>
                    <td><a href="pdf.php?idcot=<?php echo $idCot; ?>" target="_blank"><img src="images/attach.png" class="viewDetails"> Cotizaci&oacute;n <?php echo $idCot; ?> - <?php if ($_SESSION["company"] == fg) {echo "FG Electrical";} else {echo "Alianza El&eacute;ctrica";}?>.pdf</a></td>
                </tr>
                <tr>
                    <td>Adjunta uno o m&aacute;s archivos</td>
                </tr>
                <tr>
                	<td>
                    	<div id="filesDiv">
                        	<!-- -->
                        </div>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<button type="button" class="button blue" id="addAttach" name="addAttach">+</button>
                    </td>
                </tr>
                <tr>
                	<td>&nbsp;
                    	
                    </td>
                </tr>
                <tr>
                    <td>
                    	<ul class="buttonBar">
                        	<li><button type="button" class="button red" onClick="javascript:$(this).closest('.overlay').fadeOut('fast')">Cancelar</button></li>
                            <li><button type="button" class="button green" id="sendEmail" name="sendEmail">Enviar</button></li>
                        </ul>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div id="newQuote">
<form method="post" enctype="multipart/form-data" name="quote" id="quote">
<input type="hidden" name="export" id="export" value="Y">
<table class="formTable">
  <tbody>
    <tr>
    	<td colspan="2" class="pageTitle" align="right">Cotizaci&oacute;n <?php echo $idCot; ?></td>
        <input type="hidden" id="Empl_Ven" name="Empl_Ven" value="<?php echo $Empl_Ven; ?>">
        <input type="hidden" id="company" name="company" value="<?php echo $company; ?>">
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
                            <td class="tdlPrice" style="text-align:left !important;">Precio lista *</td>
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
        <div id="addItem" class="button blue"><i class="fa fa-plus" aria-hidden="true"></i> Agregar partida</div>
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
      <td colspan="2" class="qSec">Informaci&oacute;n de pago</td>
    </tr>
    <tr>
        <td colspan="2">
        	<div class="fullWidth">
                <div class="halfFirst">
                	<table class="itemListHead">
                    	<tr>
                            <td class="tdMethod">M&eacute;todo de pago *</td>
                            <td class="tdAmmount">Monto *</td>
                            <td class="tdPCurrency">Moneda *</td>
                            <td class="tdRef">Referencia *</td>
                        </tr>
                    </table>
                </div>
                <div class="halfLast">
                	<table class="itemListHead">
                    	<tr>
                        	<td class="tdPDate">Fecha de pago *</td>
                            <td class="tdFile">Comprobante *</td>
                            <td class="tdStatus">Status de validaci&oacute;n</td>
                            <td class="tdRemove"></td>
                        </tr>
                    </table>
                </div>
            </div>
        	<table class="formTable">
                <tr>
                	<td id="pContainer">
                    	<!-- Payment lines -->
                	</td>
                </tr>
                <tr><td><div id="addPayment" class="button blue"><i class="fa fa-plus" aria-hidden="true"></i> Agregar pago</div></td></tr>
                <tr><td><div class="footNotes"><img src="images/info-icon.png">Los formatos de archivo aceptados son JPG, PNG y PDF.<br>El peso del archivo no debe ser mayor a 1 MB.</div></td></tr>
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
      <td colspan="2"><textarea id="remarks" name="remarks" maxlength="256"><?php echo $comments; ?></textarea></td>
    </tr>
    <tr>
    	<td colspan="2">
        	<ul class="buttonBar">
            	<li><button type="button" class="button red" onClick="history.go(-1); return false;"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar</button></li>
                <li><button type="button" class="button green" id="upQuote" name="upQuote"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button></li>
                <li><button type="button" class="button blue" onClick="window.open('cotExp.php?idcot=<?php echo $idCot; ?>')"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Ver PDF</button></li>
                <li><button type="button" class="button dBlue" id="showEmail"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar e-mail</button></li>
                <li><button type="button" class="button blue" id="valPmnt" name="valPmnt"><i class="fa fa-usd" aria-hidden="true"></i> Validar Pagos</button></li>
                <li><button type="button" class="button purple" id="saOrder" name="saOrder"><i class="fa fa-share" aria-hidden="true"></i> Solicitar pedido</button></li>
        	</ul>
        </td>
    </tr>
  </tbody>
</table>
<input id="idCot" name="idCot" type="hidden" value="<?php echo $idCot; ?>">
</form>
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

var paymentLine = "<div class='payment fullWidth'>\
						<div class='halfFirst'>\
							<table width='100%' border='0' cellspacing='0' cellpadding='0' class='formTable'>\
								<tr>\
									<td class='tdMethod'>\
										<select id='pMethod' name='pMethod[]' class='pMethod' required>\
											<option value='' selected disabled>Selecciona...</option>\
											<option value='C'>Efectivo</option>\
											<option value='K'>Cheque</option>\
											<option value='E'>Transferencia electr&oacute;nica</option>\
											<option value='R'>Tarjeta de cr&eacute;dito</option>\
										</select>\
									</td>\
									<td class='tdAmmount'><input type='text' name='ammount[]' id='ammount' class='ammount' required style='width: 100% !important;' onKeyPress='return numeros(event)'/></td>\
									<td class='tdPCurrency'>\
										<select id='pCurrency' name='pCurrency[]' class='pCurrency' required>\
											<option value='' selected disabled>Selecciona...</option>\
											<option value='MXN'>MXN</option>\
											<option value='USD'>USD</option>\
										</select>\
									</td>\
									<td class='tdRef'><input type='text' name='ref[]' id='ref' class='ref' required style='width: 100% !important;'></td>\
								</tr>\
							</table>\
						</div>\
						<div class='halfLast'>\
							<table class='formTable'>\
								<tr>\
									<td class='tdPDate'>\
										<input type='text' id='pDate' name='pDate[]' class='pDate' required>\
									</td>\
									<td class='tdFile'><input type='file' name='file[]' id='file' required>\
									</td>\
									<td Class='tdRemove'><img src='images/remove-icon.png' class='removePay' alt='Eliminar pago' title='Eliminar pago'></td>\
								</tr>\
							</table>\
						</div>\
                    </div>";
					
var paymentLineFixed = "<div class='payment fullWidth'>\
						<div class='halfFirst'>\
							<table class='formTable'>\
								<tr>\
									<td class='tdMethod'><div class='methodDiv'></div><input type='hidden' class='vMethod' id='vMethod' name='vMethod[]'></td>\
									<td class='tdAmmount'>$ <div class='ammountDiv'></div><input type='hidden' class='vAmmount' id='vAmmount' name='vAmmount[]'></td>\
									<td class='tdPCurrency'><div class='pCurrencyDiv'></div><input type='hidden' class='vCurrency' id='vCurrency' name='vCurrency[]'></td>\
									<td class='tdRef'><div class='refDiv'></div><input type='hidden' class='vRef' id='vRef' name='vRef[]'></td>\
								</tr>\
							</table>\
						</div>\
						<div class='halfLast'>\
							<table class='formTable'>\
								<tr>\
									<td class='tdPDate'><div class='pDateDiv'></div><input type='hidden' class='vDate' id='vDate' name='vDate[]'></td>\
									<td class='tdFile'><div class='fileDiv'></div><input type='hidden' class='vFile' id='vFile' name='vFile[]'></td>\
									<td class='tdStatus'><div class='statusDiv'></div><input type='hidden' class='vStatus' id='vStatus' name='vStatus[]'></td>\
									<td Class='tdRemove'>&nbsp;</td>\
								</tr>\
							</table>\
						</div>\
                    </div>";

$("#addPayment").click(function() {
	$("#pContainer").append(paymentLine);
	$(".pDate").datepicker({
		maxDate: '0',
		dateFormat: "dd/mm/yy",
	});
});

// Remove Payment Line
$(document).on('click', '.removePay', function() {
	$(this).closest('.payment').hide("fast").delay(1000).queue(function(){$(this).remove();});
});

// Show Email Overlay
$(document).on('click', '#showEmail', function() {
	$(".overlay").fadeIn("fast");
});

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

$(".listPrice").on('change', calculateByListPrice);
$(".discount").on('change', calculateByProdDIS);
$(".currency").on('change', calculateByCurrency);
$(".quantity").on('change', calculateByProdQTY);
$("#comNots").on('change', getComNotes);


$(document).ready(function() {
	$("#CardCode").trigger("change");
	$(".listPrice").on('change', calculateByListPrice);
	$(".discount").on('change', calculateByProdDIS);
	$(".currency").on('change', calculateByCurrency);
	$(".quantity").on('change', calculateByProdQTY);
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
	getPayments();
});

// Get Quote info
var getQuoteInfo = function() {
	var account = <?php echo json_encode($account); ?>;
	var cNotes = <?php echo json_encode($cNotes); ?>;
	var dlvType = <?php echo json_encode($dlvType); ?>;
	var dlvPerson = <?php echo json_encode($dlvPerson); ?>;
	var dlvAddress = <?php echo json_encode($dlvAddress); ?>;
	var dlvPhone = <?php echo json_encode($dlvPhone); ?>;
	var dlvEmail = <?php echo json_encode($dlvEmail); ?>;
	var dlvFlet = <?php echo json_encode($dlvFlet); ?>;
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
		$(".listPrice:eq("+i+")").val(jListPrices[i]);
		$(".currency:eq("+i+")").val(jCurrencies[i]);
		$(".linePrice:eq("+i+")").addClass(jCurrencies[i]);
		$(".discount:eq("+i+")").val(jDiscs[i]).trigger("change");
		$(".finalPriceDiv:eq("+i+")").html("$"+localeString(jFPrice[i]));
		$(".finalPrice:eq("+i+")").val(jFPrice[i]);
		$(".quantity:eq("+i+")").val(jQuant[i]);
		$(".linePriceDiv:eq("+i+")").html("$"+localeString(jNPrice[i]));
		$(".linePrice:eq("+i+")").val(jNPrice[i]);
		$(".uniMed:eq("+i+")").val(jUniMed[i]);
		$(".delivery:eq("+i+")").val(jDelivery[i]);
		$(".lineRemark:eq("+i+")").val(jRemark[i]);
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

// Submit Forms
$().ready(function() {
	$("#sendQuote").validate({
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
$("#upQuote").click(function () {
	$("#quote").attr("action", "includes/updateQuote.php");
	$("#quote").submit();
});
$("#valPmnt").click(function () {
	$("#quote").attr("action", "validarpago.php?idcot=<?php echo $idCot; ?>");
	$("#quote").submit();
});
$("#saOrder").click(function () {
	$("#quote").attr("action", "solicitarpedido.php?idcot=<?php echo $idCot; ?>");
	$("#quote").submit();
});
$("#sendEmail").click(function () {
	$("#sendQuote").attr("action", "sendQuoteExp.php?idcot=<?php echo $idCot; ?>");
	$("#sendQuote").submit();
});
</script>



<?php
$queryLines = mysql_query("SELECT Id_Cot1, lineNum, Codigo_Art, Nombre_Art, CONCAT( Codigo_Art,  ' - ', Nombre_Art ) AS prodName, Cantidad, Moneda, Precio_Lista, UMV, Precio_Unidad, Factor, Sub_Tot_Line, Almacen, TiempoEntrega, FechaCreacion, remarks FROM COT1 WHERE Id_Cot1 = '$idCot' ORDER BY lineNum ASC");
$prodCodes = Array();
$prodNames = Array();
$listPrices = Array();
$currencies = Array();
$discs = Array();
$fPrice = Array();
$quant = Array();
$nPrice = Array();
$uniMed = Array();
$delivery = Array();
$remark = Array();
while($rowLines = mysql_fetch_array($queryLines)){ 
	$prodCodes[] = utf8_encode($rowLines["Codigo_Art"]);
	$prodNames[] = utf8_encode($rowLines["Nombre_Art"]);
	$listPrices[] = $rowLines["Precio_Lista"];
	$currencies[] = $rowLines["Moneda"];
	$discs[] = $rowLines["Factor"];
	$fPrice[] = $rowLines["Precio_Unidad"];
	$quant[] = $rowLines["Cantidad"];
	$nPrice[] = $rowLines["Sub_Tot_Line"];
	$uniMed[] = $rowLines["UMV"];
	$delivery[] = $rowLines["TiempoEntrega"];
	$remark[] = utf8_encode($rowLines["remarks"]); ?>
	<script type="text/javascript">
	$("#itemContainer").append(itemLine);
	setLineNumber();
	var jProdCodes = <?php echo json_encode($prodCodes); ?>;
	var jProdNames = <?php echo json_encode($prodNames); ?>;
	var jListPrices = <?php echo json_encode($listPrices); ?>;
	var jCurrencies = <?php echo json_encode($currencies); ?>;
	var jDiscs = <?php echo json_encode($discs); ?>;
	var jFPrice = <?php echo json_encode($fPrice); ?>;
	var jQuant = <?php echo json_encode($quant); ?>;
	var jNPrice = <?php echo json_encode($nPrice); ?>;
	var jUniMed = <?php echo json_encode($uniMed); ?>;
	var jDelivery = <?php echo json_encode($delivery); ?>;
	var jRemark = <?php echo json_encode($remark); ?>;
	</script>
<?php };

// Payment status
//$queryPStat = "";
//$resultPStat = mssql_query($queryPStat);
//$rowPStat = mssql_fetch_assoc($resultPStat);


$queryPayments = mysql_query("SELECT T1.ref_q, T3.text AS method, T1.ammount, T1.currency, T1.ref_p, T1.date, T1.file, T2.text AS status FROM PMNT T1 INNER JOIN PSTS T2 ON T1.status = T2.val INNER JOIN PMTD T3 ON T1.method = T3.val WHERE ref_q = '$idCot'");
$methods = Array();
$ammounts = Array();
$pCurrencies = Array();
$pRefs = Array();
$pDates = Array();
$files = Array();
$status = Array();
while($rowPayments = mysql_fetch_array($queryPayments)){ 
	$methods[] = $rowPayments["method"];
	$ammounts[] = $rowPayments["ammount"];
	$pCurrencies[] = $rowPayments["currency"];
	$pRefs[] = $rowPayments["ref_p"];
	$pDates[] = date_format(date_create($rowPayments["date"]), "d/m/Y");
	$files[] = $rowPayments["file"];
	$status[] = $rowPayments["status"]; ?>
	<script type="text/javascript">
	$("#pContainer").append(paymentLineFixed);
	var jMethods = <?php echo json_encode($methods); ?>;
	var jAmmounts = <?php echo json_encode($ammounts); ?>;
	var jPCurrencies = <?php echo json_encode($pCurrencies); ?>;
	var jRefs = <?php echo json_encode($pRefs); ?>;
	var jPDates = <?php echo json_encode($pDates); ?>;
	var jFiles = <?php echo json_encode($files); ?>;
	var jStatus = <?php echo json_encode($status); ?>;
	</script>
<?php
};

include 'footer.php';
?>