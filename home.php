<?php
include 'head.php';


$curMonth = date('n');
$curYear  = date('Y');

$queryCot = "SELECT SUM(Total_Doc) AS totCot FROM COTI WHERE YEAR(FechaCreacion) = $curYear AND MONTH(FechaCreacion) = $curMonth AND company = '".$_SESSION["company"]."'";
if ($_SESSION["admin"] != 'Y') {
	$queryCot .= " AND Empl_Ven = '".$_SESSION["salesPrson"]."'";
}
$resultCot = mysql_query($queryCot);
$rowCot = mysql_fetch_assoc($resultCot);
$totCot = $rowCot["totCot"];


$queryCot100 = "SELECT SUM(Total_Doc) AS totCot FROM COTI WHERE YEAR(FechaCreacion) = $curYear AND MONTH(FechaCreacion) = $curMonth AND Total_Doc >= '100000' AND company = '".$_SESSION["company"]."'";
if ($_SESSION["admin"] != 'Y') {
	$queryCot100 .= " AND Empl_Ven = '".$_SESSION["salesPrson"]."'";
}
$resultCot100 = mysql_query($queryCot100);
$rowCot100 = mysql_fetch_assoc($resultCot100);
$totCot100 = $rowCot100["totCot"];

$queryCant100 = "SELECT Total_Doc FROM COTI WHERE YEAR(FechaCreacion) = $curYear AND MONTH(FechaCreacion) = $curMonth AND Total_Doc >= '100000' AND company = '".$_SESSION["company"]."'";
if ($_SESSION["admin"] != 'Y') {
	$queryCant100 .= " AND Empl_Ven = '".$_SESSION["salesPrson"]."'";
}
$resultCant100 = mysql_query($queryCant100);
$rowCount100 = mysql_num_rows($resultCant100);






$querysales100 = "SELECT [TotSales] = 
ISNULL((SELECT SUM(T2.LineTotal*(100-T1.DiscPrcnt)/100) AS TOTAL
FROM OINV T1 JOIN INV1 T2 
ON T1.DocEntry = T2.DocEntry
WHERE DATEPART(YEAR,T1.DocDate) = $curYear AND DATEPART(MONTH,T1.DocDate) = $curMonth AND TotSales >= '100000'" ;



$queryTotSales = "SELECT [TotSales] = 
ISNULL((SELECT SUM(T2.LineTotal*(100-T1.DiscPrcnt)/100) AS TOTAL
FROM OINV T1 JOIN INV1 T2 
ON T1.DocEntry = T2.DocEntry
WHERE DATEPART(YEAR,T1.DocDate) = $curYear AND DATEPART(MONTH,T1.DocDate) = $curMonth ";

if ($_SESSION["admin"] != 'Y') {
	$queryTotSales .= " AND T1.SlpCode = '".$_SESSION["salesPrson"]."'";
}

$queryTotSales.= ")

-

ISNULL((SELECT SUM(T8.LineTotal) AS TOTAL

FROM ORIN T7 JOIN RIN1 T8  
ON T7.DocEntry = T8.DocEntry

WHERE DATEPART(YEAR,T7.DocDate) = $curYear AND DATEPART(MONTH,T7.DocDate) = $curMonth AND T8.BaseType != '203' ";

if ($_SESSION["admin"] != 'Y') {
	$queryTotSales .= " AND T7.SlpCode = '".$_SESSION["salesPrson"]."'";
}

$queryTotSales.= "),0),0)";

$resultTotSales = mssql_query($queryTotSales);
$rowTotSales = mssql_fetch_assoc($resultTotSales);
$totSales = $rowTotSales["TotSales"];
$totCom = round($totCot / 116 * $_SESSION["commission"], 2);

$esMonths = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

//Prueba existencias
$whCodes = array();
$whNames = array();
$queryWH = "SELECT WhsCode, LEFT(WhsName, 3) AS WhsName FROM OWHS WHERE Inactive <> 'Y' AND U_usable = 'Y' ORDER BY WhsCode ASC";
$resultWH = mssql_query($queryWH);
while ($rowWH = mssql_fetch_assoc($resultWH)) {
	$whCodes[] = $rowWH["WhsCode"];
	$whNames[] = $rowWH["WhsName"];
}
//Termina Prueba existencias -->


?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>

// Forecast
$(function () {
	Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: 'Arial Narrow, Arial, "Helvetica Neue", "Lato", "Segoe UI", Helvetica, sans-serif'
            }
        }
    });
	$('#forecastGraph').highcharts({
		chart: {
			type: 'column',
			zoomType: 'y'
		},
		title: {
			text: 'Forecast Mes Corriente',
			style: {
				fontSize: '14px',
				fontWeight: 'bold'
			}
		},
		xAxis: {
			categories: ['<?php echo $esMonths[$curMonth - 1]; ?>']
		},
		yAxis: {
			title: {
				text: 'Moneda Nacional'
			},
			min: 0
		},
		series: [{
			name: 'Monto Cotizado',
			data: [<?php echo $totCot; ?>],
			color: '#32a5d5'
		},
		{
			name: 'Cotizaciones > 100,000',
			data: [<?php echo $totCot100; ?>],
			color: '#0C50E0'
		},				 
		{
			name: 'Comisi\xF3n Estimada',
			data: [<?php echo $totCom; ?>],
			color: '#3733d3'
		},
		{
			name: 'Monto Vendido',
			data: [<?php echo $totSales; ?>],
			color: '#e65e26'
		}
		]
	});
});
</script>

<div id="pageContainer">
    <div id="leftCol">
    	<div class="module">
        	<div id="slider">
			<script type="text/javascript" src="js/jssor.slider.mini.js"></script>


			<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>-->
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.1.1/Chart.min.js"></script>
            <script src="js/hc/highcharts.js"></script>
            <!-- use jssor.slider.debug.js instead for debug -->
            <script>
                jQuery(document).ready(function ($) {



                    
                    var jssor_1_SlideoTransitions = [
                      [{b:5500.0,d:3000.0,o:-1.0,r:240.0,e:{r:2.0}}],
                      [{b:-1.0,d:1.0,o:-1.0,c:{x:51.0,t:-51.0}},{b:0.0,d:1000.0,o:1.0,c:{x:-51.0,t:51.0},e:{o:7.0,c:{x:7.0,t:7.0}}}],
                      [{b:-1.0,d:1.0,o:-1.0,sX:9.0,sY:9.0},{b:1000.0,d:1000.0,o:1.0,sX:-9.0,sY:-9.0,e:{sX:2.0,sY:2.0}}],
                      [{b:-1.0,d:1.0,o:-1.0,r:-180.0,sX:9.0,sY:9.0},{b:2000.0,d:1000.0,o:1.0,r:180.0,sX:-9.0,sY:-9.0,e:{r:2.0,sX:2.0,sY:2.0}}],
                      [{b:-1.0,d:1.0,o:-1.0},{b:3000.0,d:2000.0,y:180.0,o:1.0,e:{y:16.0}}],
                      [{b:-1.0,d:1.0,o:-1.0,r:-150.0},{b:7500.0,d:1600.0,o:1.0,r:150.0,e:{r:3.0}}],
                      [{b:10000.0,d:2000.0,x:-379.0,e:{x:7.0}}],
                      [{b:10000.0,d:2000.0,x:-379.0,e:{x:7.0}}],
                      [{b:-1.0,d:1.0,o:-1.0,r:288.0,sX:9.0,sY:9.0},{b:9100.0,d:900.0,x:-1400.0,y:-660.0,o:1.0,r:-288.0,sX:-9.0,sY:-9.0,e:{r:6.0}},{b:10000.0,d:1600.0,x:-200.0,o:-1.0,e:{x:16.0}}]
                    ];
                    
                    var jssor_1_options = {
                      $AutoPlay: true,
                      $SlideDuration: 1200,
					  $Idle: 8000,
                      $SlideEasing: $Jease$.$OutQuint,
                      $CaptionSliderOptions: {
                        $Class: $JssorCaptionSlideo$,
                        $Transitions: jssor_1_SlideoTransitions
                      },
                      $ArrowNavigatorOptions: {
                        $Class: $JssorArrowNavigator$
                      },
                      $BulletNavigatorOptions: {
                        $Class: $JssorBulletNavigator$
                      }
                    };
                    
                    var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
                    
                    //responsive code begin
                    //you can remove responsive code if you don't want the slider scales while window resizes
                    function ScaleSlider() {
                        var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                        if (refSize) {
                            refSize = Math.min(refSize, 1920);
                            jssor_1_slider.$ScaleWidth(refSize);
                        }
                        else {
                            window.setTimeout(ScaleSlider, 30);
                        }
                    }
                    ScaleSlider();
                    $(window).bind("load", ScaleSlider);
                    $(window).bind("resize", ScaleSlider);
                    $(window).bind("orientationchange", ScaleSlider);
                    //responsive code end
                });
            </script>
            <style>
				
				/* jssor slider bullet navigator skin 05 css */
				/*
				.jssorb05 div           (normal)
				.jssorb05 div:hover     (normal mouseover)
				.jssorb05 .av           (active)
				.jssorb05 .av:hover     (active mouseover)
				.jssorb05 .dn           (mousedown)
				*/
				.jssorb05 {
					position: absolute;
				}
				.jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
					position: absolute;
					/* size of bullet elment */
					width: 16px;
					height: 16px;
					background: url('img/b05.png') no-repeat;
					overflow: hidden;
					cursor: pointer;
				}
				.jssorb05 div { background-position: -7px -7px; }
				.jssorb05 div:hover, .jssorb05 .av:hover { background-position: -37px -7px; }
				.jssorb05 .av { background-position: -67px -7px; }
				.jssorb05 .dn, .jssorb05 .dn:hover { background-position: -97px -7px; }
		
				/* jssor slider arrow navigator skin 22 css */
				/*
				.jssora22l                  (normal)
				.jssora22r                  (normal)
				.jssora22l:hover            (normal mouseover)
				.jssora22r:hover            (normal mouseover)
				.jssora22l.jssora22ldn      (mousedown)
				.jssora22r.jssora22rdn      (mousedown)
				*/
				.jssora22l, .jssora22r {
					display: block;
					position: absolute;
					/* size of arrow element */
					width: 40px;
					height: 58px;
					cursor: pointer;
					background: url('img/a22.png') center center no-repeat;
					overflow: hidden;
				}
				.jssora22l { background-position: -10px -31px; }
				.jssora22r { background-position: -70px -31px; }
				.jssora22l:hover { background-position: -130px -31px; }
				.jssora22r:hover { background-position: -190px -31px; }
				.jssora22l.jssora22ldn { background-position: -250px -31px; }
				.jssora22r.jssora22rdn { background-position: -310px -31px; }

				/*POPUP CIERRE DE COTIZACIONES*/
				.quotation-notifier{
					/*position: absolute;*/
					margin-top: 110px;
					width: auto;
					max-height: 400px;
					overflow-y: auto;
					top: 150px;
					z-index: 99;
					background-color: white;
					padding: 10px;
					padding-top: 10px;
					padding-bottom: 20px;
					-webkit-box-shadow: 0px 0px 23px rgba(0,0,0,0.75);
					-moz-box-shadow: 0px 0px 23px rgba(0,0,0,0.75);
					box-shadow: 0px 0px 23px rgba(0,0,0,0.75);
				}
				.quotation-notifier .div-closer{
					/*position: absolute;*/
					background-color: #F15008;
					color: white;
					top: 0;
					right: 0;
					padding-top: 3px;
					padding-right: 3px;
				}
				.quotation-notifier .div-closer:hover{
					cursor: pointer;
				}
				.quotation-notifier h2{
					text-align: center;
				}
				.quotation-notifier .quotation-row{
					text-align: center;
					margin-top: 2px;
					margin-bottom: 2px;
				}
				.quotation-notifier button:hover{
					cursor: pointer;
				}
				.quotation-notifier button{
					border: none;
					border-radius: 5px;
					padding: 4px;
					margin-left: 3px;
					margin-right: 3px;
				}
				.quotation-notifier .hold-btn{
					background-color: #ff4235;
					color: white;
				}
				.alianza-btn{
					background-color: #ff4235;
					color: white;
					border:none;
					border-radius: 5px;
					padding-top: 3px;
					padding-bottom: 3px;
				}
				.alianza-btn:hover{
					cursor: pointer;
				}
				.quotation-notifier .hold-btn:disabled{
					background-color: #ff574c;
					color: #5b4e4d;
					cursor: no-drop;
				}
				.quotation-notifier .close-btn{
					background-color: #0d1ca8;
					color: white;
				}
				.quotation-notifier .close-btn:disabled{
					background-color: #3440af;
					color: #d5d6e0;
					cursor: no-drop;
				}
				.quotation-notifier input.sm{
					width: 100px;
				}
				.quotation-notifier input.xs{
					width: 40px;
				}
				.quotation-notifier input.md{
					width: 200px;
				}

				table.close-q-table{
					table-layout: fixed;
					border-collapse: collapse;
				}
				table.close-q-table thead{
					background-color: #8A898F;
					color: white;

				}
				table.close-q-table td,table.close-q-table th{
					white-space: nowrap;
					padding: 3px;
					text-align: center;
				}
				table.close-q-table th{
					padding-left: 10px;
					padding-right: 10px;
				}
				table.close-q-table tbody tr td div{
					background-color: #D9D9D9;
					color: #888;
					padding: 20px;
					padding-top: 2px;
					padding-bottom: 2px;
				}
				table.close-q-table tbody tr td div input, table.close-q-table tbody tr td div select{
					background-color: #D9D9D9;
					color: #888;
					border:none;
				}
				table.close-q-table tbody tr td div.custom-select{
					background-color: #F15008;
				}
				.custom-select select:disabled{
					/*background-color: #f76625 !important;*/
				}
				table.close-q-table tbody tr td div.custom-select select{
					background-color: #F15008;
				    -moz-appearance: none;
				    -webkit-appearance: none;
				    appearance: none;
				    color: white;
				    background-position: calc(100% - 5px) calc(1em - 3px), calc(100% - 0px) calc(1em - 3px), 100% 0;
				    background-size: 5px 5px, 5px 5px, 2.5em 2.5em;
				    background-repeat: no-repeat;
				    background-image: linear-gradient(45deg, transparent 50%, white 50%), linear-gradient(135deg, white 50%, transparent 50%), linear-gradient(to right, #F15008, #F15008);
				    padding-right: 15px;
				}
				table.close-q-table tbody tr td div.update-q-btn{
					background-color: #F15008;
				}
				table.close-q-table tbody tr td div input.datepicker{
					text-align: center;
				}
				table.close-q-table tbody tr td div.update-q-btn button{
					background-color: #F15008;
					color: white;
				}
				table.close-q-table tbody tr td div.update-q-btn button:disabled{
					cursor: no-drop;
					background-color: #f76625;
				}
				.no-padding-right{
					padding-right: 0px !important;
				}
				.no-padding-left{
					padding-left: 0px !important;
				}
				.no-wrap{
					white-space: normal !important;
					width: 150px !important;
				}
				.lost-select{
				    width: 110px !important;
				}
			</style>
            	<div id="jssor_1" style="position:relative; margin:0 auto; top:0px; left:0px; width:960px; height:560px; overflow:hidden; visibility:hidden;">
                    <!-- Loading Screen -->
                    <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                        <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                        <div style="position:absolute;display:block;background:url('img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
                    </div>
                    <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 960px; height: 560px; overflow: hidden;">
                    	<?php
						if($_SESSION["isBP"]) {
							$querySlide = "SELECT title, link, file FROM SLDR WHERE company = '".$_SESSION["company"]."' AND  for_customer = 'Y' ORDER BY id DESC";
						} else {
							$querySlide = "SELECT title, link, file FROM SLDR WHERE company = '".$_SESSION["company"]."' AND active = 'Y'  ORDER BY id DESC";
						}
						$resultSlide = mysql_query($querySlide);
						while ($rowSlide = mysql_fetch_array($resultSlide)) {
							echo "<div data-p='225.00' style='display: none;'>";
							if(substr($rowSlide["link"], 0, 17) == "https://youtu.be/") {
								$videoID = substr(strrchr($rowSlide["link"], "/"), 1);
								echo "<iframe width='960' height='560' src='https://www.youtube.com/embed/".$videoID."' frameborder='0' allowfullscreen></iframe>";
							} else {
								echo "<a href='".$rowSlide["link"]."' target='_blank'><img data-u='image' src='ftp/slider/".$rowSlide["file"]."' /></a>";
							}
                        	echo "</div>";
						}
						?>
                    </div>
                    <!-- Bullet Navigator -->
                    <div data-u="navigator" class="jssorb05" style="bottom:16px;right:6px;" data-autocenter="1">
                        <!-- bullet navigator item prototype -->
                        <div data-u="prototype" style="width:16px;height:16px;"></div>
                    </div>
                    <!-- Arrow Navigator -->
                    <span data-u="arrowleft" class="jssora22l" style="top:123px;left:12px;width:40px;height:58px;" data-autocenter="2"></span>
                    <span data-u="arrowright" class="jssora22r" style="top:123px;right:12px;width:40px;height:58px;" data-autocenter="2"></span>
                </div>		
            </div>
        </div><!-- Slider module end -->
        <div id="prodSearch" class="module item">
        <table class="formTable">
        	<tr>
        		<td colspan="2"><strong>Buscador R&aacute;pido de Art&iacute;culos</strong>
				</td>
			</tr>
			<tr>
        		<td><input type="text" class="itemCode" placeholder="CODIGO">
				</td>
				<td><input type="text" class="itemName" placeholder="ARTICULO">
				</td>
			</tr>
			<?php
			if ($_SESSION["isBP"]) {
			?>
			
			
			<tr>
				<td class="tdSalePrice" colspan="2" align="center">Precio de Lista $<span class="listPrice">0.00</span> <span class="currency">-</span>
				</td>
			</tr>
			<?php
			} else {
			?>
			<tr>
				<td>P. Lista $<span class="listPrice">0.00</span> <span class="currency">-</span>
				</td>
				<td class="tdSalePrice" rowspan="2">$<span class="salePrice">0.00</span> <span class="currency">-</span>
				</td>
			</tr>
			<tr>
				<td>Factor <select class='discount' required style="width: auto !important; display: initial;">
                                	<?php
									$queryDisc = "SELECT Code, Name, U_discount FROM [@DISCOUNT] ORDER BY Name ASC";
									$resultDisc = mssql_query($queryDisc);
									while ($rowDisc = mssql_fetch_assoc($resultDisc)) {
										$selected = "";
										if ($rowDisc["U_discount"] == 1) {
											$selected = "selected";
										}
										echo "<option value='".$rowDisc["Name"]."' $selected>".$rowDisc["Name"]."</option>";
									}
									?>
                                </select>
				</td>
			</tr>
			<?php } ?>
			
		<!--	<tr>
				<td>Existencia <span class="onHand">-</span>
				</td>
				<td>Por recibir <span class="onOrder">-</span>
				</td>
			</tr>
		-->	
			</table>
			
			<table class="formTable">
			<!--pruebas-->
			<tr>
                          <td width="391" colspan='<?php echo count($whCodes); ?>' class='qSubSec' style='color:#3e631a'>Existencia por almac&eacute;n ( Total <span class="oHTotal">-</span>
                            <span class="qSubSec" style="color:#3e631a">)</span>
                          </td>
           </tr>
           <tr style='color:#3e631a'>
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td>".$whNames[$i]."</td>";
							}
							?>
           </tr>
           <tr style='color:#3e631a'>
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td><div class='oH".$whCode."'>-</div></td>";
							}
							?>
           </tr>
               
           <tr>
                          <td colspan='<?php echo count($whCodes); ?>' class='qSubSec' style='color:#a50000'>Pendiente por recibir en almac&eacute;n ( Total <span class="ordTotal">-</span>
                            <span class="qSubSec" style="color:#a50000">)</span></td>
           </tr>
           <tr style='color:#a50000'>
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td>".$whNames[$i]."</td>";
							}
							?>
           </tr>
           <tr style='color:#a50000'>
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td><div class='oO".$whCode." oTot'><div class='divOOSD'>\
								</div><span>-</span></div></td>";
							}
			   				?>
           </tr>
                      
          
			<!--  Terminan pruebas  -->
			
		</table>
		</div>

    	<?php
			//var_dump($_SESSION);
		echo "<div class='quotation-notifier-container' style='display:none;position:fixed;left:0;top:0;height:100%;width:100%;z-index:9999;text-align:center;'><div class='quotation-notifier' style='display:none;'><div style='text-align:right;'><button class='div-closer' onclick='closeNotifier()'>Cerrar ventana</button></div><h2>Cotizaciones por cerrar:</h2><div class='quotation-container'></div></div></div>"; //Modal para cierre de cotizaciones
    
		if($_SESSION["admin"] == "Y"){ //Admn general






			$toDate = date('Y-m-d H:i:s');
			$fromDate = strtotime ( '-45 day' , strtotime ( $toDate ) ) ;
			$fromDate = date ( 'Y-m-j' , $fromDate );
			//$fromDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', "01/11/2017")));
			$repDate = date('Y-m-d H:i:s');
			echo "<h2>Estados de cuenta con antiguedad de 45 dias:</h2>";
			echo "<select id='socios'>";
				$query = "SELECT DISTINCT T1.CardCode, T2.SlpCode, T1.CardName, (T1.CardCode + ' - ' + T1.CardName) as Name FROM OCRD T1, B1_JournalTransSourceView T2 WHERE T1.VatStatus = 'Y' AND T1.CardType = 'C' AND T1.CardCode=T2.CardCode AND T2.SlpCode = ".$_SESSION["salesPrson"];
				$result = mssql_query($query);
				$num_rows = mssql_num_rows($result);
				if( $num_rows == 0 ){
					echo "<option>No hay ningun socio de negocios</option>";
				}else{
					while($row = mssql_fetch_array($result)){
						echo "<option value='".$row["CardCode"]."'>";
						echo $row["Name"];
						echo "</option>";
					}
				}
			echo "</select><div id='edocuenta'></div>";

		}elseif($_SESSION["admin"] == "N"){ //Gerente

			$toDate = date('Y-m-d H:i:s');
			$fromDate = strtotime ( '-45 day' , strtotime ( $toDate ) ) ;
			$fromDate = date ( 'Y-m-j' , $fromDate );
			//$fromDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', "01/11/2017")));
			$repDate = date('Y-m-d H:i:s');
			echo "<h2>Estados de cuenta con antiguedad de 45 días:</h2>";
			echo "<select id='socios'>";
				$query = "SELECT DISTINCT T1.CardCode, T2.SlpCode, T1.CardName, (T1.CardCode + ' - ' + T1.CardName) as Name FROM OCRD T1, B1_JournalTransSourceView T2 WHERE T1.VatStatus = 'Y' AND T1.CardType = 'C' AND T1.CardCode=T2.CardCode AND T2.SlpCode = ".$_SESSION["salesPrson"];
				$result = mssql_query($query);
				$num_rows = mssql_num_rows($result);
				if( $num_rows == 0 ){
					echo "<option>No hay ningun socio de negocios</option>";
				}else{
					while($row = mssql_fetch_array($result)){
						echo "<option value='".$row["CardCode"]."'>";
						echo $row["Name"];
						echo "</option>";
					}
				}
			echo "</select><div id='edocuenta'></div>";
		}else if($_SESSION["admin"] == "W"){ // Marketing


			$toDate = date('Y-m-d H:i:s');
			$fromDate = strtotime ( '-45 day' , strtotime ( $toDate ) ) ;
			$fromDate = date ( 'Y-m-j' , $fromDate );
			//$fromDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', "01/11/2017")));
			$repDate = date('Y-m-d H:i:s');
			echo "<h2>Estados de cuenta con antiguedad de 45 días:</h2>";
			echo "<select id='socios'>";
				$query = "SELECT DISTINCT T1.CardCode, T2.SlpCode, T1.CardName, (T1.CardCode + ' - ' + T1.CardName) as Name FROM OCRD T1, B1_JournalTransSourceView T2 WHERE T1.VatStatus = 'Y' AND T1.CardType = 'C' AND T1.CardCode=T2.CardCode";
				$result = mssql_query($query);
				$num_rows = mssql_num_rows($result);
				if( $num_rows == 0 ){
					echo "<option>No hay ningun socio de negocios</option>";
				}else{
					while($row = mssql_fetch_array($result)){
						echo "<option value='".$row["CardCode"]."'>";
						echo $row["Name"];
						echo "</option>";
					}
				}
			echo "</select><div id='edocuenta'></div>";
		}else if($_SESSION["admin"] == NULL){ //Vendedor

			$toDate = date('Y-m-d H:i:s');
			$fromDate = strtotime ( '-45 day' , strtotime ( $toDate ) ) ;
			$fromDate = date ( 'Y-m-j' , $fromDate );
			//$fromDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', "01/11/2017")));
			$repDate = date('Y-m-d H:i:s');
			echo "<h2>Estados de cuenta con antiguedad de 45 días:</h2>";
			echo "<select id='socios'>";
				$query = "SELECT DISTINCT T1.CardCode, T2.SlpCode, T1.CardName, (T1.CardCode + ' - ' + T1.CardName) as Name FROM OCRD T1, B1_JournalTransSourceView T2 WHERE T1.VatStatus = 'Y' AND T1.CardType = 'C' AND T1.CardCode=T2.CardCode AND T2.SlpCode = ".$_SESSION["salesPrson"];
				$result = mssql_query($query);
				$num_rows = mssql_num_rows($result);
				if( $num_rows == 0 ){
					echo "<option>No hay ningun socio de negocios</option>";
				}else{
					while($row = mssql_fetch_array($result)){
						echo "<option value='".$row["CardCode"]."'>";
						echo $row["Name"];
						echo "</option>";
					}
				}
			echo "</select><div id='edocuenta'></div>";
		}
	
		?>
    </div><!-- End leftCol -->
    <div id="midCol">
    	<?php
		//if ($_SESSION["admin"] != "BP" && $_SESSION["admin"] != "W") {
			echo "<div class='module homeGraph' id='forecastGraph' ></div>";
			//var_dump($_SESSION);
	//echo "<div style='display:none;'>"; //DIV GENERAL PARA PRUEBAS
		if($_SESSION["admin"] == "Y"){ //Admn general
			echo "<h2>Cotizaciones arriba de $100'000(MXN): </h2>";
			echo "<canvas id='general' height='400' style='display:none; width:100%;'></canvas><br/>";
			echo "<table id='table-manager-coti' style='display:none;'><thead><th>ID</th><th>Nombre:</th><th>Status</th><th></th></thead><tbody></tbody></table>";

			echo "<h2>Cotizaciones arriba de $100'000 MXN por vendedor:<h2>";
			echo "<h4>Selecciona un vendedor:</h4> <select id='vendedores'></select>";
			echo "<h3 style='text-align:center;' id='status-coti' hidden='hidden'>Este vendedor no tiene cotizaciones arriba de $100'000 MXN </h3>";
			echo "<div id='sales_container'><canvas id='sales_manager' width='500' height='400' style='display:none;'></canvas><div id='info-cotizaciones'></div></div>";
			echo "<div style='text-align:center;'><table style='display:none;' id='table-manager-coti-ven'><thead><th>ID</th><th>Nombre:</th><th>Status</th><th></th></thead><tbody></tbody></table></div>";

			
			echo "<h2>Top 10 cotizaciones de sucursal: </h2>";
			echo "<canvas id='topquotbranch' width='500' height='400'></canvas><br/>";
			
			echo "<h2>Hit Rate general: </h2>";
			echo "<div id='hitrate_gral' style='padding-left:50px;'><div id='val_gral' style='border: 2px solid black; margin:auto; display:inline-block;padding:10px;font-size:30px;'></div><div id='data_gral' style='margin:auto; display:inline-block;padding:10px;font-size:20px;'></div></div>";
			
			echo "<h2>Hit Rate por vendedor: </h2>";
			echo "Selecciona un vendedor: <select id='vendedores_hitrate'></select><br/>";
			echo "<div id='hitrate_manager' style='padding-left:50px;'><div id='val' style='border: 2px solid black; margin:auto; display:inline-block;padding:10px;font-size:30px;'></div><div id='data' style='margin:auto; display:inline-block;padding:10px;font-size:20px;'></div></div>";
			echo "<h3 style='text-align:center;' id='no_sales_hr' hidden='hidden'></h3>";

			echo "<h2>Vendedores con m�s cotizaciones: </h2>";
			echo "<h4 id='no-salers' style='text-align:center;' hidden='hidden'>No hay vendedores a cargo</h4>";
			echo "<canvas id='topvendedores' width='500' height='400' style='display:none;'></canvas><br/>";

			echo "<h2>Top 10 productos por vendedor:<h2>";
			echo "<h4>Selecciona un vendedor:</h4> <select id='vendedores_prod'></select>";
			echo "<h3 style='text-align:center;' id='status-prod-vend' hidden='hidden'>Este vendedor no tiene productos</h3>";
			echo "<div id='topprods_container'><canvas id='topproductos_manager' width='500' height='400' style='display:none;'></canvas></div><br/>";
		}elseif($_SESSION["admin"] == "N"){ //Gerente
			echo "<h2>Top 10 cotizaciones de sucursal: </h2>";
			echo "<canvas id='topquotbranch' width='500' height='400'></canvas><br/>";

		}else if($_SESSION["admin"] == "W"){ // Marketing
			//echo "<h2>Cotizaciones mayores a $100'000 MXN por vendedor: </h2>";
			//echo "<canvas id='manager' width='500' height='400'></canvas>";

			echo "<h2>Cotizaciones arriba de $100'000(MXN): </h2>";
			echo "<canvas id='general' width='500' height='400' style='display:none;'></canvas><br/>";
			echo "<table id='table-manager-coti' style='display:none;'><thead><th>ID</th><th>Nombre:</th><th>Status</th><th></th></thead><tbody></tbody></table>";


			echo "<h2>Cotizaciones arriba de $100'000 MXN por vendedor:<h2>";
			echo "<h4>Selecciona un vendedor:</h4> <select id='vendedores'>";
					//Carga de vendedores
			$queryx = "SELECT SlpName, SlpCode FROM OSLP ORDER BY 1";
			$result = mssql_query($queryx);
			$num_rows = mssql_num_rows($result);
			if( $num_rows == 0 ){
				echo "<option>No hay vendedores</option>";
			}else{
				while($row = mssql_fetch_array($result)){
					echo "<option value='".$row["SlpCode"]."'>";
					echo $row["SlpName"];
					echo "</option>";
				}
			}

			echo "</select>";
			echo "<h3 style='text-align:center;' id='status-coti' hidden='hidden'>Este vendedor no tiene cotizaciones arriba de $100'000 MXN </h3>";
			echo "<div id='sales_container'><canvas id='sales_manager' width='500' height='400' style='display:none;'></canvas></div>";	



			echo "<h2>Top 10 productos por vendedor:<h2>";
			echo "<h4>Selecciona un vendedor:</h4> <select id='vendedores_prod'>";

			$result = mssql_query($queryx);
			$num_rows = mssql_num_rows($result);
			if( $num_rows == 0 ){
				echo "<option>No hay vendedores</option>";
			}else{
				while($row = mssql_fetch_array($result)){
					echo "<option value='".$row["SlpCode"]."'>";
					echo $row["SlpName"];
					echo "</option>";
				}
			}
			echo "</select>";
			echo "<h3 style='text-align:center;' id='status-prod-vend' hidden='hidden'>Este vendedor no tiene productos</h3>";
			echo "<div id='topprods_container'><canvas id='topproductos_manager' width='500' height='400' style='display:none;'></canvas></div><br/>";


			echo "<h2>Hit Rate general: </h2>";
			echo "<div id='hitrate_gral' style='padding-left:50px;'><div id='val_gral' style='border: 2px solid black; margin:auto; display:inline-block;padding:10px;font-size:30px;'></div><div id='data_gral' style='margin:auto; display:inline-block;padding:10px;font-size:20px;'></div></div>";

			echo "<h2>Hit Rate (Ventas/Cotizaciones) por vendedor: </h2>";
			echo "Selecciona un vendedor: <select id='vendedores_hitrate'>";
					//Muestra de vendedores

			$result = mssql_query($queryx);
			$num_rows = mssql_num_rows($result);
			if( $num_rows == 0 ){
				echo "<option>No hay vendedores</option>";
			}else{
				while($row = mssql_fetch_array($result)){
					echo "<option value='".$row["SlpCode"]."'>";
					echo $row["SlpName"];
					echo "</option>";
				}
			}
			echo "</select><br/>";
			echo "<div id='hitrate_manager' style='padding-left:50px;'><div id='val' style='border: 2px solid black; margin:auto; display:inline-block;padding:10px;font-size:30px;'></div><div id='data' style='margin:auto; display:inline-block;padding:10px;font-size:20px;'></div></div>";
			echo "<h3 style='text-align:center;' id='no_sales_hr' hidden='hidden'></h3>";




		}else if($_SESSION["admin"] == NULL){ //Vendedor
			echo "<h2>Cotizaciones arriba de $100'000 (MXN): </h2>";
			echo "<canvas id='sales' width='500' height='400' style='display:none;'></canvas>";
			echo "<h2>Top 10 productos: </h2>";
			echo "<h4 id='no-prods' style='text-align:center;' hidden='hidden'>No hay ning�n producto</h4>";
			echo "<div id='topprods-container'><canvas id='topproductos' width='500' height='400' style='display:none;'></canvas></div><br/>";
			echo "<h2>Hit Rate (Ventas/Cotizaciones): </h2>";
			echo "<div id='hitrate' style='padding-left:50px;'><div id='val' style='border: 2px solid black; margin:auto; display:inline-block;padding:10px;font-size:30px;'></div><div id='data' style='margin:auto; display:inline-block;padding:10px;font-size:20px;'></div></div>";
		}
	//echo '</div>';
		?>
    </div>
    <div id="rightCol">
    	

        <div class="module" id="countdown">
        	<div class="cTop"><?php if($_SESSION["isBP"]) {echo date('F');} else {echo "Faltan";}?></div>
            <div class="cDays"><?php if($_SESSION["isBP"]) {echo date('j');} else {echo date('t') - date('j');} ?></div>
            <div class="cFoot"><?php if($_SESSION["isBP"]) {echo date('l');} else {echo "d&iacute;as<br>para cierre de mes";} ?></div>
        </div>
        <div>
        	<?php 
				if ($_SESSION["admin"] == 'Y' || $_SESSION["admin"] == 'W') {
        	?>
        	<button type="button" class="alianza-btn" onclick="triggerQuotationsPopup()">Cotizaciones por cerrar</button>
        	<?php 
        		}
        	?>
        </div>

        <?php include 'includes/getBlog.php';?>
    </div>
</div>
   
<script>

/*QUOTATION NOTIFIER*/
function holdQuotation(el){
	var e = $(el);
	var num = e.parents(".quotation-row").find("input.num_q").val();
	var comments = e.parents(".quotation-row").find("input.comm").val();
	//var hold_date = e.parents(".quotation-row").find("input.hold-date").val();
	var hold_date = e.parents(".quotation-row").find("input.aaaa-date").val() + '-' +
					e.parents(".quotation-row").find("input.mm-date").val() + '-' +
					e.parents(".quotation-row").find("input.dd-date").val();
	$.ajax({
		method: "GET",
		url: "includes/ajaxHoldQuotation.php",
		data: {
			'num_cot': num,
			'comments': comments,
			'hold_date': hold_date
		},
		success: function(response){
			if(response.status === true){
				e.parents(".quotation-row").find(".close-btn").text("Aplazada");
				e.parents(".quotation-row").find(".close-btn").attr("disabled", "disabled");
				e.parents(".quotation-row").find(".hold-btn").text("Aplazada");
				e.parents(".quotation-row").find(".hold-btn").attr("disabled", "disabled");
			}else{
				console.log(response);
			}
		}
	});
}
function executeAction(e){
	let action_code = $(e).parents("tr").find("select").val();
	if(action_code == "1"){
		let idCot =  $(e).parents("tr").find(".id_cot").text();
		let comments = $(e).parents("tr").find(".comment").val();
		let hold_date = $(e).parents("tr").find(".datepicker").val();
		$.ajax({
			method: "GET",
			url: "includes/ajaxHoldQuotation.php",
			data: {
				'num_cot': idCot,
				'comments': comments,
				'hold_date': hold_date
			},
			success: function(response){
				if(response.status === true){
					$(e).parents("tr").find("button").text("Aplazada");
					$(e).parents("tr").find("button").attr("disabled", "disabled");
				}else{
					console.log(response);
				}
			}
		});
	}else{
		let idCot =  $(e).parents("tr").find(".id_cot").text();
		let comments = $(e).parents("tr").find(".comment").val();
		let status = $(e).parents("tr").find(".lost-select select").val();

		$.ajax({
			method: "GET",
			url: "includes/ajaxCloseQuotation.php",
			data: {
				'num_cot': idCot,
				'comments': comments,
				'status': status
			},
			success: function(response){
				if(response.status === true){
					$(e).parents("tr").find("button").text("Cerrada");
					$(e).parents("tr").find("button").attr("disabled", "disabled");
				}else{
					console.log(response);
				}
			}
		});
	}
}
function closeQuotation(el){
	var e = $(el);
	var num = e.parents(".quotation-row").find("input.num_q").val();
	var comments = e.parents(".quotation-row").find("input.comm").val();
	var status = e.parents(".quotation-row").find(".lost-select select").val();

	$.ajax({
		method: "GET",
		url: "includes/ajaxCloseQuotation.php",
		data: {
			'num_cot': num,
			'comments': comments,
			'status': status
		},
		success: function(response){
			if(response.status === true){
				e.parents(".quotation-row").find(".close-btn").text("Cerrada");
				e.parents(".quotation-row").find(".close-btn").attr("disabled", "disabled");
				e.parents(".quotation-row").find(".hold-btn").text("Cerrada");
				e.parents(".quotation-row").find(".hold-btn").attr("disabled", "disabled");
			}else{
				console.log(response);
			}
		}
	});
}
function closeNotifier(){
	$(".quotation-notifier").hide();
	$(".quotation-notifier-container").hide();
}
function getQuotationsForNotifier(){
	let rol = "" + <?php echo "'".$_SESSION["admin"]."'"; ?>;
	console.log(rol);
	//
	if(rol != "Y" && rol != "W"){
		$.ajax({
			method: "GET",
			url: "includes/ajaxNotifyCloseQuot.php",
			data: {
				"rol": rol
			},
			success: function(response){
				$(".quotation-container").empty();
				let table_html = "<table class='close-q-table'>" +
									"<thead>" +
										"<tr>" +
											"<th>Cotizacion</th>" +
											"<th>Socio</th>" +
											"<th>Monto</th>" +
											"<th>Comentarios</th>" +
											"<th>Status actual</th>" +
											"<th>Actualizar status</th>" +
											"<th>Cerrar como</th>" +
											"<th>Aplazar cierre a</th>" +
											"<th>Actualizar</th>" +
										"</tr>" +
									"</thead>" +
									"<tbody>";
				response.forEach(function(e){
					//Q ROW
					table_html += ("<tr>" + 
								"<td><div># <span class='id_cot'>" + e.Id_Cot1 + "</span></div></td>" +
								"<td class='no-wrap'><div>" + e.CardName + "</div></td>" +
								"<td><div>$" + e.Total_Doc + "</div></td>" +
								"<td><div><input style='width: 140px;' type='text' class='comment' placeholder='Se cierra/aplaza por que...' value='" + (e.ComentarioCierre == null ? '' : e.ComentarioCierre) + "'/></div></td>" +
								"<td><div>" + getStatus(e.status) + "</div></td>" +
								"<td><div class='custom-select' style='width:150px;'><select name='action' onchange='holdChanger(this)'><option value='1' selected>Aplazar Cierre</option><option value='2'>Cerrar</option></select></div></td>" +
								"<td><div class='custom-select lost-select' style='width:150px;'><select value='3' disabled name='' onchange='lostChanger(this)'><option value='3' selected>Ganada</option><option value='4'>Perdida</option></select></div></td>" +
								"<td><div class='no-padding-left no-padding-right'><input type='text' class='datepicker' placeholder='dd/mm/aaaa' onchange='dateChanger(this)'/></div></td>" +
								"<td><div class='update-q-btn'><button disabled onclick='executeAction(this)'>Actualizar</button></div></td>" +
							"</tr>");
				});

				table_html += ("</tbody>" +
								"</table>");
				$(".quotation-container").append(table_html);
				$(".quotation-notifier").show();
				$(".quotation-notifier").css("display", "inline-block");
				$(".quotation-notifier-container").show();
				$(".datepicker").datepicker();
			}
		});
	}
}
function triggerQuotationsPopup(){

	$.ajax({
		method: "GET",
		url: "includes/ajaxNotifyCloseQuot.php",
		data: {
			"rol": "Y"
		},
		success: function(response){
				$(".quotation-container").empty();
				let table_html = "<table class='close-q-table'>" +
									"<thead>" +
										"<tr>" +
											"<th>Cotizacion</th>" +
											"<th>Socio</th>" +
											"<th>Monto</th>" +
											"<th>Comentarios</th>" +
											"<th>Status actual</th>" +
											"<th>Actualizar status</th>" +
											"<th>Cerrar como</th>" +
											"<th>Aplazar cierre a</th>" +
											"<th>Actualizar</th>" +
										"</tr>" +
									"</thead>" +
									"<tbody>";
				response.forEach(function(e){
					//Q ROW
					table_html += ("<tr>" + 
								"<td><div># <span class='id_cot'>" + e.Id_Cot1 + "</span></div></td>" +
								"<td class='no-wrap'><div>" + e.CardName + "</div></td>" +
								"<td><div>$" + e.Total_Doc + "</div></td>" +
								"<td><div><input style='width: 140px;' type='text' class='comment' placeholder='Se cierra/aplaza por que...' value='" + (e.ComentarioCierre == null ? '' : e.ComentarioCierre) + "'/></div></td>" +
								"<td><div>" + getStatus(e.status) + "</div></td>" +
								"<td><div class='custom-select' style='width:150px;'><select name='action' onchange='holdChanger(this)'><option value='1' selected>Aplazar Cierre</option><option value='2'>Cerrar</option></select></div></td>" +
								"<td><div class='custom-select lost-select' style='width:150px;'><select disabled name='' onchange='lostChanger(this)'><option value='3' selected>Ganada</option><option value='4'>Perdida</option></select></div></td>" +
								"<td><div class='no-padding-left no-padding-right'><input type='text' class='datepicker' placeholder='dd/mm/aaaa' onchange='dateChanger(this)'/></div></td>" +
								"<td><div class='update-q-btn'><button disabled onclick='executeAction(this)'>Actualizar</button></div></td>" +
							"</tr>");
				});

				table_html += ("</tbody>" +
								"</table>");
				$(".quotation-container").append(table_html);
				$(".quotation-notifier").show();
				$(".quotation-notifier").css("display", "inline-block");
				$(".quotation-notifier-container").show();
				$(".datepicker").datepicker();
			}
	});
}
function getStatus(st){
	console.log(st);
	switch(st){
		case '1':
			return "Cotizada";
			break;
		case '2':
			return "Negociando";
			break;
		case '3':
			return "Ganada";
			break;
		case '4':
			return "Perdida";
			break;
	}
}

function dateChanger(e){
	let value =  $(e).val();
	if(value != ""){
		$(e).parents("tr").find("button").removeAttr("disabled");
	}else{
		$(e).parents("tr").find("button").attr("disabled", "disabled");
	}
}
function holdChanger(e){
	let value =  $(e).val();
	if(value == 2){
		$(e).parents("tr").find(".lost-select select").removeAttr("disabled");
		$(e).parents("tr").find("button").removeAttr("disabled");
	}else{
		$(e).parents("tr").find("button").attr("disabled", "disabled");
		$(e).parents("tr").find(".lost-select select").attr("disabled", "disabled");
	}
}
function lostChanger(e){
	/*let value =  $(e).val();
	if(value == 2){
		$(e).parents("tr").find(".lost-select").removeAttr("disabled");
	}else{
		$(e).parents("tr").find(".lost-select").attr("disabled", "disabled");
	}*/
}
$(document).ready(function() {
	getQuotationsForNotifier();


	$(".itemCode").autocomplete({
		minLength: 3,
		source: "includes/searchProd.php?by=code",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(".itemCode").val(ui.item.value);
			} else {
				$(".itemCode").val(ui.item.value);
			}
			getProdDetails();
		},
		close: function() {
			getProdDetails();
		}
	});


	$(".itemCode").focusout(function(){
		if ($(this).val().length >= 3) {
			getProdDetails();
		}
	});
/*
	var input = $(this);
	var start = input[0].selectionStart;
	$(this).val(function (_, val) {
		return val.toUpperCase();
	});
	input[0].selectionStart = input[0].selectionEnd = start;
	if ($(".itemCode").val().length >= 3) {
		getProdDetails();
	}
*/

});

//////////////////////================================= Get product details =================================//////////////////////
/*var getProdDetails = function() {
	var code = $(".itemCode").val();
	if (code.length >= 3) {
		var lPrice = 0;
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?code="+encodeURI(code),
			dataType: "json",
			cache: false,                
			success: function(prodDetail){
				$(".itemName").val(prodDetail["ItemName"]);
				lPrice = parseFloat(prodDetail["Price"]);
				$(".listPrice").html(lPrice);
				$(".currency").html(prodDetail["Currency"]);
				$(".salePrice").html(localeString(lPrice - (lPrice * $(".discount").val() / 100)));
				$(".onHand").html(prodDetail["OnHand"]);
				$(".onOrder").html(prodDetail["OnOrder"]);
				
			}
		});
	}
};*/
	
	
var getProdDetails = function() {
	var code = $(".itemCode").val();
	if (code.length >= 3) {
		var lPrice = 0;
		
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?code="+encodeURI(code),
			dataType: "json",
			cache: false,                
			success: function(prodDetail){
				$(".itemName").val(prodDetail["ItemName"]);
				lPrice = parseFloat(prodDetail["Price"]);
				$(".listPrice").html(lPrice);
				$(".currency").html(prodDetail["Currency"]);
				$(".salePrice").html(localeString(lPrice - (lPrice * $(".discount").val() / 100)));
				$(".onHand").html(prodDetail["OnHand"]);
				$(".onOrder").html(prodDetail["OnOrder"]);
				//agregado para llenar stock por almacen---
				
				var totOnHand = 0;
				var totOnOrder = 0;
				<?php
				foreach($whCodes as $whCode) {
					echo "$('.oH".$whCode."').html(parseInt(prodDetail['e".$whCode."']));";
					echo "totOnHand += parseInt(prodDetail['e".$whCode."']);";
					echo "$('.oO".$whCode."').find('span').html(parseInt(prodDetail['p".$whCode."']));";
					echo "$('.oO".$whCode."').find('.divOOSD').html(prodDetail['qo".$whCode."']);";
					echo "totOnOrder += parseInt(prodDetail['p".$whCode."']);";
				}
				?>
				$(".oHTotal").html(totOnHand);
				$(".ordTotal").html(totOnOrder);
				
				validateSAP();
				//Termina  agregado para llenar stock por almacen---
			}
		});
	}
};	

	
var getProdCode = function() {
	var name = $(".itemName").val();
	if (name.length >= 3) {
		$.ajax({
			type: "GET",
			url: "includes/prodCode.php?name="+encodeURI(name),
			dataType: "json",
			cache: false,
			success: function(prodCode){
				if (prodCode["ItemCode"] != null && prodCode["ItemCode"] != "") {
					$(".itemCode").val(prodCode["ItemCode"]);
					getProdDetails();
				}
			}
		});
	}
};


/**
*
*	GR�FICAS DE LAS COTIZACIONES MAYORES A 100'000 MXN
*/

if( document.getElementById('general') != undefined ){
	//console.log("DEPLOY GENERAL");
	var url = "includes/ajaxCotizaciones.php";
	$.ajax({
		method: "POST",
		url : url,
		success : function( response ){
			var json = JSON.parse( response );
			if( json.length == 0 ){
				$("#general").hide();
			}else{
				$("#general").show();
				cardNames = new Array();
				totals = new Array();
				var table = $("#table-manager-coti");
				var tbody = $("#table-manager-coti tbody");
				tbody.empty();
				for( var i = 0 ; i < json.length ; i++ ){
					cardNames.push( shortify(json[ i ].CardName) );
					totals.push( Number(json[ i ].Total_MN) );
					var tr = $("<tr></tr>");
					var td_id = $("<td>" + json[ i ].id + "</td>");
					var td_nom = $("<td>" + json[ i ].CardName + "</td>");
					var td_st = $("<td></td>");
					var status;
					switch( json[ i ].st ){
						case "1":
							status = "Cotizada";
							break;
						case "2":
							status = "Negociando";
							break;
						case "3":
							status = "Ganada";
							break;
						case "4":
							status = "Perdida";
							break;
					};
					td_st.text( status );
					var td_link = $("<td><a style='color:blue;' href='vercotizacion.php?idCot=" + json[ i ].id + "'>Ver</a></td>");
					tr.append( td_id );
					tr.append( td_nom );
					tr.append( td_st );
					tr.append( td_link );
					tbody.append( tr );
					table.show();
				}
				var data = {
					labels: cardNames,
						datasets: [
						    {
						        label: "Sodium intake",
						        fillColor: "rgba(0, 0, 255, 0.5)",
						        strokeColor: "rgba(220,220,220,1)",
						        pointColor: "rgba(220,220,220,1)",
						        pointStrokeColor: "#fff",
						        pointHighlightFill: "#fff",
						        pointHighlightStroke: "rgba(220,220,220,1)",
						        data: totals
						    }
						]};
				var ctx = document.getElementById("general").getContext('2d');
				
				var myChart = new Chart(ctx).Bar(data, {
					scaleLabel : 
					    function(label){return  '$' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}

				});
			}
		}
	});
}
if( document.getElementById('hitrate_gral') != undefined ){
	var url = "includes/ajaxHitRateGral.php";
	$.ajax({
		method: "POST",
		url : url,
		success : function( response ){
			var json = JSON.parse( response );
			//console.log( json );
			ventas = json[ 0 ].Ventas;
			cotizaciones = json[ 1 ].Cotizaciones;
			//console.log( Number(ventas));
			//console.log( Number(cotizaciones));
			var hr = (ventas / cotizaciones);
			hr = hr.toString();
			hr = hr.slice( 0, (hr.indexOf(".")) + 3 );
			hr = Number( hr );
			$("#val_gral").text( hr );
			if( hr >= 1.0 ){
				$("#val_gral").css({
					"background" : "rgba(0,255,0,0.4)"
				});
			}else{
				$("#val_gral").css({
					"background" : "rgba(255,0,0,0.5)"
				});				
			}
			$("#data_gral").text("(" + ventas.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "/" + cotizaciones.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ")" );
		}
	});

}
if( document.getElementById('hitrate') != undefined ){
	var url = "includes/ajaxHitRate.php";
	$.ajax({
		method: "POST",
		url : url,
		success : function( response ){
			var json = JSON.parse( response );
			//console.log( json );
			ventas = json[ 0 ].Ventas;
			cotizaciones = json[ 1 ].Cotizaciones;
			//console.log( Number(ventas));
			//console.log( Number(cotizaciones));
			ventas = 10000;
			var hr = (ventas / cotizaciones);
			hr = hr.toString();
			hr = hr.slice( 0, (hr.indexOf(".")) + 3 );
			hr = Number( hr );
			$("#val").text( hr );
			if( hr >= 1.0 ){
				$("#val").css({
					"background" : "rgba(0,255,0,0.4)"
				});
			}else{
				$("#val").css({
					"background" : "rgba(255,0,0,0.5)"
				});				
			}
			$("#data").text("(" + ventas.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "/" + cotizaciones.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ")" );
		}
	});

}
if( document.getElementById('sales') != undefined ){
	//console.log("DEPLOY VENDEDOR");
	var url = "includes/ajaxCotizacionesVendedor.php";
	$.ajax({
		method: "POST",
		url : url,
		success : function( response ){
			var json = JSON.parse( response );
			cardNames = new Array();
			totals = new Array();
			for( var i = 0 ; i < json.length ; i++ ){
				cardNames.push( shortify(json[ i ].CardName) );
				totals.push( Number(json[ i ].Total_MN) );
			}
			var data = {
				labels: cardNames,
					datasets: [
					    {
					        label: "Sodium intake",
					        fillColor: "rgba(0, 0, 255, 0.5)",
					        strokeColor: "rgba(220,220,220,1)",
					        pointColor: "rgba(220,220,220,1)",
					        pointStrokeColor: "#fff",
					        pointHighlightFill: "#fff",
					        pointHighlightStroke: "rgba(220,220,220,1)",
					        data: totals
					    }
					]};
			$("#sales").show();
			var ctx = document.getElementById("sales").getContext('2d');
			var myChart = new Chart(ctx).Bar(data);
		}
	});
}

$("#socios").on("change", function(){
	var socio = $("#socios option:selected").val();
	$("#edocuenta").hide();
	$.ajax({
		url : "includes/ajaxEdoCuenta.php",
		method : "POST",
		data : {
			"bPartner" : socio
		},
		success : function( response ){
			$("#edocuenta").show();
			$("#edocuenta").html( response );
			$(".d90").remove();
			$(".h90").remove();
			$(".d120").remove();
			$(".h120").remove();
			$(".d121").remove();
			$(".h121").remove();
		}
	});
});
if( document.getElementById('sales_manager') != undefined ){
	//console.log("SALES MANAGER");
	var SELECT = $("#vendedores");
	var SELECT_PRODS = $("#vendedores_prod");
	var SELECT_HR = $("#vendedores_hitrate");
	$.ajax({
		url : "includes/ajaxVendedores.php",
		method : "POST",
		data : {
			"type" : "all"
		},
		success : function( response ){
			var data = JSON.parse( response );
			if( data.length == 0){
				var OPTION = $("<option>No hay vendedores a cargo</option>");
				SELECT.append(OPTION);
				SELECT_PRODS.append(OPTION.clone());
				SELECT_HR.append(OPTION.clone());
			}else{
				for( var i = 0 ; i < data.length ; i++ ){
					var OPTION = $("<option></option>");
					OPTION.val(data[ i ].SlpCode);
					OPTION.text(data[ i ].SlpName);
					SELECT.append(OPTION);
					SELECT_PRODS.append(OPTION.clone());
					SELECT_HR.append(OPTION.clone());
				}
			}
		}
	});
	$("#vendedores").on("change", function(){
		var slp = $("#vendedores option:selected").val();
		$.ajax({
			method: "POST",
			data : { "id" : slp },
			url : "includes/ajaxCotizacionesVendedor.php",
			success : function( response ){
				var json = JSON.parse( response );
				if( json.length == 0 ){
					$("#status-coti").show();
					$("#sales_manager").remove();
					var div = $("#sales_container");
					var canvas = $("<canvas id='sales_manager' width='500' height='400'></canvas>");
					div.append( canvas );
				}else{
					$("#sales_manager").remove();
					var div = $("#sales_container");
					var canvas = $("<canvas id='sales_manager' width='500' height='400'></canvas>");
					div.append( canvas );
					$("#status-coti").hide();
					cardNames = new Array();
					totals = new Array();

					var table = $("#table-manager-coti-ven");
					var tbody = $("#table-manager-coti-ven tbody");
					table.hide();
					tbody.empty();
					for( var i = 0 ; i < json.length ; i++ ){
						cardNames.push( shortify(json[ i ].CardName) );
						totals.push( Number(json[ i ].Total_MN) );
						var tr = $("<tr></tr>");
						var td_id = $("<td>" + json[ i ].id + "</td>");
						var td_nom = $("<td>" + json[ i ].CardName + "</td>");
						var td_st = $("<td></td>");
						var status;
						switch( json[ i ].st ){
							case "1":
								status = "Cotizada";
								break;
							case "2":
								status = "Negociando";
								break;
							case "3":
								status = "Ganada";
								break;
							case "4":
								status = "Perdida";
								break;
						};
						td_st.text( status );
						var td_link = $("<td><a style='color:blue;' href='vercotizacion.php?idCot=" + json[ i ].id + "'>Ver</a></td>");
						tr.append( td_id );
						tr.append( td_nom );
						tr.append( td_st );
						tr.append( td_link );
						tbody.append( tr );
						table.show();
					}
					var data = {
						labels: cardNames,
							datasets: [
							    {
							        label: "Sodium intake",
							        fillColor: "rgba(0, 255, 0, 0.5)",
							        strokeColor: "rgba(220,220,220,1)",
							        pointColor: "rgba(220,220,220,1)",
							        pointStrokeColor: "#fff",
							        pointHighlightFill: "#fff",
							        pointHighlightStroke: "rgba(220,220,220,1)",
							        data: totals
							    }
							]};
					var ctx = document.getElementById("sales_manager").getContext('2d');
					
					var myChart = new Chart(ctx).Bar(data, {
						onClick : redirectCoti,
						scaleLabel : 
						    function(label){return  '$' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}

					});
				}
			}
		});
		
	});
	$("#vendedores_prod").on("change", function(){
		var slp = $("#vendedores_prod option:selected").val();
		$.ajax({
			method: "POST",
			data : { "id" : slp },
			url : "includes/ajaxTopProductosVendidos.php",
			success : function( response ){
				var json = JSON.parse( response );
				if( json.length == 0 ){
					$("#status-prod-vend").show();
					$("#topproductos_manager").remove();
					var div = $("#topprods_container");
					var canvas = $("<canvas id='topproductos_manager' width='500' height='400'></canvas>");
					div.append( canvas );
				}else{
					$("#topproductos_manager").remove();
					var div = $("#topprods_container");
					var canvas = $("<canvas id='topproductos_manager' width='500' height='400'></canvas>");
					div.append( canvas );
					$("#status-prod-vend").hide();
					nombres = new Array();
					cantidades = new Array();
					for( var i = 0 ; i < json.length ; i++ ){
						nombres.push( shortify(json[ i ].Nombre_Art) );
						cantidades.push( Number(json[ i ].Cantidad) );
					}
					var data = {
						labels: nombres,
							datasets: [
							    {
							        label: "Sodium intake",
							        fillColor: "rgba(255, 255, 0, 0.5)",
							        strokeColor: "rgba(220,220,220,1)",
							        pointColor: "rgba(220,220,220,1)",
							        pointStrokeColor: "#fff",
							        pointHighlightFill: "#fff",
							        pointHighlightStroke: "rgba(220,220,220,1)",
							        data: cantidades
							    }
							]};
					var ctx = document.getElementById("topproductos_manager").getContext('2d');
					var myChart = new Chart(ctx).Bar(data, {
						scaleLabel : 
						    function(label){return  label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}

					});
				}
			}
		});
		
	});
	$("#vendedores_hitrate").on("change", function(){
		//console.log("HITRATE POR VENDEDOR");
		var slp = $("#vendedores_hitrate option:selected").val();
		$.ajax({
			method: "POST",
			data : { "id" : slp },
			url : "includes/ajaxHitRate.php",
			success : function( response ){
				var json = JSON.parse( response );
				if( json.length != 0 ){
					$("#no_sales_hr").hide();
					ventas = json[ 0 ].Ventas;
					cotizaciones = json[ 1 ].Cotizaciones;
					//console.log( ventas);
					//console.log( cotizaciones);
					var hr = (ventas / cotizaciones).toFixed( 2 );
					if( cotizaciones == 0 ){
						$("#hitrate_manager").hide();
						$("#no_sales_hr").text("Este vendedor no tiene cotizaciones");
						$("#no_sales_hr").show();
					}else{
						//console.log( hr );
						$("#val").text( hr );
						if( hr >= 1.0 ){
							$("#val").css({
								"background" : "rgba(0,255,0,0.4)"
							});
						}else{
							$("#val").css({
								"background" : "rgba(255,0,0,0.5)"
							});				
						}
						$("#data").text("(" + ventas.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "/" + cotizaciones.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ")" );
						$("#hitrate_manager").show();
					}
				}else{
					$("#hitrate_manager").hide();
					$("#no_sales_hr").text("Este vendedor no tiene cotizaciones");
					$("#no_sales_hr").show();
				}
			}
		});
		
	});
}
if( document.getElementById('topproductos') != undefined ){
	$.ajax({
		method: "POST", 
		url : "includes/ajaxTopProductosVendidos.php",
		success : function( response ){
			var json = JSON.parse( response );
			if( json.length == 0 ){
				$("#no-prods").show();
				$("#topproductos").remove();
				var div = $("#topprods-container");
				var canvas = $("<canvas id='topproductos' width='500' height='400'></canvas>");
				div.append( canvas );
			}else{
				$("#no-prods").hide();
				$("#topproductos").remove();
				var div = $("#topprods-container");
				var canvas = $("<canvas id='topproductos' width='500' height='400'></canvas>");
				div.append( canvas );
				nombres = new Array();
				cantidades = new Array();
				for( var i = 0 ; i < json.length ; i++ ){
					nombres.push( shortify(json[ i ].Nombre_Art) );
					cantidades.push( Number(json[ i ].Cantidad) );
				}
				var data = {
					labels: nombres,
						datasets: [
						    {
						        label: "Sodium intake",
						        fillColor: "rgba(255, 0, 0, 0.5)",
						        strokeColor: "rgba(220,220,220,1)",
						        pointColor: "rgba(220,220,220,1)",
						        pointStrokeColor: "#fff",
						        pointHighlightFill: "#fff",
						        pointHighlightStroke: "rgba(220,220,220,1)",
						        data: cantidades
						    }
						]};
				var ctx = document.getElementById("topproductos").getContext('2d');
				var myChart = new Chart(ctx).Bar(data);
			}
		}
	});
}
if( document.getElementById('topvendedores') != undefined ){
	$.ajax({
		method: "POST",
		url : "includes/ajaxTopVendedores.php",
		success : function( response ){
			var json = JSON.parse( response );
			if( json.length == 0 ){
				$("#no-salers").show();
				$("#topvendedores").hide();
			}else{
				$("#no-salers").hide();
				$("#topvendedores").show();
				cardNames = new Array();
				cotizaciones = new Array();
				for( var i = 0 ; i < json.length ; i++ ){
					cardNames.push( shortify(json[ i ].CardName) );
					cotizaciones.push( Number(json[ i ].Cantidad) );
				}
				var data = {
					labels: cardNames,
						datasets: [
						    {
						        label: "Sodium intake",
						        fillColor: "rgba(255, 0, 0, 0.5)",
						        strokeColor: "rgba(220,220,220,1)",
						        pointColor: "rgba(220,220,220,1)",
						        pointStrokeColor: "#fff",
						        pointHighlightFill: "#fff",
						        pointHighlightStroke: "rgba(220,220,220,1)",
						        data: cotizaciones
						    }
						]};
				var ctx = document.getElementById("topvendedores").getContext('2d');
				
				var myChart = new Chart(ctx).Bar(data, {
					scaleLabel : 
					    function(label){return  label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}

				});
			}
		}
	});
}
if( document.getElementById('topquotbranch') != undefined ){
	$.ajax({
		method: "POST",
		url : "includes/ajaxTopQuotBranch.php",
		success : function( response ){
			var json = response;
			if( json.length == 0 ){
				$("#topquotbranch").hide();
			}else{
				$("#topquotbranch").show();
				cardNames = new Array();
				total = new Array();
				for( var i = 0 ; i < json.length ; i++ ){
					cardNames.push( shortify(json[ i ].CardName) );
					total.push( Number(json[ i ].Total_MN) );
				}
				var data = {
					labels: cardNames,
						datasets: [
						    {
						        label: "Sodium intake",
						        fillColor: "rgba(255, 0, 0, 0.5)",
						        strokeColor: "rgba(220,220,220,1)",
						        pointColor: "rgba(220,220,220,1)",
						        pointStrokeColor: "#fff",
						        pointHighlightFill: "#fff",
						        pointHighlightStroke: "rgba(220,220,220,1)",
						        data: total
						    }
						]};
				var ctx = document.getElementById("topquotbranch").getContext('2d');
				
				var myChart = new Chart(ctx).Bar(data, {
					scaleLabel : 
					    function(label){return  label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}

				});
			}
		}
	});
}
function redirectCoti(){
	alert("REDIRECT");
}
if( document.getElementById('topclientes') != undefined ){
	//console.log("DEPLOY CLIENTES");
	var url = "includes/ajaxTopClientes.php";
	$.ajax({
		method: "POST",
		url : url,
		success : function( response ){
			var json = JSON.parse( response );
			cardNames = new Array();
			totals = new Array();
			for( var i = 0 ; i < json.length ; i++ ){
				cardNames.push( shortify(json[ i ].CardName) );
				totals.push( Number(json[ i ].Cantidad) );
			}
			var data = {
				labels: cardNames,
					datasets: [
					    {
					        label: "Sodium intake",
					        fillColor: "rgba(255, 0, 0, 0.5)",
					        strokeColor: "rgba(220,220,220,1)",
					        pointColor: "rgba(220,220,220,1)",
					        pointStrokeColor: "#fff",
					        pointHighlightFill: "#fff",
					        pointHighlightStroke: "rgba(220,220,220,1)",
					        data: totals
					    }
					]};
			var ctx = document.getElementById("topclientes").getContext('2d');
			var myChart = new Chart(ctx).Bar(data);
		}
	});
}

function addCommas(strArray){
	for( var i = 0 ; i < strArray.length ; i++ ){
		var nStr = strArray[ i ];
	    nStr += '';
	    x = nStr.split('.');
	    x1 = x[0];
	    x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    strArray[ i ] = x1 + x2;
	    //console.log( strArray[ i ]);
	}
	return strArray;
}

function shortify( string ){
	return string.substr(0, 15) + "...";
}

// Set discount
$(".discount").change(function() {
	var lPrice = $(".listPrice").html();
	$(".salePrice").html(localeString(lPrice - (lPrice * $(".discount").val() / 100)));
})
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php include 'footer.php'; ?>