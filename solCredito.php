<?php
include 'head.php';
?>

<style>
.formTable tr td {
	width:33.33%;
}
input {
	width:100%;
}
select {
	width:100%;
}
</style>
<form method="post" action="/includes/creditRequest.php" enctype="multipart/form-data" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
    <tr>
      <td colspan="3" class="qSec">Informaci&oacute;n general del solicitante</td>
    </tr>
    <tr>
    	<td colspan="3">Cliente *</td>
    </tr>
    <tr>
        <td class="tdCardCode">
            <input type="text" id="CardCode" name="CardCode" class="CardCode" required placeholder="CODIGO">
        </td>
        <td class="tdCardName" colspan="2">
            <input type="text" id="CardName" name="CardName" class="CardName" required placeholder="NOMBRE">
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <div>
                <?php include "modules/bpDetails.php"; ?>
            </div>
        </td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Cr&eacute;dito solicitado</td>
    </tr>
    <tr>
      <td>Monto de cr&eacute;dito solicitado (MN antes de IVA)</td>
      <td>Plazo del cr&eacute;dito solictado (d&iacute;as naturales)</td>
      <td>Tipo de domicilio</td>
    </tr>
    <tr>
      <td><input type="text"  id="reqAmmount" name="reqAmmount" onKeyPress="return numeros(event)" required></td>
      <td><input type="text"  id="reqDays" name="reqDays"  onKeyPress="return numeros(event)"required></td>
      <td>
      	<select id="facType" name="facType" required>
        	<option value="" selected disabled>Selecciona...</option>
            <option value="Propio">Propio</option>
            <option value="Rentado">Rentado</option>
        </select>
      </td>
    </tr>
    <tr>
    	<td colspan="3">
        	<div id="domData">
            </div>
        </td>
    </tr>
    <tr>
      <td>Registro de importador n&uacute;mero (si aplica)</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><input type="text" id="regNum" name="regNum" onKeyPress="return numeros(event)"></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Referencias comerciales (3)</td>
    </tr>
    <tr>
      <td>Raz&oacute;n social</td>
      <td>Persona de contacto</td>
      <td>Tel&eacute;fono(s)</td>
    </tr>
    <tr>
      <td><input type="text" id="refComRazSoc1" name="refComRazSoc1" required></td>
      <td><input type="text" id="refComCntPrs1" name="refComCntPrs1" required></td>
      <td><input type="text" id="refComTel1" name="refComTel1" required></td>
    </tr>
    <tr>
      <td><input type="text" id="refComRazSoc2" name="refComRazSoc2" required></td>
      <td><input type="text" id="refComCntPrs2" name="refComCntPrs2" required></td>
      <td><input type="text" id="refComTel2" name="refComTel2" required></td>
    </tr>
    <tr>
      <td><input type="text" id="refComRazSoc3" name="refComRazSoc3" required></td>
      <td><input type="text" id="refComCntPrs3" name="refComCntPrs3" required></td>
      <td><input type="text" id="refComTel3" name="refComTel3" required></td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Referencias bancarias (2)</td>
    </tr>
    <tr>
      <td>Instituci&oacute;n</td>
      <td>Persona de contacto</td>
      <td>Tel&eacute;fono(s)</td>
    </tr>
    <tr>
      <td><input type="text" id="refBanBan1" name="refBanBan1" required></td>
      <td><input type="text" id="refBanCntPrs1" name="refBanCntPrs1" required></td>
      <td><input type="text" id="refBanTel1" name="refBanTel1" required></td>
    </tr>
    <tr>
      <td>Sucursal</td>
      <td>Cuenta</td>
      <td></td>
    </tr>
    <tr>
      <td><input type="text" id="refBanSuc1" name="refBanSuc1" required></td>
      <td><input type="text" id="refBanCta1" name="refBanCta1" required></td>
      <td></td>
    </tr>
    <tr>
      <td>Instituci&oacute;n</td>
      <td>Persona de contacto</td>
      <td>Tel&eacute;fono(s)</td>
    </tr>
    <tr>
      <td><input type="text" id="refBanBan2" name="refBanBan2" required></td>
      <td><input type="text" id="refBanCntPrs2" name="refBanCntPrs2" required></td>
      <td><input type="text" id="refBanTel2" name="refBanTel2" required></td>
    </tr>
    <tr>
      <td>Sucursal</td>
      <td>Cuenta</td>
      <td></td>
    </tr>
    <tr>
      <td><input type="text" id="refBanSuc2" name="refBanSuc2" required></td>
      <td><input type="text" id="refBanCta2" name="refBanCta2" required></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Comentarios</td>
    </tr>
    <tr>
      <td colspan="3"><textarea id="Comentarios" name="Comentarios"></textarea></td>
    </tr>
    <tr>
      <td colspan="3" align="center">MANIFIESTO A <?php echo strtoupper($_SESSION["companyName"]); ?>, QUE LAS DECLARACIONES ANTERIORES HECHAS POR MI SON ABSOLUTAS Y VERDADERAS</td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Favor de adjuntar a su solicitud los siguientes documentos:</td>
    </tr>
    <tr>
    	<td colspan="3">
        	<div id="documents">
        	</div>
        </td>
    </tr>
    <tr>
    	<td></td>
        <td align="center"><strong>ATENTAMENTE</strong></td>
        <td></td>
    </tr>
    <tr>
    	<td></td>
        <td align="center"><input type="text" id="repLegal" name="repLegal" required><br>Nombre del representante legal</td>
        <td></td>
    </tr>
    <tr>
    	<td colspan="3"><strong>Aviso de privacidad:</strong> <?php echo strtoupper($_SESSION["companyName"]); ?> se compromete a que toda documentación oficial que nos envíe, será tratada en forma confidencial.</td>
    </tr>
    <tr>
    	<td colspan="3"><strong>NOTA:</strong> ESTIMADO CLIENTE, EL HECHO DE HABER LLENADO ESTA SOLICITUD, NO IMPLICA HABER OBTENIDO CRÉDITO CON <?php echo strtoupper($_SESSION["companyName"]); ?>, EN CASO DE QUE EL CRÉDITO SEA APROBADO, SE LE NOTIFICARÁ VÍA TELEFÓNICA Y CORREO ELECTRÓNICO; CUALQUIER DATO FALSO SERÁ MOTIVO DE NEGACIÓN O RECHAZO DEL CRÉDITO.</td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="button red" onClick="cancel();">Cancelar</button></td>
      <td>&nbsp;</td>
      <td align="right"><button type="submit" class="button green">Enviar solicitud</button></td>
    </tr>
  </tbody>
</table>

</form>



<script type="text/javascript">
var getCardCode = function() {
	var name = $("#CardName").val();
	if (name.length >= 3) {
		$.ajax({
			type: "GET",
			url: "includes/cardCode.php?name="+encodeURI(name),
			dataType: "json",
			cache: false,
			success: function(CardCode){
				if (CardCode["CardCode"] != null) {
					$("#CardCode").val(CardCode["CardCode"]).after(function() {
                        getBPDetails();
					});
				}
			}
		});
	}
};
var bpType = "";

var fisDocs = "<table class='formTable'><tr>\
    	<td><label for='fisIdOficial'>Identificación oficial con fotografía (Pasaporte o INE)</label></td>\
        <td colspan='2'><input type='file' name='fisIdOficial' id='fisIdOficial' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='fisCompDom'>Comprobante de domicilio fiscal</label></td>\
        <td colspan='2'><input type='file' name='fisCompDom' id='fisCompDom' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='fisCedFis'>Cédula fiscal</label></td>\
        <td colspan='2'><input type='file' name='fisCedFis' id='fisCedFis' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='fisFormR1'>Formulario R1 (Alta en SHCP)</label></td>\
        <td colspan='2'><input type='file' name='fisFormR1' id='fisFormR1' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='fisIdOfAval'>Identificación oficial de avales</label></td>\
        <td colspan='2'><input type='file' name='fisIdOfAval' id='fisIdOfAval' required/></td>\
    </tr></table>";

var morDocs = "<table class='formTable'><tr>\
    	<td><label for='morActa'>Acta constitutiva</label></td>\
        <td colspan='2'><input type='file' name='morActa' id='morActa' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morCompDom'>Comprobante de domicilio fiscal</label></td>\
        <td colspan='2'><input type='file' name='morCompDom' id='morCompDom' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morEdosFin'>Estados financieros dictaminados (Últimos dos ejercicios)</label></td>\
        <td colspan='2'><input type='file' name='morEdosFin' id='morEdosFin' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morFormR2'>Formulario R2 (Alta en SHCP)</label></td>\
        <td colspan='2'><input type='file' name='morFormR2' id='morFormR2' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morCedFis'>Cédula fiscal</label></td>\
        <td colspan='2'><input type='file' name='morCedFis' id='morCedFis' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morIdOfAut'>Identificación oficial personas autorizadas para suscribir títulos de crédito</label></td>\
        <td colspan='2'><input type='file' name='morIdOfAut' id='morIdOfAut' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morIdOfApod'>Identificación oficial de apoderados</label></td>\
        <td colspan='2'><input type='file' name='morIdOfApod' id='morIdOfApod' required/></td>\
    </tr></table>";

var ownDom = "<table class='formTable'><tr>\
      <td>Valor aproximado de la propiedad (MN)</td>\
      <td></td>\
      <td></td>\
    </tr>\
    <tr>\
      <td><input type='text' id='propValue' name='propValue' onKeyPress='return numeros(event)' required></td>\
      <td></td>\
      <td></td>\
    </tr></table>";

var rentDom = "<table class='formTable'><tr>\
      <td>Monto de renta mensual (MN)</td>\
      <td>Antig&uuml;edad de arrendamiento del inmueble</td>\
      <td>Vencimiento del contrato de arrendamiento</td>\
    </tr>\
    <tr>\
      <td><input type='text' id='monthRent' name='monthRent' onKeyPress='return numeros(event)' required></td>\
      <td><input type='text' id='sinceRent' name='sinceRent' required></td>\
      <td><input type='text' id='dueRent' name='dueRent' required></td>\
    </tr></table>";

var getBPDetails = function() {
	var code = $("#CardCode").val();
	if (code.length >= 3) {
		$.ajax({
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
				bpType = bpDetail["CmpPrivate"];
				if (bpType == "I") {
					$("#bpType").val("Fisica");
					$("#documents").html(fisDocs);
				} else {
					$("#bpType").val("Moral");
					$("#documents").html(morDocs);
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
				$("#lastDate").val(bpDetail["DocDate"]);
				$("#lastAmmount").val(localeString(bpDetail["TrsfrSum"]));
			}
		});
	}
};

$(".CardCode").autocomplete({
	minLength: 3,
	source: "includes/searchBP.php?by=code",
	select: function(event, ui) {
		var origEvent = event;
		while (origEvent.originalEvent !== undefined) {
			origEvent = origEvent.originalEvent;
		}
		if (origEvent.type == "click") {
			$(".CardCode").val(ui.item.value);
		} else {
			$(".CardCode").val(ui.item.value);
		}
		getBPDetails();
	},
	close: function() {
		getBPDetails();
	}
});
$(".CardName").autocomplete({
	minLength: 3,
	source: "includes/searchBP.php?by=name",
	select: function(event, ui) {
		var origEvent = event;
		while (origEvent.originalEvent !== undefined) {
			origEvent = origEvent.originalEvent;
		}
		if (origEvent.type == "click") {
			$(".CardName").val(ui.item.value);
		} else {
			$(".CardName").val(ui.item.value);
		}
		getCardCode();
	},
	close: function() {
		getCardCode();
	}
});

$("#facType").on("change", function() {
	if($(this).val() == "Propio") {
		$("#domData").html(ownDom);
	} else {
		$("#domData").html(rentDom);
		$("#sinceRent").datepicker({
			numberOfMonths: 2,
			maxDate: '0',
			dateFormat: "dd/mm/yy",
			onClose: function(selectedDate) {
				$("#dueRent").datepicker("option", "minDate", selectedDate);
			}
		});
		
		$("#dueRent").datepicker({
			numberOfMonths: 2,
			dateFormat: "dd/mm/yy",
			onClose: function(selectedDate) {
				$("#sinceRent").datepicker("option", "maxDate", "0");
			}
		});
	}
});

</script>

<?php include 'footer.php'; ?>