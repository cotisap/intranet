<?php
include_once 'head.php';
?>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php
include_once 'header.php';

// Data variables
$xAxis = array('15','16','17','18','19','20','21','22','23','00');
$xAxisText = array('15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00','00:00');


//===== Fiesta Radio

// Total views
$fiTotViews = array();
$fiTotViewsGraph = array();

//===== Evento 40

// Total views
$evTotViews = array();
$evTotViewsGraph = array();

// Splash 1 views
$evS1Views = array();
$evS1ViewsGraph = array();

// Splash 2 views
$evS2Views = array();
$evS2ViewsGraph = array();

// Splash 3 views
$evS3Views = array();
$evS3ViewsGraph = array();

// Splash 4 views
$evS4Views = array();
$evS4ViewsGraph = array();

// Total clicks
$evTotClicks = array();
$evTotClicksGraph = array();

// Splash 1 clicks
$evS1Clicks = array();
$evS1ClicksGraph = array();

// Splash 2 clicks
$evS2Clicks = array();
$evS2ClicksGraph = array();

// Splash 3 clicks
$evS3Clicks = array();
$evS3ClicksGraph = array();

// Splash 4 clicks
$evS4Clicks = array();
$evS4ClicksGraph = array();



// Set values to variables

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM pageload WHERE splashID = 'promoTEC' AND created_at > '2015-11-18 15:00:00' AND created_at < '2015-11-19 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$fiTotViews[$row['time']] = $row['quant'];
}


// Evento vistas
$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM pageload WHERE created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' AND (splashID = 'ev01' OR splashID = 'ev02' OR splashID = 'ev03' OR splashID = 'ev04') GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evTotViews[$row['time']] = $row['quant'];
}

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM pageload WHERE splashID = 'ev01' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evS1Views[$row['time']] = $row['quant'];
}

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM pageload WHERE splashID = 'ev02' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evS2Views[$row['time']] = $row['quant'];
}

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM pageload WHERE splashID = 'ev03' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evS3Views[$row['time']] = $row['quant'];
}

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM pageload WHERE splashID = 'ev04' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evS4Views[$row['time']] = $row['quant'];
}

// Evento clicks
$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM clickcount WHERE created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' AND (splashID = 'ev01' OR splashID = 'ev02' OR splashID = 'ev03' OR splashID = 'ev04') GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evTotClicks[$row['time']] = $row['quant'];
}

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM clickcount WHERE splashID = 'ev01' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evS1Clicks[$row['time']] = $row['quant'];
}

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM clickcount WHERE splashID = 'ev02' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evS2Clicks[$row['time']] = $row['quant'];
}

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM clickcount WHERE splashID = 'ev03' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evS3Clicks[$row['time']] = $row['quant'];
}

$myQuery = mysql_query("SELECT created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(created_at, ':', 1), ' ', -1) as time, COUNT(*) quant FROM clickcount WHERE splashID = 'ev04' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' GROUP BY time");
while($row = mysql_fetch_array($myQuery)) {
	$evS4Clicks[$row['time']] = $row['quant'];
}



// Set to array index values
foreach($xAxis as $time) {
	if(!isset($fiTotViews[$time])) $fiTotViews[$time] = 0;
	if(!isset($evTotViews[$time])) $evTotViews[$time] = 0;
	if(!isset($evS1Views[$time])) $evS1Views[$time] = 0;
	if(!isset($evS2Views[$time])) $evS2Views[$time] = 0;
	if(!isset($evS3Views[$time])) $evS3Views[$time] = 0;
	if(!isset($evS4Views[$time])) $evS4Views[$time] = 0;
	if(!isset($evTotClicks[$time])) $evTotClicks[$time] = 0;
	if(!isset($evS1Clicks[$time])) $evS1Clicks[$time] = 0;
	if(!isset($evS2Clicks[$time])) $evS2Clicks[$time] = 0;
	if(!isset($evS3Clicks[$time])) $evS3Clicks[$time] = 0;
	if(!isset($evS4Clicks[$time])) $evS4Clicks[$time] = 0;
}

for ($i = 0; $i < count($xAxis); $i++) {
	$fiTotViewsGraph[$i] = $fiTotViews[$xAxis[$i]];
	$evTotViewsGraph[$i] = $evTotViews[$xAxis[$i]];
	$evS1ViewsGraph[$i] = $evS1Views[$xAxis[$i]];
	$evS2ViewsGraph[$i] = $evS2Views[$xAxis[$i]];
	$evS3ViewsGraph[$i] = $evS3Views[$xAxis[$i]];
	$evS4ViewsGraph[$i] = $evS4Views[$xAxis[$i]];
	$evTotClicksGraph[$i] = $evTotClicks[$xAxis[$i]];
	$evS1ClicksGraph[$i] = $evS1Clicks[$xAxis[$i]];
	$evS2ClicksGraph[$i] = $evS2Clicks[$xAxis[$i]];
	$evS3ClicksGraph[$i] = $evS3Clicks[$xAxis[$i]];
	$evS4ClicksGraph[$i] = $evS4Clicks[$xAxis[$i]];
}
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="js/hc/highcharts.js"></script>
<script>
// Variables
var js_hours = JSON.parse('<?php echo JSON_encode($xAxisText);?>');
var js_fiTotViews = JSON.parse('<?php echo JSON_encode($fiTotViewsGraph);?>');
var js_evTotViews = JSON.parse('<?php echo JSON_encode($evTotViewsGraph);?>');
var js_evS1Views = JSON.parse('<?php echo JSON_encode($evS1ViewsGraph);?>');
var js_evS2Views = JSON.parse('<?php echo JSON_encode($evS2ViewsGraph);?>');
var js_evS3Views = JSON.parse('<?php echo JSON_encode($evS3ViewsGraph);?>');
var js_evS4Views = JSON.parse('<?php echo JSON_encode($evS4ViewsGraph);?>');
var js_evTotClicks = JSON.parse('<?php echo JSON_encode($evTotClicksGraph);?>');
var js_evS1Clicks = JSON.parse('<?php echo JSON_encode($evS1ClicksGraph);?>');
var js_evS2Clicks = JSON.parse('<?php echo JSON_encode($evS2ClicksGraph);?>');
var js_evS3Clicks = JSON.parse('<?php echo JSON_encode($evS3ClicksGraph);?>');
var js_evS4Clicks = JSON.parse('<?php echo JSON_encode($evS4ClicksGraph);?>');

for (var i=0; i<js_hours.length; i++)
{
    js_fiTotViews[i] = parseInt(js_fiTotViews[i], 10);
	js_evTotViews[i] = parseInt(js_evTotViews[i], 10);
	js_evS1Views[i] = parseInt(js_evS1Views[i], 10);
	js_evS2Views[i] = parseInt(js_evS2Views[i], 10);
	js_evS3Views[i] = parseInt(js_evS3Views[i], 10);
	js_evS4Views[i] = parseInt(js_evS4Views[i], 10);
	js_evTotClicks[i] = parseInt(js_evTotClicks[i], 10);
	js_evS1Clicks[i] = parseInt(js_evS1Clicks[i], 10);
	js_evS2Clicks[i] = parseInt(js_evS2Clicks[i], 10);
	js_evS3Clicks[i] = parseInt(js_evS3Clicks[i], 10);
	js_evS4Clicks[i] = parseInt(js_evS4Clicks[i], 10);
}

$(function () {
	// Fiesta Total Views
    $('#fiestaTotViews').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Promo Tec Milenio Views'
        },
		subtitle: {
			text: 'Vistas del promo por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Visitas'
            }
        },
        series: [{
            name: 'Impactos',
            data: js_fiTotViews,
			color: '#3fae29'
        }]
    });
	
	// Evento total views
	$('#eventoTotViews').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Pages Views'
        },
		subtitle: {
			text: 'Vistas de los Splash Pages por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Visitas'
            }
        },
        series: [{
            name: 'Impactos',
            data: js_evTotViews,
			color: '#001f5b'
        }]
    });
	
	// Evento Splash 1 views
	$('#eventoS1Views').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Page 1 Views'
        },
		subtitle: {
			text: 'Vistas del Splash Page por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Visitas'
            }
        },
        series: [{
            name: 'Impactos',
            data: js_evS1Views,
			color: '#1a5cff'
        }]
    });
	// Evento Splash 2 views
	$('#eventoS2Views').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Page 2 Views'
        },
		subtitle: {
			text: 'Vistas del Splash Page por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Visitas'
            }
        },
        series: [{
            name: 'Impactos',
            data: js_evS2Views,
			color: '#31dd00'
        }]
    });
	// Evento Splash 3 views
	$('#eventoS3Views').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Page 3 Views'
        },
		subtitle: {
			text: 'Vistas del Splash Page por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Visitas'
            }
        },
        series: [{
            name: 'Impactos',
            data: js_evS3Views,
			color: '#ff4e00'
        }]
    });
	// Evento Splash 4 views
	$('#eventoS4Views').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Page 4 Views'
        },
		subtitle: {
			text: 'Vistas del Splash Page por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Visitas'
            }
        },
        series: [{
            name: 'Impactos',
            data: js_evS4Views,
			color: '#39daff'
        }]
    });
	
	// Evento total clicks
	$('#eventoTotClicks').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Pages Clicks'
        },
		subtitle: {
			text: 'Clicks sobre los Splash Pages por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Clicks'
            }
        },
        series: [{
            name: 'Clicks',
            data: js_evTotClicks,
			color: '#3fae29'
        }]
    });
	
	// Evento Splash 1 clicks
	$('#eventoS1Clicks').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Page 1 Clicks'
        },
		subtitle: {
			text: 'Clicks sobre el Splash Page por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Clicks'
            }
        },
        series: [{
            name: 'Clicks',
            data: js_evS1Clicks,
			color: '#9800d1'
        }]
    });
	// Evento Splash 2 clicks
	$('#eventoS2Clicks').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Page 2 Clicks'
        },
		subtitle: {
			text: 'Clicks sobre el Splash Page por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Clicks'
            }
        },
        series: [{
            name: 'Clicks',
            data: js_evS2Clicks,
			color: '#ffb600'
        }]
    });
	// Evento Splash 3 clicks
	$('#eventoS3Clicks').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Page 3 Clicks'
        },
		subtitle: {
			text: 'Clicks sobre el Splash Page por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Clicks'
            }
        },
        series: [{
            name: 'Clicks',
            data: js_evS3Clicks,
			color: '#ff0037'
        }]
    });
	// Evento Splash 4 clicks
	$('#eventoS4Clicks').highcharts({
        chart: {
            type: 'area',
			zoomType: 'x'
        },
        title: {
            text: 'Evento 40 Splash Page 4 Clicks'
        },
		subtitle: {
			text: 'Clicks sobre el Splash Page por hora de 15:00 a 00:59 hrs'
		},
        xAxis: {
            categories: js_hours
        },
        yAxis: {
            title: {
                text: 'Clicks'
            }
        },
        series: [{
            name: 'Clicks',
            data: js_evS4Clicks,
			color: '#4e1ed3'
        }]
    });
});
</script>
<div id="pageTop">
<span style="font-size:30px;" class="color3">Visi&oacute;n <span class="color4">general</span></span>
</div>


<!-- La Fiesta 2015 Aud Telmex -->
<div>
<div id="pageTop">
<p class="eventTitle">La Fiesta de La Radio Guadalajara Nov 18, 2015</p>
</div>


<div id="pageContainer">
<?php
$myQuery = mysql_query("SELECT COUNT(*) quant FROM pageload WHERE splashID = 'promoTEC' AND created_at > '2015-11-18 15:00:00' AND created_at < '2015-11-19 00:59:00'");
$row = mysql_fetch_array($myQuery);
$totCount = $row["quant"];
?>
    <div class="fullCol">
        <div class="graphContainer">
            <div id="fiestaTotViews" class="graphDiv"></div>
            <div class="totCount">Total de impactos: <span style="color:#3fae29;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
</div>

<!-- Termina La Fiesta 2015 Aud Telmex -->


<!-- El Evento 40 2015 Aud Telmex -->
<div>
<div id="pageTop">
<p class="eventTitle">El Evento 40 Diez Guadalajara Nov 17, 2015</p>
</div>

<div id="pageContainer">
<?php
$myQuery = mysql_query("SELECT COUNT(*) quant FROM pageload WHERE created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' AND (splashID = 'ev01' OR splashID = 'ev02' OR splashID = 'ev03' OR splashID = 'ev04')");
$row = mysql_fetch_array($myQuery);
$totCount = $row["quant"];
?>
    <div class="fullCol">
        <div class="graphContainer">
            <div id="eventoTotViews" class="graphDiv"></div>
            <div class="totCount">Total de impactos: <span style="color:#001f5b;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
</div>

<div id="pageContainer">
    <div class="leftCol">
    	<?php
		$myQuery = mysql_query("SELECT COUNT(*) quant FROM pageload WHERE splashID = 'ev01' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00'");
		$row = mysql_fetch_array($myQuery);
		$totCount = $row["quant"];
		?>
        <div class="graphContainer">
        	<div id="eventoS1Views" class="graphDiv"></div>
            <div class="totCount">Total de impactos: <span style="color:#1a5cff;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
    <div class="rightCol">
    	<?php
		$myQuery = mysql_query("SELECT COUNT(*) quant FROM pageload WHERE splashID = 'ev02' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00'");
		$row = mysql_fetch_array($myQuery);
		$totCount = $row["quant"];
		?>
        <div class="graphContainer">
        	<div id="eventoS2Views" class="graphDiv"></div>
            <div class="totCount">Total de impactos: <span style="color:#31dd00;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
</div>

<div id="pageContainer">
    <div class="leftCol">
    	<?php
		$myQuery = mysql_query("SELECT COUNT(*) quant FROM pageload WHERE splashID = 'ev03' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00'");
		$row = mysql_fetch_array($myQuery);
		$totCount = $row["quant"];
		?>
        <div class="graphContainer">
        	<div id="eventoS3Views" class="graphDiv"></div>
            <div class="totCount">Total de impactos: <span style="color:#ff4e00;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
    <div class="rightCol">
    	<?php
		$myQuery = mysql_query("SELECT COUNT(*) quant FROM pageload WHERE splashID = 'ev04' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00'");
		$row = mysql_fetch_array($myQuery);
		$totCount = $row["quant"];
		?>
        <div class="graphContainer">
        	<div id="eventoS4Views" class="graphDiv"></div>
            <div class="totCount">Total de impactos: <span style="color:#39daff;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
</div>

<!-- Clicks -->
<div id="pageContainer">
<?php
$myQuery = mysql_query("SELECT COUNT(*) quant FROM clickcount WHERE created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00' AND (splashID = 'ev01' OR splashID = 'ev02' OR splashID = 'ev03' OR splashID = 'ev04')");
$row = mysql_fetch_array($myQuery);
$totCount = $row["quant"];
?>
    <div class="fullCol">
        <div class="graphContainer">
            <div id="eventoTotClicks" class="graphDiv"></div>
            <div class="totCount">Total de clicks: <span style="color:#3fae29;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
</div>

<div id="pageContainer">
    <div class="leftCol">
    	<?php
		$myQuery = mysql_query("SELECT COUNT(*) quant FROM clickcount WHERE splashID = 'ev01' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00'");
		$row = mysql_fetch_array($myQuery);
		$totCount = $row["quant"];
		?>
        <div class="graphContainer">
        	<div id="eventoS1Clicks" class="graphDiv"></div>
            <div class="totCount">Total de clicks: <span style="color:#9800d1;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
    <div class="rightCol">
    	<?php
		$myQuery = mysql_query("SELECT COUNT(*) quant FROM clickcount WHERE splashID = 'ev02' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00'");
		$row = mysql_fetch_array($myQuery);
		$totCount = $row["quant"];
		?>
        <div class="graphContainer">
        	<div id="eventoS2Clicks" class="graphDiv"></div>
            <div class="totCount">Total de clicks: <span style="color:#ffb600;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
</div>

<div id="pageContainer">
    <div class="leftCol">
    	<?php
		$myQuery = mysql_query("SELECT COUNT(*) quant FROM clickcount WHERE splashID = 'ev03' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00'");
		$row = mysql_fetch_array($myQuery);
		$totCount = $row["quant"];
		?>
        <div class="graphContainer">
        	<div id="eventoS3Clicks" class="graphDiv"></div>
            <div class="totCount">Total de clicks: <span style="color:#ff0037;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
    <div class="rightCol">
    	<?php
		$myQuery = mysql_query("SELECT COUNT(*) quant FROM clickcount WHERE splashID = 'ev04' AND created_at > '2015-11-17 15:00:00' AND created_at < '2015-11-18 00:59:00'");
		$row = mysql_fetch_array($myQuery);
		$totCount = $row["quant"];
		?>
        <div class="graphContainer">
        	<div id="eventoS4Clicks" class="graphDiv"></div>
            <div class="totCount">Total de clicks: <span style="color:#4e1ed3;"><?php echo $totCount; ?></span></div>
        </div>
    </div>
</div>

</div>
<!-- Termina El Evento 40 2015 Aud Telmex -->


<?php else : ?>

No est&aacute; autorizado a ver este recurso. <a href="index.php">Regresar</a>

<?php endif; ?>

<?php
include_once 'footer.php';
?>