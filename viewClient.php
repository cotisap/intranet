<?php
include 'includes/mysqlconn.php';
include 'includes/mssqlconn.php';
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
  <tbody>
    <tr>
      <td colspan="3" class="qSec">Datos del cliente</td>
    </tr>
    <tr>
      <td>Raz&oacute;n social</td>
      <td>RFC</td>
      <td>Tipo de persona</td>
    </tr>
    <tr>
      <td><input type="text"  id="Name_SN" name="Name_SN" required></td>
      <td><input type="text" id="RFC_SN" name="RFC_SN" required ></td>
      <td>
      	<select id="TipoSo" name="TipoSo" required >
            <option value="" disabled selected>Selecciona...</option>
            <option value="I">FISICA</option>
            <option value="C">MORAL</option>
        </select>
    	</td>
    </tr>
    <tr>
      <td>Persona de contacto</td>
      <td>Tel&eacute;fono</td>
      <td>Email</td>
    </tr>
    <tr>
      <td><input type="text"  id="Name_PC" name="Name_PC" required></td>
      <td><input type="text" id="Tel_SN" name="Tel_SN" onKeyPress="return numeros(event)" required></td>
      <td><input type="email" id="email_SN" name="email_SN" required></td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Direcci&oacute;n fiscal</td>
    </tr>
    <tr>
      <td>Calle y n&uacute;mero</td>
      <td>Colonia</td>
      <td>Municipio / Delegaci&oacute;n</td>
    </tr>
    <tr>
      <td><input type="text"  id="Calle_F" name="Calle_F" required ></td>
      <td><input type="text"  id="Colonia_F" name="Colonia_F" required ></td>
      <td><input type="text"  id="Mun_Del_F" name="Mun_Del_F" required ></td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td>Pa&iacute;s</td>
      <td>Estado</td>
    </tr>
    <tr>
      <td><input type="text"  id="Ciudad_F" name="Ciudad_F" required ></td>
      <td>
      <select  id='pais' name='pais' required >
		<option value='' disabled selected>Selecciona...</option>
		<option value="1" >Estados Unidos</option>
        <option value="2" >M&eacute;xico</option>
    </select>
    </td>
      <td><select  id='estado' name='estado' required >
		<option value='' disabled selected>Selecciona...</option>
       </select></td>
    </tr>
    <tr>
      <td>C&oacute;digo postal</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><input type="text"  id="CP_F" name="CP_F" onKeyPress="return numeros(event)" required ></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Direcci&oacute;n de entrega</td>
    </tr>
    <tr>
      <td>Calle y n&uacute;mero</td>
      <td>Colonia</td>
      <td>Municipio / Delegaci&oacute;n</td>
    </tr>
    <tr>
      <td><input type="text" id="Calle_E" name="Calle_E" required ></td>
      <td><input type="text" id="Colonia_E" name="Colonia_E" required ></td>
      <td><input type="text" id="Mun_Del_E" name="Mun_Del_E" required ></td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td>Pa&iacute;s</td>
      <td>Estado</td>
    </tr>
    <tr>
      <td><input type="text"  id="Ciudad_E" name="Ciudad_E" required ></td>
      <td><select  id='paise' name='paise' required >
                <option value='' disabled selected>Selecciona...</option>
                <option value="1" >Estados Unidos</option>
                <option value="2" >M&eacute;xico</option>
            </select></td>
      <td><select  id='estadoe' name='estadoe' required >
		<option value='' disabled selected>Selecciona...</option>
        </select></td>
    </tr>
    <tr>
      <td>C&oacute;digo postal</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><input type="text"  id="CP_E" name="CP_E" onKeyPress="return numeros(event)" required ></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Informaci&oacute;n de pago</td>
    </tr>
    <tr>
      <td>Condiciones de pago</td>
      <td>L&iacute;mite de cr&eacute;dito</td>
      <td>Indicador de impuestos</td>
    </tr>
    <tr>
      <td><select id="CondPag" name="CondPag" required onChange="selec()">
			<option value='' disabled selected>Selecciona...</option>
			<?php
                //pais
                $queryCP = "SELECT GroupNum, PymntGroup FROM OCTG where PymntGroup NOT LIKE'%COMPRAS%'";
                $resultCP = mssql_query($queryCP);
                $rowCP = mssql_fetch_array($resultCP);
				
                while($rowCP = mssql_fetch_array($resultCP))
                {
                  echo "<option value=".$rowCP["GroupNum"]." >".$rowCP["PymntGroup"]."</option>";
                }
            
            ?>			
			</select></td>
      <td><input type="text"  id="Lim_Cred" name="Lim_Cred" onKeyPress="return numeros(event)" required></td>
      <td><select id="IndImp" name="IndImp" required >
		<option value='' disabled selected>Selecciona...</option>
				<?php
                    //pais
                    $queryII = "SELECT Code, NAME FROM OSTC WHERE ValidForAR ='Y'";
                    $resultII = mssql_query($queryII);
                    $rowII = mssql_fetch_array($resultII);
                    while($rowII = mssql_fetch_array($resultII))
                    {
                      echo "<option value=".$rowII["Code"]." >".$rowII["NAME"]."</option>";
                    }
                
                ?>			
			</select></td>
    </tr>
    <tr>
      <td>Lista de precios</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><select id="LisPre" name="LisPre" required >
			<option value='' disabled selected>Selecciona...</option>
<?php
	//pais
	$queryLP = "SELECT ListNum, ListName  FROM OPLN WHERE BASE_NUM IN (1,3,4,5,6)";
	$resultLP = mssql_query($queryLP);
	$rowLP = mssql_fetch_array($resultLP);
	while($rowLP = mssql_fetch_array($resultLP))
	{
	  echo "<option value=".$rowLP["ListNum"]." >".$rowLP["ListName"]."</option>";
	}

?>			
			</select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="qSec">Comentarios</td>
    </tr>
    <tr>
      <td colspan="3"><textarea id="Comentarios" name="Comentarios"></textarea></td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="button red" onClick="cancel();">Cancelar</button></td>
      <td>&nbsp;</td>
      <td align="right"><button type="submit" class="button green">Guardar</button></td>
    </tr>
  </tbody>
</table>