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
<form method="post" action="/includes/AltaSN.php" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
    <tr>
      <td colspan="3" class="qSec">Datos del cliente</td>
    </tr>
    <tr>
      <td>Raz&oacute;n social</td>
      <td>Persona de contacto</td>
      <td></td>
    </tr>
    <tr>
      <td><input type="text"  id="Name_SN" name="Name_SN" required></td>
      <td><input type="text"  id="Name_CP" name="Name_CP" required></td>
      <td></td>
    </tr>
    <tr>
      <td>Tel&eacute;fono</td>
      <td>Email</td>
      <td></td>
    </tr>
    <tr>
      <td><input type="text" id="Tel_SN" name="Tel_SN" onKeyPress="return numeros(event)" required></td>
      <td><input type="email" id="email_SN" name="email_SN" required></td>
      <td></td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Comentarios</td>
    </tr>
    <tr>
      <td colspan="3"><textarea id="Comentarios" name="Comentarios"></textarea></td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="button red" onClick="cancel();">Cancelar</button></td>
      <td>&nbsp;</td>
      <td align="right"><button type="submit" class="button green">Guardar</button></td>
    </tr>
  </tbody>
</table>

</form>



<script type="text/javascript">
function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " 0123456789-+()";
    especiales = [8,37,39,46];
 
    tecla_especial = false
    for(var i in especiales){
 if(key == especiales[i]){
     tecla_especial = true;
     break;
        } 
    }
 
    if(letras.indexOf(tecla)==-1 && !tecla_especial)
        return false;
}

$(document).ready(function(){
	$("select").select2();
	$('#altaCliBT').addClass('active');
	$('#subHeaderTitle').html('Alta de cliente');
	
	$("#pais").change(function () {
		$("#textPais").val($(this).find(":selected").text());
		$("#pais option:selected").each(function () {
			elegido = $(this).val();
            $.post("includes/estados.php", { elegido: elegido }, function(data) {
            	$("#estado").html(data);
            });            
        });
	});
	
	$("#estado").change(function () {
		$("#textEstado").val($(this).find(":selected").text());
	});

	$("#paise").change(function () {
		$("#textPaisE").val($(this).find(":selected").text());
		$("#paise option:selected").each(function () {
            elegidoe = $(this).val();
            $.post("includes/estadose.php", { elegidoe: elegidoe }, function(data) {
            	$("#estadoe").html(data);
            });            
        });
	});
	
	$("#estadoe").change(function () {
		$("#textEstadoE").val($(this).find(":selected").text());
	});
});
function selec() 
{ 
var op=document.getElementById("CondPag"); 
	if (op.selectedIndex==1) {
		document.getElementById("Lim_Cred").value="0";
		document.getElementById("Lim_Cred").disabled= true;
	}
	else 
	{
		document.getElementById("Lim_Cred").disabled= false;
	}
} 

var equalDomFx = function() {
	if($(this).is(':checked')){
        $("#Calle_E").val($("#Calle_F").val());
		$("#Colonia_E").val($("#Colonia_F").val());
		$("#Mun_Del_E").val($("#Mun_Del_F").val());
		$("#Ciudad_E").val($("#Ciudad_F").val());
		$("#paise").val($("#pais").val()).trigger("change");
		$("#estadoe").val($("#estado").val()).trigger("change");
		$("#CP_E").val($("#CP_F").val());
    }
};

$("#equalDom").on('change', equalDomFx);
</script>

<?php include 'footer.php'; ?>