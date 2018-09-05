<?php
include "../includes/mssqlconn.php";
include "../includes/mysqlconn.php";
include '../head.php';

?>
<html>
<head>
</head>
<body>
Articulos
<form method="post" action="../Articulos/AltaArt.php" >
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      	<td width="50%"><br>
		Codigo Articulo<input type="text"  id="Code_Art" name="Code_Art"><br>
		</td>
    </tr>
    <tr>
        <td width="50%">
        Descripcion<input type="text" required id="Des_Art" name="Des_Art" maxlength="250"><br>
        </td>    
    </tr>
          <tr>
        <td width="50%">
        Sujeto a Impuestos <select id="Suj_Imp" name="Suj_Imp">
                    <option value="Y" >SI</option>
                    <option value="N" >NO</option>
            </select>
        </td>
      </tr>
      <tr>
      	<td>
Grupo Articulos<select id="Grp_Art" name="Grp_Art">
				<?php
                    //pais
                    $queryGA = "SELECT ItmsGrpCod, ItmsGrpNam  from OITB ORDER BY ItmsGrpNam ASC";
                    $resultGA = mssql_query($queryGA);
                    $rowGA = mssql_fetch_array($resultGA);
                    while($rowGA = mssql_fetch_array($resultGA))
                    {
                      echo "<option value=".$rowGA["ItmsGrpCod"]." >".$rowGA["ItmsGrpNam"]."</option>";
                    }
                
                ?>			
			</select>
        </td>
      </tr>
      <tr>
      	<td>
Lista de Precios<select id="Lis_Art" name="Lis_Art">
				<?php
                    //pais
                    $queryLP = "select ListNum , ListName  from OPLN ";
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
        	Precio <input type="text"  id="Precio_Art" name="Precio_Art" onKeyPress="return numeros(event)">
        </td>
        <td>
        	Moneda <select  id='moneda' name='moneda'>
                    <option value="MXN" >MXN</option>
                    <option value="USD" >USD</option>
                    <option value="EUR" >EUR</option>
                </select>
        </td>
      <tr>
        <td>
        	Unidad Medida Venta <select  id='UMV' name='UMV'>
                    <option value="CAJA" >CAJA</option>
                    <option value="KIT" >KIT</option>
                    <option value="PAQ" >PAQUETE</option>
                    <option value="PZA" >PIEZA</option>
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
</script>
</body>
</html>