<?php
$queryFile = "SELECT id, file, empl, MAX(dateCreated) FROM PLST";
$resultFile = mysql_query($queryFile);
$rowFile = mysql_fetch_assoc($resultFile);
$pListfile = $rowFile["file"];
?>

<img src="/images/intranet-logo.png" class="navLogo">
<a href="#" id="respMenuTrigger"><img src="/images/menu-icon.png"></a>
<ul class="mainNav">
	<li><a href="/home.php"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
	<?php
	if ($_SESSION["isBP"]) {
	?>
	<li><a href="/miscotizaciones.php"><i class="fa fa-list" aria-hidden="true"></i> Mis cotizaciones</a>
	<li><a href="/misfacturas.php"><i class="fa fa-file-text-o" aria-hidden="true"></i> Mis facturas</a></li>
	<li><a href="/existencias.php"><i class="fa fa-cubes" aria-hidden="true"></i> Existencias</a></li>
	<?php
	} else {

		if ($_SESSION["admin"] != 'W' && $_SESSION["admin"] != 'A') {
		?>
			<li><a><i class="fa fa-list" aria-hidden="true"></i> Cotizaciones</a>
				<ul>
					<li><a href="/cotizador.php"><i class='fa fa-asterisk' aria-hidden='true'></i> Nueva cotizaci&oacute;n</a></li>
					<li><a href="/buscarcotizaciones.php"><i class='fa fa-search' aria-hidden='true'></i> Buscar cotizaciones</a></li>
					<li><a href="/altacliente.php"><i class="fa fa-address-card-o" aria-hidden="true"></i> Alta de cliente</a></li>
					<li><a href="/nuevoarticulo.php"><i class="fa fa-cube" aria-hidden="true"></i> Art&iacute;culo nuevo</a></li>
					<li><a href="/ftp/pList/<?php echo $pListfile; ?>"><i class="fa fa-exchange" aria-hidden="true"></i> Referencia cruzada</a></li>
				</ul>
			</li>
			<li><a href="/buscarpedidos.php"><i class="fa fa-cubes" aria-hidden="true"></i> Pedidos</a></li>
			<li><a href="/buscarentregas.php"><i class="fa fa-truck" aria-hidden="true"></i> Entregas</a></li>
			<li><a href="/buscarfacturas.php"><i class="fa fa-file-text-o" aria-hidden="true"></i> Facturas</a></li>
			<li><a href="/veravance.php"><i class="fa fa-line-chart" aria-hidden="true"></i> Monitor de avance</a></li>
			<li><a><i class="fa fa-table" aria-hidden="true"></i> Reportes</a>
				<ul>
					<li><a href="/reportsGenerator.php?report=antSalCli">Antig&uuml;edad de saldos de clientes</a></li>
					<li><a href="/reportsGenerator.php?report=estCom">Comisiones estimadas</a></li>
					<li><a href="/reportsGenerator.php?report=resCom">Resumen de comisiones</a></li>
					<li><a href="/reportsGenerator.php?report=edoCta">Estado de cuenta</a></li>
				</ul>
			</li>
			<li><a><i class="fa fa-question" aria-hidden="true"></i> Solicitudes</a>
				<ul>
					<li><a href="/solCliente.php"><i class="fa fa-address-card-o" aria-hidden="true"></i> Alta cliente SAP</a></li>
					<li><a href="/solCredito.php"><i class="fa fa-credit-card" aria-hidden="true"></i> Solicitud de cr&eacute;dito</a></li>
				</ul>
			</li>
			<li><a href="/verProductos.php"><i class="fa fa-book" aria-hidden="true"></i> Articulos</a></li>
			<li><a href="/documentos.php"><i class="fa fa-book" aria-hidden="true"></i> Biblioteca</a></li>

			<?php
			if ($_SESSION["admin"] == 'Y') {
			?>
			<li><a><i class="fa fa-cogs" aria-hidden="true"></i> Administraci&oacute;n</a>
				<ul>
					<li><a href="/genadmin.php">General</a></li>
					<li><a href="/emplAdmin.php">Usuarios</a></li>
					<li><a href="/docsadmin.php">Documentos</a></li>
					<li><a href="/slideradmin.php">Slider</a></li>
					<li><a href="/blog.php">Blog</a></li>
				</ul>
			</li>
				
			<?php
			}
			?>
		<?php
		} elseif ($_SESSION["admin"] == 'W') {
		?>
		<li><a href="/buscarentregasalmacen.php"><i class="fa fa-truck" aria-hidden="true"></i> Entregas</a></li>
		<?php
		} elseif ($_SESSION["admin"] == 'A') {
		?>
		<li><a href="/buscarfacturas.php"><i class="fa fa-file-text-o" aria-hidden="true"></i> Facturas</a></li>
		<?php
		}
	}
	?>
</ul>

<script>
$(document).ready(function(){
    $("#respMenuTrigger").click(function(){
        $(".mainNav").slideToggle("fast");
    });
});
</script>
