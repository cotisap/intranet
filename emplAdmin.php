<?php
session_start();
if ($_SESSION["admin"] != 'Y') {
	header("Location: no-auth.php");
	die();
}

include 'head.php';
?>
<style>
input {
	width:100%;
}
</style>
<link href="css/nestable/style.css" rel="stylesheet">
<p class="pageTitle">Administraci&oacute;n de empleados</p>

<div id="employeeAdmin">

  <div class="row">
    <div class="col-md-6">
      <h3>Empleados</h3>
      <menu id="nestable-menu">
        <button type="button" data-action="expand-all">Expandir todo</button>
        <button type="button" data-action="collapse-all">Colapsar todo</button>
      </menu>
      <div class="dd nestable" id="nestable">
        <ol class="dd-list">

          <!-- Employees -->

         <?php
		 $empQueryCommon = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'Y' AND SlpCode > 0 AND ";
		 $empQuery = $empQueryCommon."(U_manager = 0 OR U_manager = '' OR U_manager IS NULL) ORDER BY SlpName ASC";
		$empResult = mssql_query($empQuery);
		 while ($empRow = mssql_fetch_assoc($empResult)) {
			 echo "<li class='dd-item' data-id='".$empRow["SlpCode"]."' data-name='".$empRow["SlpCode"]."' data-slug='item-slug-1' data-new='0' data-deleted='0'>
					<div class='dd-handle'>".utf8_encode($empRow["SlpName"])."</div>
					<span class='button-edit btn btn-default btn-xs pull-right' data-owner-id='".$empRow["SlpCode"]."'>
					  <i class='fa fa-pencil' aria-hidden='true'></i>
					</span><div id='edit-div-".$empRow["SlpCode"]."'></div>";
			$empQueryL1 = $empQueryCommon."U_manager = ".$empRow["SlpCode"]." ORDER BY SlpName ASC";
			$empResultL1 = mssql_query($empQueryL1);
			if (mssql_num_rows($empResultL1) > 0) {
				echo "<ol class='dd-list'>";
			}
			while ($empRowL1 = mssql_fetch_assoc($empResultL1)) {
				echo "<li class='dd-item' data-id='".$empRowL1["SlpCode"]."' data-name='".$empRowL1["SlpCode"]."' data-slug='item-slug-5' data-new='0' data-deleted='0'>
						<div class='dd-handle'>".utf8_encode($empRowL1["SlpName"])."</div>
						<span class='button-edit btn btn-default btn-xs pull-right' data-owner-id='".$empRowL1["SlpCode"]."'>
						  <i class='fa fa-pencil' aria-hidden='true'></i>
						</span><div id='edit-div-".$empRowL1["SlpCode"]."'></div>";
				$empQueryL2 = $empQueryCommon."U_manager = ".$empRowL1["SlpCode"]." ORDER BY SlpName ASC";
				$empResultL2 = mssql_query($empQueryL2);
				if (mssql_num_rows($empResultL2) > 0) {
					echo "<ol class='dd-list'>";
				}
				while ($empRowL2 = mssql_fetch_assoc($empResultL2)) {
					echo "<li class='dd-item' data-id='".$empRowL2["SlpCode"]."' data-name='".$empRowL2["SlpCode"]."' data-slug='item-slug-5' data-new='0' data-deleted='0'>
							<div class='dd-handle'>".utf8_encode($empRowL2["SlpName"])."</div>
							<span class='button-edit btn btn-default btn-xs pull-right' data-owner-id='".$empRowL2["SlpCode"]."'>
							  <i class='fa fa-pencil' aria-hidden='true'></i>
							</span><div id='edit-div-".$empRowL2["SlpCode"]."'></div>";
					$empQueryL3 = $empQueryCommon."U_manager = ".$empRowL2["SlpCode"]." ORDER BY SlpName ASC";
					$empResultL3 = mssql_query($empQueryL3);
					if (mssql_num_rows($empResultL3) > 0) {
						echo "<ol class='dd-list'>";
					}
					while ($empRowL3 = mssql_fetch_assoc($empResultL3)) {
						echo "<li class='dd-item' data-id='".$empRowL3["SlpCode"]."' data-name='".$empRowL3["SlpCode"]."' data-slug='item-slug-5' data-new='0' data-deleted='0'>
								<div class='dd-handle'>".utf8_encode($empRowL3["SlpName"])."</div>
								<span class='button-edit btn btn-default btn-xs pull-right' data-owner-id='".$empRowL3["SlpCode"]."'>
								  <i class='fa fa-pencil' aria-hidden='true'></i>
								</span><div id='edit-div-".$empRowL3["SlpCode"]."'></div>";
						$empQueryL4 = $empQueryCommon."U_manager = ".$empRowL3["SlpCode"]." ORDER BY SlpName ASC";
						$empResultL4 = mssql_query($empQueryL4);
						if (mssql_num_rows($empResultL4) > 0) {
							echo "<ol class='dd-list'>";
						}
						while ($empRowL4 = mssql_fetch_assoc($empResultL4)) {
							echo "<li class='dd-item' data-id='".$empRowL4["SlpCode"]."' data-name='".$empRowL4["SlpCode"]."' data-slug='item-slug-5' data-new='0' data-deleted='0'>
									<div class='dd-handle'>".utf8_encode($empRowL4["SlpName"])."</div>
									<span class='button-edit btn btn-default btn-xs pull-right' data-owner-id='".$empRowL4["SlpCode"]."'>
									  <i class='fa fa-pencil' aria-hidden='true'></i>
									</span><div id='edit-div-".$empRowL4["SlpCode"]."'></div>
								  </li>";
						}
						if (mssql_num_rows($empResultL4) > 0) {
							echo "</ol>";
						}
						echo "</li>";
					}
					if (mssql_num_rows($empResultL3) > 0) {
						echo "</ol>";
					}
					echo "</li>";
				}
				if (mssql_num_rows($empResultL2) > 0) {
					echo "</ol>";
				}
				echo "</li>";
			}
			if (mssql_num_rows($empResultL1) > 0) {
				echo "</ol>";
			}
			echo "</li>";
		 }
		 ?> 
         
        </ol>
      </div>
    </div>
  </div>

</div>

<script src="js/nestable/jquery.nestable.js"></script>
<script src="js/nestable/jquery.nestable++.js"></script>
<script>
$('#nestable').nestable({
	maxDepth: 5,
	dropCallback: function(details) {
		var sourceID = details.sourceId;
		var destID = (details.destId == null ? 0 : details.destId);
		$.post("includes/genadmin/empAdmin.php?empID="+sourceID+"&mgrID="+destID)
		.done(function(data) {
			$("#employeeAdmin").find(".inMessage").remove();
			$("#employeeAdmin").append("<div class='inMessage'>Cambios guardados con &eacute;xito<div class='inCloseMessage'>[Aceptar]</div></div>");
			$(".inCloseMessage").on("click", function() {
				$(this).closest(".inMessage").remove();
			});
		})
		.fail(function(data) {
			console.log(data);
		});
		//console.log(sourceID + " " + destID);
	}
})
.on('change', updateOutput);

$(".inCloseMessage").on("click", function() {
	$(this).closest(".inMessage").remove();
});

var editForm = "<div class='editFormInner'>\
		<form id='empDetailsForm'>\
		<table class='formTable'>\
			<tr>\
				<td width='33%'>Email<input type='hidden' name='empID' id='empID'></td>\
				<td width='33%'>Contrase&ntilde;a</td>\
				<td width='33%'>Tel&eacute;fono</td>\
			</tr>\
			<tr>\
				<td width='33%'><input type='text' name='email' id='email'></td>\
				<td width='33%'><input type='text' name='password' id='password'></td>\
				<td width='33%'><input type='text' name='telephone' id='telephone'></td>\
			</tr>\
			<tr>\
				<td width='33%'>Extensi&oacute;n</td>\
				<td width='33%'>Lista de precios</td>\
				<td width='33%'>Comisi&oacute;n</td>\
			</tr>\
			<tr>\
				<td width='33%'><input type='text' name='extension' id='extension'></td>\
				<td width='33%'>\
					<select name='priceList' id='priceList' required>\
						<option value='' selected disabled>Selecciona...</option>\
						<?php
						$lpQuery = "SELECT ListNum, ListName FROM OPLN WHERE ValidFor = 'Y' ORDER BY ListNum ASC";
						$lpResult = mssql_query($lpQuery);
						while ($lpRow = mssql_fetch_assoc($lpResult)) {
							echo "<option value='".$lpRow["ListNum"]."'>(".$lpRow["ListNum"].") ".$lpRow["ListName"]."</option>";
						}
						?>
					</select>\
				</td>\
				<td width='33%'><input type='text' name='commission' id='commission' onKeyPress='return numeros(event)'></td>\
			</tr>\
			<tr>\
				<td width='33%'>Sucursal</td>\
				<td width='33%'>Cotiza exportaci&oacute;n</td>\
				<td width='33%'>Descuentos especiales</td>\
			</tr>\
			<tr>\
				<td width='33%'>\
					<select id='wareHouse' name='wareHouse'>\
						<option value='' selected disabled>Selecciona...</option>\
						<?php
						$whQuery = "SELECT WhsCode, WhsName FROM OWHS WHERE U_usable = 'Y' AND Inactive = 'N' ORDER BY WhsCode";
						$whResult = mssql_query($whQuery);
						while ($whRow = mssql_fetch_assoc($whResult)) {
							echo "<option value='".$whRow["WhsCode"]."'>(".$whRow["WhsCode"].") ".$whRow["WhsName"]."</option>";
						}
						?>
					</select>\
				</td>\
				<td width='33%'><input type='checkbox' name='export' id='export'></td>\
				<td width='33%'><input type='checkbox' name='discounts' id='discounts'></td>\
			</tr>\
			<tr>\
				<td colspan='3'>\
				<ul class='buttonBar'>\
					<li><button type='button' class='button red' id='empCancel' return false;'><i class='fa fa-ban' aria-hidden='true'></i> Cancelar</button></li>\
					<li><button type='button' class='button green' id='upEmp' name='upEmp'><i class='fa fa-floppy-o' aria-hidden='true'></i> Guardar</button></li>\
				</ul>\
				</td>\
			</tr>\
		</table>\
	</div>";
	



</script>

<?php include 'footer.php'; ?>