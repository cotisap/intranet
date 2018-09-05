<?php
include "../includes/mssqlconn.php";
include "../includes/mysqlconn.php";
include '../head.php';

?>
<html>
<head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

</head>
<body>

<div>
<form method="post" action="" >
<table width="50%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Clientes
      	<div style="margin-top:10px">
        <select id="CardCode" name="CardCode" style="margin-top:10px;">
        	<?php
			//Clientes SAP
			$myQuery = mssql_query("SELECT CardCode, (CardCode+' - '+ CardName) as Name FROM OCRD  WHERE VatStatus ='Y'");
	
			echo "<optgroup label='Clientes SAP'>";
			while($row = mssql_fetch_array($myQuery)){			
				echo "<option value='".$row["CardCode"]."'>".$row["Name"]."</option>";
			};
			//clientes Intranet
			$mySN = mysql_query("SELECT Codigo_SN ,CONCAT(Codigo_SN, Name_SN) as Name_SN FROM SONE");
			echo "<optgroup label='Clientes'>";
			while($rowS = mysql_fetch_array($mySN)){			
				echo "<option value='".$rowS["Codigo_SN"]."'>".$rowS["Name_SN"]."</option>";
			};
			?>
        </select>
        </div>
        
      </td>
      <td align="right"><button type="submit" onClick="window.open('../clientes/clientes.php','width=400,height=550 0');">Nuevo Cliente</button></td>
    </tr>
    <tr>
      <td colspan="2">Articulos a Cotizar<br>
        <div id="itemContainer">
        	<table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
            	<thead>
                	<tr>
                        <td>Articulo</td>
                        <td width="76px">Cantidad</td>
                        <td width="30px">Precio</td>
                    </tr>
                </thead>
            </table>
            <div class='item'>
            	<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
                	<tr>	
                        <td>
                        	<select id='product' name='product[]' class='itemProduct'  onchange="myFunction2(this)" >
                            	<option value='' disabled>Selecciona...</option>
								<?php
								echo "<optgroup label='Articulos SAP'>";
                                $myQuery = mssql_query("SELECT T1.ItemCode, (T1.ItemCode + ' - ' + T1.ItemName) as ItemName, T2.price FROM OITM t1 inner join itm1 t2 on t1.itemcode=t2.itemcode  where T1.QryGroup1 ='Y' and T2.pricelist=1");
                                while($row = mssql_fetch_array($myQuery)){			
                                    echo "<option value='".$row["ItemCode"]."' id='".$row["price"]."'>".$row["ItemName"]."</option>";
                                };
								//clientes Intranet
								$mySN = mysql_query("SELECT Codigo_Art, Descripcion FROM ART1");
								echo "<optgroup label='Articulos'>";
								while($rowS = mysql_fetch_array($mySN)){			
									echo "<option value='".$rowS["Cadigo_ART"]."'>".$rowS["Descripcion"]."</option>";
								};

                                ?>
                            </select>
                        </td>
                        <td width='76px'><input type='number' id='quant' name='quant[]' required value="1" style='width:70px !important'/></td>
                        <td width='30px'><input type="text" id="precio" name="precio" disabled/></td>
                    </tr>
                </table>
            </div>
        </div>
	  </td>
   
    </tr>
    <tr>
    	<td>
        	<button type="button" onClick="insertar()">Agregar</button>
        </td>
    </tr>
    <tr>
    	<td>
            <?php //Ejemplo aprenderaprogramar.com
			$cant= $_POST['quant'];
			$precio = $_POST['precio'];
			$sub = $cant*$precio;
			echo "<br/>  Subtotal". $sub. "";
			?>
						
        </td
        ><td>
        	Total <input type="text" id="sub" name="sub"  />
        </td
    ></tr>
    <tr>
      <td colspan="2">Comentarios<br><textarea id="Comentarios" name="Comentarios" maxlength="256"></textarea></td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="formButton redB">Cancelar</button></td>
      <td align="right"><button type="submit" class="formButton blueB" onClick="window.open('../Cotizacion/CrearCotizacion.php','width=400,height=550 0');">Guardar</button></td>
    </tr>
  </tbody>
</table>


</form>
</div>
<script>
function myFunction2(sel) 
{
    var valor = document.getElementById("product");
	var price = sel.options[sel.selectedIndex];
	document.getElementById("precio").value=price.id;
}

</script>
<script>
function myFunction()
{
	 var valor = document.getElementById("product");
	var precio= valor.options[valor.selectedIndex];
	document.getElementById("Comentarios").value= precio.id;
	
}
function insertar()
{
	 alert('Se ha insertado');
}

</script>
</body>



</html>