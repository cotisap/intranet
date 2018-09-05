<?php
session_start();
if ($_SESSION["admin"] != 'Y') {
	header("Location: no-auth.php");
	die();
}

include 'head.php';
?>

<link rel="stylesheet" type="text/css" href="js/dt/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>


<p class="pageTitle">Administraci&oacute;n general</p>

<table class="formTable">
  <tbody>
    <tr>
      <td class="qSec">Correos de avisos</td>
    </tr>
    <tr>
      <td><div id="emailsDiv"></div></td>
    </tr>
    <tr>
      <td class="qSec">Notas comerciales</td>
    </tr>
    <tr>
      <td>
        <div id="cNotesDiv">
          <?php include('includes/adminnotes.php'); ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="qSec">Descuentos</td>
    </tr>
    <tr>
        <td>
          <div id="discountDiv">
            <?php include('includes/admindiscount.php') ?>
          </div>
        </td>
    </tr>
    <tr>
      <td class="qSec">Lista de claves y productos</td>
    </tr>
    <tr>
      <td><div id="prodListDiv"></div></td>
    </tr>

    <tr>
    	<td class="qSec">Inserci&oacute;n de promociones trimestrales</td>
    </tr>
    <tr>
    	<td>
    		<form action="uploadFiles.php" method="post" enctype="multipart/form-data">
    			<label for="promocion">Promoci&oacute;n</label>
          <input type="hidden" name="company" value="<?php echo $_SESSION["company"]; ?>">
    			<br><br>
    			<input type="file" id="promocion" name="promocion">
    			<br><br>
    			<input type="submit" class="button green" name="cargar" value="cargar">
    		</form>
    	</td>
    </tr>

    <tr>
    	<td class="qSec">Imagen del login</td>
    </tr>
    <tr>
    	<td>
    		<form action="uploadimagefondo.php" method="post" enctype="multipart/form-data">
    			<label for="imglogin">Fondo</label>
    			<br><br>
    			<input type="file" id="imglogin" name="imglogin">
    			<br><br>
    			<input type="submit" class="button green" name="cargar" value="cargar">
    		</form>
    	</td>
    </tr>

  </tbody>
</table>


<script>
// Load forms
$(document).ready(function() {
	$.post("emails.php", function(data) {
		$("#emailsDiv").html(data);
	});
	$.post("prodList.php", function(data) {
		$("#prodListDiv").html(data);
	});
});
</script>

<?php include 'footer.php'; ?>