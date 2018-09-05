<?php
include "../includes/mssqlconn.php";
include "../includes/mysqlconn.php";
include '../head.php';


?>
<html>
<head>
</head>
<script language="javascript" src="js/jquery-1.2.6.min.js"></script>
<body>
Clientes
<form method="post" action="../clientes/AltaSN.php" >
<table width="0%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      	<td width="50%"><br>
		Nombre Cliente<input type="text"  id="Name_SN" name="Name_SN"><br>
		</td>
    </tr>
    <tr>
        <td width="50%">
        RFC<input type="text" required id="RFC_SN" name="RFC_SN"><br>
        </td>    
    </tr>
    <tr>
        <td width="50%">
        Tipo de Persona <select id="TipoSo" name="TipoSo">
                    <option value="I" >FISICA</option>
                    <option value="C" >MORAL</option>
            </select>
        </td>
      </tr>
      <tr>
        <td width="50%">           
            Telefono<input type="text" id="Tel_SN" name="Tel_SN" onKeyPress="return numeros(event)"><br>
              </td>
      </tr>
      <tr>
        <td width="50%">
            Email<input type="email" id="email_SN" name="email_SN"><br>
              </td>
      </tr>
      <tr>
        <td width="50%">
            Moneda<select  id='moneda' name='moneda'>
                    <option value="MXN" >MXN</option>
                    <option value="USD" >USD</option>
                    <option value="EUR" >EUR</option>
                </select>
        </td>
      </tr>
      <tr>
        <td width="50%">
Direccion Fiscal<br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Calle y Numero<input type="text"  id="Calle_F" name="Calle_F"><br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Colonia<input type="text"  id="Colonia_F" name="Colonia_F">
        </td>
      </tr>
      <tr>
        <td width="50%">
Municipio/Delegacion<input type="text"  id="Mun_Del_F" name="Mun_Del_F"><br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Ciudad<input type="text"  id="Ciudad_F" name="Ciudad_F">
        </td>
      </tr>
      <tr>
        <td width="50%">
Pais<select  id='pais' name='pais'>
		<option value="0" >Seleccionar</option>
		<option value="1" >Estados Unidos</option>
        <option value="2" >Mexico</option>
    </select>
            
Estado <select  id='estado' name='estado' >
       </select>
         </td>
      </tr>
      <tr>
        <td width="50%">
Codigo Postal<input type="text"  id="CP_F" name="CP_F" onKeyPress="return numeros(event)"><br><br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Direccion Entrega<br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Calle y Numero<input type="text" id="Calle_E" name="Calle_E"><br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Colonia<input type="text" id="Colonia_E" name="Colonia_E">
        </td>
      </tr>
      <tr>
        <td width="50%">
Municipio/Delegacion<input type="text" id="Mun_Del_E" name="Mun_Del_E"><br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Ciudad<input type="text"  id="Ciudad_F" name="Ciudad_E">
        </td>
      </tr>
      <tr>
      	<td>
Pais<select  id='paise' name='paise'>
                <option value="0" >Seleccionar</option>
                <option value="1" >Estados Unidos</option>
                <option value="2" >Mexico</option>
            </select>
        </td>
      </tr>
      <tr>
        <td width="50%">
Estado <select  id='estadoe' name='estadoe' ></select><br>
        </td>
      </tr>
<tr>
        <td width="50%">
Codigo Postal<input type="text"  id="CP_E" name="CP_E" onKeyPress="return numeros(event)"><br><br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Condiciones de Pago<br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Limite de Credito<input type="text"  id="Lim_Cred" name="Lim_Cred" onKeyPress="return numeros(event)"><br>
        </td>
      </tr>
      <tr>
        <td width="50%">
Indicador de Impuestos<select id="IndImp" name="IndImp">
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
			</select>
        </td>
      </tr>
      <tr>
        <td width="50%">
Condiciones de Pago<select id="CondPag" name="CondPag">
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
			</select>
        </td>
      </tr>
      <tr>
        <td width="50%">
Lista de Precios<select id="LisPre" name="LisPre">
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
			</select>
        </td>
      </tr>
      <tr>
        <td width="50%">
Comentarios<br>
<input type="text"  id="Comentarios" name="Comentarios"><br>
        </td>
      </tr>
    <tr>
      <td align="left"><button type="button" class="Button" onClick="cancel();">Cancelar</button></td>
      <td align="right"><button type="submit" class="Button">Guardar</button></td>
    </tr>
  </tbody>
</table>
</form>
<script type="text/javascript">
function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " 0123456789-+()";
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
   $("#pais").change(function () {
           $("#pais option:selected").each(function () {
            elegido=$(this).val();
            $.post("estados.php", { elegido: elegido }, function(data){
            $("#estado").html(data);
            });            
        });
   })
});

$(document).ready(function(){
   $("#paise").change(function () {
           $("#paise option:selected").each(function () {
            elegidoe=$(this).val();
            $.post("estadose.php", { elegidoe: elegidoe }, function(data){
            $("#estadoe").html(data);
            });            
        });
   })
});
</script>
</body>
</html>

