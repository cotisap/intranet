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

<p class="pageTitle">Solicitud de Alta de Cliente en SAP</p>

<form method="post" action="/includes/clientRequest.php" enctype="multipart/form-data" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
    <tr>
      <td colspan="3" class="qSec">Informaci&oacute;n General</td>
    </tr>
    <tr>
        <td colspan="3">
            <div>
                <!-- BP Detail -->
				<div class="fullWidth third">
					<div>
						Nombre o raz&oacute;n social<br>
						<input type="text" id="bpName" name="bpName" required>
					</div>
					<div>
						RFC<br>
						<input type="text" id="bpRFC" name="bpRFC" required>
					</div>
				</div>
				<div class="fullWidth third">
					<div>
						Tel&eacute;fono conmutador<br>
						<input type="text" id="bpPhone" name="bpPhone" required>
					</div>
					<div>
						E-mail general<br>
						<input type="text" id="bpEmail" name="bpEmail" required>
					</div>
					<div>
						Website<br>
						<input type="text" id="bpWeb" name="bpWeb" required>
					</div>
				</div>
				<div class="fullWidth third">
					<div>
						Tipo de persona<br>
						<select id="bpType" name="bpType" required>
							<option value="" selected disabled>Selecciona...</option>
							<option value="F">Física</option>
							<option value="M">Moral</option>
						</select>
					</div>
				</div>
				
				<!-- Billing Address -->
				<div class="fullWidth qSec">
						<strong>Direcci&oacute;n fiscal</strong>
				</div>
				<div class="fullWidth third">
					<div>
						Calle y n&uacute;mero<br>
						<input type="text" id="bpBStreet" name="bpBStreet" required>
					</div>
					<div>
						Colonia<br>
						<input type="text" id="bpBCol" name="bpBCol" required>
					</div>
					<div>
						Ciudad<br>
						<input type="text" id="bpBCity" name="bpBCity" required>
					</div>
				</div>
				<div class="fullWidth third">
					<div>
						Municipio / Delegaci&oacute;n<br>
						<input type="text" id="bpBCounty" name="bpBCounty" required>
					</div>
					<div>
						Estado<br>
						<input type="text" id="bpBState" name="bpBState" required>
					</div>
					<div>
						Pa&iacute;s<br>
						<input type="text" id="bpBCountry" name="bpBCountry" required>
					</div>
				</div>
				<div class="fullWidth third">
					<div>
						C&oacute;digo postal<br>
						<input type="text" id="bpBZip" name="bpBZip" required>
					</div>
				</div>
				<!-- Shipping Address -->
				<div class="fullWidth qSec">
						<strong>Direcci&oacute;n de env&iacute;o</strong>
				</div>
				<div class="fullWidth third">
					<div>
						Calle y n&uacute;mero<br>
						<input type="text" id="bpSStreet" name="bpSStreet" required>
					</div>
					<div>
						Colonia<br>
						<input type="text" id="bpSCol" name="bpSCol" required>
					</div>
					<div>
						Ciudad<br>
						<input type="text" id="bpSCity" name="bpSCity" required>
					</div>
				</div>
				<div class="fullWidth third">
					<div>
						Municipio / Delegaci&oacute;n<br>
						<input type="text" id="bpSCounty" name="bpSCounty" required>
					</div>
					<div>
						Estado<br>
						<input type="text" id="bpSState" name="bpSState" required>
					</div>
					<div>
						Pa&iacute;s<br>
						<input type="text" id="bpSCountry" name="bpSCountry" required>
					</div>
				</div>
				<div class="fullWidth third">
					<div>
						C&oacute;digo postal<br>
						<input type="text" id="bpSZip" name="bpSZip" required>
					</div>
				</div>
				<!-- Contact Persons -->
				<div class="fullWidth qSec">
						<strong>Personas de contacto</strong>
				</div>
				<div class="fullWidth third">
					<div>
						Compras<br>
						<input type="text" id="cmName" name="cmName" required>
					</div>
					<div>
						Tel&eacute;fono<br>
						<input type="text" id="cmPhone" name="cmPhone" required>
					</div>
					<div>
						E-mail<br>
						<input type="text" id="cmEmail" name="cmEmail" required>
					</div>
				</div>
           		<div class="fullWidth third">
					<div>
						Recepci&oacute;n de Documentos<br>
						<input type="text" id="rdName" name="rdName" required>
					</div>
					<div>
						Tel&eacute;fono<br>
						<input type="text" id="rdPhone" name="rdPhone" required>
					</div>
					<div>
						E-mail<br>
						<input type="text" id="rdEmail" name="rdEmail" required>
					</div>
				</div>
           		<div class="fullWidth third">
					<div>
						Pagos<br>
						<input type="text" id="pgName" name="pgName" required>
					</div>
					<div>
						Tel&eacute;fono<br>
						<input type="text" id="pgPhone" name="pgPhone" required>
					</div>
					<div>
						E-mail<br>
						<input type="text" id="pgEmail" name="pgEmail" required>
					</div>
				</div>
            </div>
        </td>
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
    	<td colspan="3"><strong>Aviso de privacidad:</strong> <?php echo strtoupper($_SESSION["companyName"]); ?> se compromete a que toda documentaci&oacute;n oficial que nos env&iacute;e, ser&aacxute; tratada en forma confidencial.</td>
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
    </table>";

var morDocs = "<table class='formTable'><tr>\
    	<td><label for='morActa'>Acta constitutiva</label></td>\
        <td colspan='2'><input type='file' name='morActa' id='morActa' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morCompDom'>Comprobante de domicilio fiscal</label></td>\
        <td colspan='2'><input type='file' name='morCompDom' id='morCompDom' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morFormR2'>Formulario R2 (Alta en SHCP)</label></td>\
        <td colspan='2'><input type='file' name='morFormR2' id='morFormR2' required/></td>\
    </tr>\
    <tr>\
    	<td><label for='morCedFis'>Cédula fiscal</label></td>\
        <td colspan='2'><input type='file' name='morCedFis' id='morCedFis' required/></td>\
    </tr>\
    </table>";

$("#bpType").on("change", function() {
	bpType = $(this).val();
	if (bpType == "F") {
		$("#documents").html(fisDocs);
	} else {
		$("#documents").html(morDocs);
	}
});
</script>

<?php include 'footer.php'; ?>