<div id="footer">
	<?php
	switch ($_SESSION["company"]) {
		case "alianza":
		case "sureste":
		case "pacifico":
			echo "<a href='http://alianzaelectrica.com/' target='_blank'>www.alianzaelectrica.com</a>";
			break;
		case "fg":
			echo "<a href='http://fgelectrical.com/' target='_blank'>www.fgelectrical.com</a>";
			break;
		case "alianzati":
			echo "<a href='http://alianza-ti.com/' target='_blank'>www.alianza-ti.com</a>";
			break;
		case "mbr":
			echo "<a href='http://mbrhosting.com/' target='_blank'>www.mbrhosting.com</a>";
			break;
	}
	?>
</div>

    </div>
</div>

<?php

if(isset($_GET['msg']))
{
	$msg = $_GET['msg'];
	switch($msg) {
		case "sapcreated":
			$message = "Documento SAP creado con &eacute;xito";
			break;
		case "snsuccess":
			$message = "Nuevo cliente guardado con &eacute;xito";
			break;
		case "artsuccess":
			$message = "Nuevo art&iacute;culo guardado con &eacute;xito";
			break;
		case "cotsuccess":
			$message = "Su cotizaci&oacute;n con el folio ".$_GET["fquote"]." ha sido guardada con &eacute;xito.<br><a href='/vercotizacion.php?idCot=".$_GET["fquote"]."'>Ver cotizaci&oacute;n.</a>";
			break;
		case "toolsuccess":
			$message = "Documento guardado con &eacute;xito";
			break;
		case "slidesuccess":
			$message = "Slide guardado con &eacute;xito";
			break;
		case "cotupsuccess":
		case "filesuccess":
			$message = "Cambios guardados con &eacute;xito";
			break;
		case "sentemail":
			$message = "E-mail enviado con &eacute;xito";
			break;
		case "solpedido":
			$message = "Solicitud de pedido enviada con &eacute;xito";
			break;
		case "valpago":
			$message = "Solicitud de validaci&oacute;n de pago enviada con &eacute;xito";
			break;
		case "dlvsuccess":
			$message = "Entrega registrada con &eacute;xito";
			break;
	}
	echo "<div class='overlay' style='display:block'><div id='message'><img src='images/ok-icon.png'> $message<div class='closeMessage'>[Aceptar]</div></div></div>";
}
?>

<script>
$(document).ready(function(){
    $("#hMenuTrigger").click(function(){
        $("#headerMenu").toggle();
    });
	$(".closeMessage").click(function() {
		$(this).closest(".overlay").remove();
	});
});

function localeString(x, sep, grp) {
	x = parseFloat(x).toFixed(2);
    var sx = (''+x).split('.'), s = '', i, j;
    sep || (sep = ','); // default seperator
    grp || grp === 0 || (grp = 3); // default grouping
    i = sx[0].length;
    while (i > grp) {
        j = i - grp;
        s = sep + sx[0].slice(j, i) + s;
        i = j;
    }
    s = sx[0].slice(0, i) + s;
    sx[0] = s;
    return sx.join('.')
};

function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " 0123456789";
    especiales = [8,37,46];
 
    tecla_especial = false
    for(var i in especiales){
 if(key == especiales[i]){
     tecla_especial = true;
     break;
        } 
    }
 
    if(letras.indexOf(tecla)==-1 && !tecla_especial)
        return false;
};

function getback() {
	window.history.back();
};
</script>
</body>

</html>
<?php
//$compressor->finish();
?>