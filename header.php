<?php
$curRates = array();
$queryCurRates = "SELECT Currency, Rate FROM ORTT WHERE RateDate = CONVERT(date, getdate()) AND Currency = 'USD'";
$resultCurRates = mssql_query($queryCurRates);
while ($rowCurRates = mssql_fetch_assoc($resultCurRates)) {
	$curRates[$rowCurRates["Currency"]] = $rowCurRates["Rate"];
}

$queryFile = "SELECT id, file, empl, dateCreated FROM PLST ORDER BY dateCreated DESC LIMIT 1";
$resultFile = mysql_query($queryFile);
$rowFile = mysql_fetch_assoc($resultFile);
$pListfile = $rowFile["file"];

$logo = "";
switch ($_SESSION["company"]) {
			case "alianza":
			case "pacifico":
				$logo = "alianza";
				break;
			case "sureste":
				$logo = "alianza-sureste";
				break;
			case "fg":
				$logo = "fg";
				break;
			case "alianzati":
				$logo = "alianzati";
				break;
			case "mbr":
				$logo = "mbr";
				break;
		}

?>
<div id="header">
	<ul class="desktop links-header">
  		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script>
			$(function() {
						$( "#links" ).sortable();
						$( "#links" ).disableSelection();
			});
		</script>
        <ul id="links">
                	<?php
					if ($_SESSION["isBP"]) {
					?>
						<li><a href="miscotizaciones.php"><img src="/images/newquote.jpg"><br>Mis cotizaciones</a></li>
						<li><a href="misfacturas.php"><img src="/images/duereport.jpg"><br>Mis facturas</a></li>
						<li><a href="existencias.php"><img src="/images/deliver.jpg"><br>Existencias</a></li>
					<?
					} elseif ($_SESSION["admin"] != 'W') {
					?>
                        <li><a href="cotizador.php"><img src="/images/newquote.jpg"><br>Cotizador</a></li>
                        <li><a href="reportsGenerator.php?report=antSalCli"><img src="/images/duereport.jpg"><br>Saldos</a></li>
                        <li><a href="altacliente.php"><img src="/images/newclient.jpg"><br>Alta cliente</a></li>
                        <li><a href="nuevoarticulo.php"><img src="/images/newitem.jpg"><br>Nuevo art&iacute;culo</a></li>
                        <li><a href="veravance.php"><img src="/images/semaphore.jpg"><br>Monitor de avance</a></li>
                    <?php
					} else {
					?>
                    	<li><a href="buscarentregasalmacen.php"><img src="/images/deliver.jpg"><br>Entregas</a></li>
                    <?php
					}
					?>
        </ul>
	</ul>
	<ul class="desktop">
    	<li></li>
		<li><span class="greeting"></span> <strong><?php echo $_SESSION["name"]; ?></strong>, bienvenid@ a&nbsp;<img src='/images/logo-<?php echo $logo; ?>.png' class='comp-logo'>
        </li>
        <li></li>
        <li>
        <?php
		foreach($curRates as $cur => $value) {
			echo $cur.": <strong>".number_format($value, 4)."</strong>&nbsp;";
		}
		?>
        </li>
        <li></li>
        <li><a href="/includes/logout.php" class="logout"><i class="fa fa-power-off" aria-hidden="true"></i> Salir</a></li>
    </ul>
    <ul class="mobile">
    	<li><img src='/images/logo-<?php echo $logo; ?>.png' class='comp-logo'>
        </li>
        <li>
        <?php
		foreach($curRates as $cur => $value) {
			echo $cur.": <strong>".number_format($value, 4)."</strong>&nbsp;";
		}
		?>
        </li>
        <li><a href="/includes/logout.php" class="logout"><i class="fa fa-power-off" aria-hidden="true"></i></a></li>
    </ul>
</div>

<div id="subHeader">
    <div class="mobile subHeaderTop">
        <div id="subHeaderTitle">&nbsp;</div>
        <a href="#" id="respSubMenuTrigger"><img src="/images/dropDown.png"></a>
    </div>
    <ul class="subNav">
        <?php
        $fileName = basename($_SERVER["SCRIPT_FILENAME"], '.php');
        switch ($fileName) {
            case "cotizador":
            case "buscarcotizaciones":
            case "altacliente":
            case "nuevoarticulo":
            case "vercotizacion":
			case "vercotizacionexp":
            case "cotizaciones":
                echo "<li><a id='newCotBT' href='cotizador.php'><i class='fa fa-asterisk' aria-hidden='true'></i> Nueva cotizaci&oacute;n</a></li>";
                echo "<li><a id='listCotBT' href='buscarcotizaciones.php'><i class='fa fa-search' aria-hidden='true'></i> Buscar cotizaciones</a></li>";
                echo "<li><a id='altaCliBT' href='altacliente.php'><i class='fa fa-address-card-o' aria-hidden='true'></i> Alta de cliente</a></li>";
                echo "<li><a id='newArtBT' href='nuevoarticulo.php'><i class='fa fa-cube' aria-hidden='true'></i> Art&iacute;culo nuevo</a></li>";
				echo "<li><a id='prodList' href='ftp/pList/".$pListfile."' target='_blank'><i class='fa fa-exchange' aria-hidden='true'></i> Referencia cruzada</a></li>";
                break;
            case "reportsGenerator":
            case "antSalCli":
			case "estCom":
            case "resCom":
            case "edoCta":
                echo "<li><a id='antSalCliBT' href='reportsGenerator.php?report=antSalCli'>Antig&uuml;edad de saldos de clientes</a></li>";
                echo "<li><a id='estComBT' href='reportsGenerator.php?report=estCom'>Comisiones estimadas</a></li>";
				echo "<li><a id='resComBT' href='reportsGenerator.php?report=resCom'>Resumen de comisiones</a></li>";
                echo "<li><a id='edoCtaBT' href='reportsGenerator.php?report=edoCta'>Estado de cuenta</a></li>";
                break;
            case "intranetadmin":
            case "slideradmin":
            case "docscapacitacion":
			case "herramientasadmin":
			case "docsadmin":
                echo "<li><a id='toolsBT' href='/docsadmin.php'>Herramientas</a></li>";
                echo "<li><a id='trainBT' href='/docscapacitacion.php'>Capacitaci&oacute;n</a></li>";
                echo "<li><a id='slideBT' href='/slideradmin.php'>Slider</a></li>";
                break;
				
			case "genadmin":
			case "herramientas":
			case "home":
			case "capacitacion":
			case "buscarentregasalmacen":
			case "entregasalmacen":
			case "entregar":
			case "verfactura":
			case "facturas":
			case "miscotizaciones":
			case "misfacturas":
			case "cotizacioncliente":
			case "existencias":
				echo "<style>
						#subHeader {
							display:none;
						}
						#header {
							margin-bottom:20px;
						}
					</style>";
				break;
        }
        ?>
    </ul>
</div>

<script>
$(document).ready(function(){
    $("#respSubMenuTrigger").click(function(){
        $(".subNav").slideToggle("fast");
		$(this).toggleClass("rotate");
    });
	
	var thehours = new Date().getHours();
	var themessage;
	var morning = ('Buen d&iacute;a');
	var afternoon = ('Buenas tardes');
	var evening = ('Buenas noches');

	if (thehours >= 0 && thehours < 12) {
		themessage = morning; 

	} else if (thehours >= 12 && thehours < 17) {
		themessage = afternoon;

	} else if (thehours >= 17 && thehours < 24) {
		themessage = evening;
	}

	$('.greeting').html(themessage);
});
</script>