<?php
session_start();
if ($_SESSION["admin"] != 'Y') {
	header("Location: no-auth.php");
	die();
}

include 'head.php';

?>

<link rel="stylesheet" type="text/css" href="css/selectfile/component.css" />

<div class="overlay">
    <div id="editSlideForm">
        <!-- Edit Slide Form -->
    </div>
</div>





<div class="reportContainer">
    <table class="reportTable">
        <thead><tr><th width="59">Cod. Art</th><th width="195">Descripcion</th><th width="43">UMV</th><th width="43">Precio</th>
        <th width="56">Moneda</th><th width="44">Editar</th><th width="61">Eliminar</th></tr></thead>
        <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
        <tbody>
        <?php	
        $query = "SELECT Codigo_art, Descripcion, UMV, precio, moneda from ART1 WHERE company = '".$_SESSION["company"]."' ORDER BY Codigo_art";
		$result = mysql_query($query);
        //display the results 
        while($row = mysql_fetch_array($result)) {	
          echo "<tr><td>".utf8_encode($row["Codigo_art"])."</td>
		  <td>".utf8_encode($row["Descripcion"])."</td>
		  <td>".utf8_encode($row["UMV"])."</td>
		  <td>".$row["precio"]."</td>
		  <td>".$row["moneda"]."</td>
		  <td align='center'><img src='images/pencil.png' class='viewDetails' data-id='".$row["id"]."'></td>
		  <td align='center'><img src='images/remove-icon.png' class='removeDetails' data-id='".$row["id"]."'></td>
		  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>


<?php include 'footer.php'; ?>