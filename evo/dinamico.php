<?php
require "head_evo.php";
include "../includes/mssqlevo.php";
?>
<br>
<div class="container-fluid">
	<img src="imgEvo/logo_alianza_top.png" class="img-responsive pull-right">
</div><br>
<div class="container-fluid">
 <h3 class="text-center">DINÁMICO</h3>
 </div><br><br>
<?php
//Estado
echo "Estado&nbsp;";
		echo "<select name='estado'>";    
    		$query = "select ESTADO from EVO_FG group by ESTADO order by ESTADO asc ";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".$row['ESTADO']."</option>";
			}	
		echo "</select>";
		echo "<br>";	
		echo "<br>";	
		//Fecha desde
echo "Fecha inicial";
		echo "<select name='fecha_desde'>";    
    		$query = "select FECHA from EVO_FG group by FECHA ";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){ 
				echo "<option>".$row['FECHA']."</option>";
			}
	
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		//Fecha hasta
echo "Fecha termino";
		echo "<select name='fecha_hasta'>";    
    		$query = "select FECHA from EVO_FG group by FECHA ";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".$row['FECHA']."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
	
		//Familia
echo "Familia&nbsp;";
		echo "<select name='familia'>";    
    		$query = "select FAMILIA from EVO_FG group by FAMILIA ";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".$row['FAMILIA']."</option>";
			}
		echo "</select>";
	
		echo "<br>";	
		echo "<br>";
	
		//Cantidad
echo "Cantidad&nbsp;";
		echo "<select name='cantidad'>";    
    		$query = "select CANTIDAD from EVO_FG group by CANTIDAD ";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".$row['CANTIDAD']."</option>";
			}
		echo "</select>";
	
		echo "<br>";	
		echo "<br>";
	
		//n° factura
echo "Factura&nbsp;";
		echo "<select name='factura'>";    
    		$query = "select [N° DE FACTURA] from EVO_FG group by [N° DE FACTURA]";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode ($row['N° DE FACTURA'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// Aplicacion
echo "Aplicacion&nbsp;";
		echo "<select name='aplicacion'>";    
    		$query = "select APLICACION from EVO_FG group by APLICACION";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode($row['APLICACION'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// CLAVE
echo "Clave&nbsp;";
		echo "<select name='clave'>";    
    		$query = "select CLAVE from EVO_FG group by CLAVE";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode($row['CLAVE'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// Descripcion
echo "Descripcion&nbsp;";
		echo "<select name='descripcion'>";    
    		$query = "select DESCRIPCION from EVO_FG group by DESCRIPCION 
			order by DESCRIPCION asc";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode($row['DESCRIPCION'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// N° de cliente
echo "N° de cliente&nbsp;";
		echo "<select name='n_cliente'>";    
    		$query = "select [N° DE CLIENTE] from EVO_FG group by [N° DE CLIENTE]";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode($row['[N° DE CLIENTE]'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// Semana
echo "Semana&nbsp;";
		echo "<select name='semana'>";    
    		$query = "select SEMANA from EVO_FG group by SEMANA order by SEMANA asc";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode($row['SEMANA'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// VENDEDOR
echo "Vendedor&nbsp;";
		echo "<select name='vendedor'>";    
    		$query = "select VENDEDOR from EVO_FG group by VENDEDOR order by VENDEDOR asc";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode($row['VENDEDOR'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// Mes
echo "Mes&nbsp;";
		echo "<select name='mes'>";    
    		$query = "select MES from EVO_FG group by MES ";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode($row['MES'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// Marca
echo "Marca&nbsp;";
		echo "<select name='marca'>";    
    		$query = "select MARCA from EVO_FG group by MARCA order by MARCA asc";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option type='checkbox'>".utf8_encode($row['MARCA'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
		
		// Nombre de cliente
echo "Nombre de cliente&nbsp;";
		echo "<select name='nom_cliente'>";    
    		$query = "select [NOMBRE DE CLIENTE] from EVO_FG group by 
			[NOMBRE DE CLIENTE] order by [NOMBRE DE CLIENTE] asc";
    		$result = mssql_query($query);
			while ($row = mssql_fetch_assoc($result)){
				echo "<option>".utf8_encode($row['[NOMBRE DE CLIENTE]'])."</option>";
			}
		echo "</select>";
		
		echo "<br>";	
		echo "<br>";
?>
</div>