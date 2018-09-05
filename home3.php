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
?>

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
			name: 'Comisión Estimada',
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
							$querySlide = "SELECT title, link, file FROM SLDR WHERE company = '".$_SESSION["company"]."' AND active = 'Y' AND for_customer = 'Y' ORDER BY id DESC";
						} else {
							$querySlide = "SELECT title, link, file FROM SLDR WHERE company = '".$_SESSION["company"]."' AND active = 'Y' AND for_customer <> 'Y' ORDER BY id DESC";
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
			
			<!--Prueba existencias -->
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
		
		<!-- Termina Prueba existencias -->
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
			<tr>
				<td>Existencia <span class="onHand">-</span>
				</td>
				<td>Por recibir <span class="onOrder">-</span>
				</td>
			</tr>
			<!--pruebas-->
			 <div class='stockDiv'>\
                <div class='stockLeft'>\
                	<table width='100%' border='0' cellspacing='0' cellpadding='0'>\
                      <tbody>\
                        <tr>\
                          <td colspan='<?php echo count($whCodes); ?>' class='qSubSec' style='color:#3e631a'>Existencia por almac&eacute;n (Total <div class='oHTotal'>-</div>)</td>\
                        </tr>\
                        <tr style='color:#3e631a'>\
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td>".$whNames[$i]."</td>";
							}
							?>
                        </tr>\
                        <tr style='color:#3e631a'>\
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td><div class='oH".$whCode."'>-</div></td>";
							}
							?>
                        </tr>\
                      </tbody>\
                    </table>\
                </div>\
                <div class='stockRight'>\
                	<table width='100%' border='0' cellspacing='0' cellpadding='0'>\
                      <tbody>\
                        <tr>\
                          <td colspan='<?php echo count($whCodes); ?>' class='qSubSec' style='color:#a50000'>Pendiente por recibir en almac&eacute;n</td>\
                        </tr>\
                        <tr style='color:#a50000'>\
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td>".$whNames[$i]."</td>";
							}
							?>
                        </tr>\
                        <tr style='color:#a50000'>\
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td><div class='oO".$whCode." oTot'><div class='divOOSD'>\
								</div><span>-</span></div></td>";
							}
							?>
                        </tr>\
                      </tbody>\
                    </table>\
                </div>\
             </div>\
			<!--  Terminan pruebas  -->
		</table>
		</div>
    </div><!-- End leftCol -->
    <div id="midCol">
    	<?php
		if ($_SESSION["admin"] != "BP" && $_SESSION["admin"] != "W") {
			echo "<div class='module homeGraph' id='forecastGraph'></div>";
		}
		?>
    	<div class="module" id="webApps">
        	Links
            <div class="webAppsWrapper">
            <!--script src="//code.jquery.com/jquery-1.10.2.js"></script-->
  			<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
            	<script>
					$(function() {
						$( "#sortable" ).sortable();
						$( "#sortable" ).disableSelection();
					});
				</script>
            	<ul id="sortable">
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
            </div>
        </div>
    </div>
    <div id="rightCol">
        <div class="module" id="countdown">
        	<div class="cTop"><?php if($_SESSION["isBP"]) {echo date('F');} else {echo "Faltan";}?></div>
            <div class="cDays"><?php if($_SESSION["isBP"]) {echo date('j');} else {echo date('t') - date('j');} ?></div>
            <div class="cFoot"><?php if($_SESSION["isBP"]) {echo date('l');} else {echo "d&iacute;as<br>para cierre de mes";} ?></div>
        </div>
    </div>
</div>
   
<script>
$(document).on("input", ".itemCode", function() {
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
	var input = $(this);
	var start = input[0].selectionStart;
	$(this).val(function (_, val) {
		return val.toUpperCase();
	});
	input[0].selectionStart = input[0].selectionEnd = start;
	if ($(".itemCode").val().length >= 3) {
		getProdDetails();
	}
});

$(document).on("input", ".itemName", function() {
	$(".itemName").autocomplete({
		minLength: 3,
        source: "includes/searchProd.php?by=name",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(".itemName").val(ui.item.value);
			} else {
				$(".itemName").val(ui.item.value);
			}
			getProdCode();
		},
		close: function() {
			getProdCode();
		}
    });
	if ($(".itemName").val().length >= 3) {
		getProdCode();
	}
});
	
//////////////////////================================= Get product details =================================//////////////////////
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

// Set discount
$(".discount").change(function() {
	var lPrice = $(".listPrice").html();
	$(".salePrice").html(localeString(lPrice - (lPrice * $(".discount").val() / 100)));
})
</script>
    
<?php include 'footer.php'; ?>