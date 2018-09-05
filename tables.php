<?php include 'head.php'; ?>

<?php if (login_check($mysqli) == true) : ?>

<?php include 'filter.php'; ?>

<div id="pageTop">
    <div class="leftCol">
    	<span class="title">Registros obtenidos por conexi&oacute;n</span>
    </div>
    <div class="rightCol">
		
    </div>
</div>

<div class="cd-tabs">
	<nav>
		<ul class="cd-tabs-navigation">
			<li><a data-content="inbox" class="selected" href="#0">General</a></li>
		</ul> <!-- cd-tabs-navigation -->
	</nav>

	<ul class="cd-tabs-content">

		<li data-content="new" class="selected">
        <!--div id="pageTop">
        <a href="includes/exportFacebook.php" target="_blank"><img src="images/csv_download.png" /></a>
        </div-->
			<div id="pageContainer">
<?php

$myQuery = mysql_query("SELECT id, venue, event, name, email FROM data WHERE event = 'AlejandroSanz' GROUP BY email ORDER BY id");
			
echo "<table border='0' cellpadding='0' cellspacing='5' class='recordsTable'>
<thead>
<tr>
<th class='tableHeader'>ID</th>
<th class='tableHeader'>Locación</th>
<th class='tableHeader'>Evento</th>
<th class='tableHeader'>Nombre</th>
<th class='tableHeader'>Email</th>
</tr>
</thead>
<tbody>";
while($row = mysql_fetch_array($myQuery)){
    echo "<tr>
    <td>".$row["id"]."</td>
    <td>".$row["venue"]."</td>
	<td>".$row["event"]."</td>
    <td>".$row["name"]."</td>
    <td>".$row["email"]."</td>
    </tr>";
};
echo "</tbody></table>";
?>

</div>
		</li>

	</ul> <!-- cd-tabs-content -->
</div> <!-- cd-tabs -->

<script src="js/jquery-2.1.1.js"></script>
<script src="js/main.js"></script> <!-- Resource jQuery -->

<?php else : ?>

<script type="text/javascript">
window.location.href = '/no-auth.php';
</script>

<?php endif; ?>

<?php include 'footer.php'; ?>