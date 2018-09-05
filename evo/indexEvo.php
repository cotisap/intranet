<?php
session_start();
require 'head_evo.php';


?>
<br><br><br><br><br>
<div class="container">
	<p class="text-center">Herramienta para visualizar y descargar reportes 
    EVO tanto de <strong>ALIANZA ELECTRICA</strong> y <strong>FG ELECTRICAL</strong></p>
</div><br><br>
<div class="container">
<h3 class="text-center">¿Qué reporte desea generar?</h3>
<br><br><br>
	<div class="row">
    	<div class="col-md-4"></div>
        <div class="col-md-4">
        <!--Botones-->
        <form action="dinamico.php" name="evoF" method="post">
           <button type="submit" name="evoFg" class="btn btn-default center-block disabled">FG ELECTRICAL</button>
        </form><br>
        <form action="dinamico.php" name="evoA" method="post">
           <button type="submit" name="evoAz" class="btn btn-default center-block">ALIANZA ELECTRICA</button>
        </form>
        </div>
        <div class="col-md-4"></div>
		
	</div>
</div><br><br><br><br><br><br><br><br><br><br>
<?
require 'footer.php';
?>