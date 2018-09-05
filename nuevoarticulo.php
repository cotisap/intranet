<?php
include "head.php";

$currencies = array();
$queryCur = "SELECT ChkName, CurrCode FROM OCRN";
$resultCur = mssql_query($queryCur);
while ($rowCur = mssql_fetch_assoc($resultCur)) {
	$currencies[$rowCur["ChkName"]] = $rowCur["CurrCode"];
}

?>

<style>
.formTable tr td {
	width:33.33%;
}
input {
	width:100%;
}
select {
	width:100%;
}
</style>
<form method="post" action="/includes/AltaArt.php">
<table class="formTable">
  <tbody>
    <tr>
      <td colspan="3" class="qSec">Datos del art&iacute;culo</td>
    </tr>
    <tr>
    	<td colspan="3">
        	<div class="fullWidth">
            	<div class="thirdFirst">
                	<table class="formTable">
                    	<tr>
                        	<td>C&oacute;digo</td>
                        </tr>
                        <tr>
                        	<td><input type="text"  id="Code_Art" name="Code_Art" required></td>
                        </tr>
                    </table>
                </div>
                <div class="thirdSecond">
                	<table class="formTable">
                    	<tr>
                        	<td>Descripci&oacute;n</td>
                        </tr>
                        <tr>
                        	<td><input type="text" required id="Des_Art" name="Des_Art" maxlength="250" required></td>
                        </tr>
                    </table>
                </div>
                <div class="thirdLast">
                	<table class="formTable">
                    	<tr>
                        	<td>Grupo de art&iacute;culos</td>
                        </tr>
                        <tr>
                        	<td><select id="Grp_Art" name="Grp_Art">
      <option value="" disabled selected>Selecciona...</option>
				<?php
                    $queryGA = "SELECT ItmsGrpCod, ItmsGrpNam  from OITB ORDER BY ItmsGrpNam ASC";
                    $resultGA = mssql_query($queryGA);
                    $rowGA = mssql_fetch_array($resultGA);
                    while($rowGA = mssql_fetch_array($resultGA))
                    {
                      echo "<option value=".$rowGA["ItmsGrpCod"]." >".$rowGA["ItmsGrpNam"]."</option>";
                    }
                
                ?>			
			</select></td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
    <tr>
    	<td colspan="3">
        	<div class="fullWidth">
            	<div class="thirdFirst">
                	<table class="formTable">
                    	<tr>
                        	<td>Unidad de medida venta</td>
                        </tr>
                        <tr>
                        	<td>
                            <select  id='UMV' name='UMV' required>
                                <option value="" disabled selected>Selecciona...</option>
                                <?php
                                $queryUMV = "SELECT SalUnitMsr FROM OITM GROUP BY SalUnitMsr ORDER BY SalUnitMsr";
                                $resultUMV = mssql_query($queryUMV);
                                while ($rowUMV = mssql_fetch_array($resultUMV)) {
                                    echo "<option value='".$rowUMV["SalUnitMsr"]."'>".$rowUMV["SalUnitMsr"]."</option>";
                                }
                                ?>
                            </select>
                			</td>
                        </tr>
                    </table>
                </div>
                <div class="thirdSecond">
                	<table class="formTable">
                    	<tr>
                        	<td>Precio de venta</td>
                        </tr>
                        <tr>
                        	<td><input type="text"  id="Precio_Art" name="Precio_Art" onKeyPress="return numeros(event)" required></td>
                        </tr>
                    </table>
                </div>
                <div class="thirdLast">
                	<table class="formTable">
                    	<tr>
                        	<td>Moneda</td>
                        </tr>
                        <tr>
                        	<td><select  id='moneda' name='moneda' required>
      				<option value="" disabled selected>Selecciona...</option>
                    <?php
					foreach($currencies as $currency => $val) {
						echo "<option value='".$val."'>".$val."</option>";
					}
					?>
                </select></td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Comentarios</td>
    </tr>
    <tr>
      <td colspan="3"><textarea id="Comentarios" name="Comentarios"></textarea></td>
    </tr>
    <tr>
      <td colspan="3">
      	<ul class="buttonBar">
        	<li><button type="button" class="button red" onClick="cancel();">Cancelar</button></li>
            <li><button type="submit" class="button green">Guardar</button></li>
        </ul>
      </td>
    </tr>
  </tbody>
</table>

</form>


<script type="text/javascript">
function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " 0123456789";
    especiales = [8,37,39,46];
 
    tecla_especial = false
    for(var i in especiales){
 if(key == especiales[i]){
     tecla_especial = true;
     break;
        } 
    }
 
    if(letras.indexOf(tecla)==-1 && !tecla_especial)
        return false;
}

$(document).ready(function(){
	$("select").select2();
	$('#newArtBT').addClass('active');
	$('#subHeaderTitle').html('Art&iacute;culo nuevo');
});
</script>

<?php
include 'footer.php';
?>