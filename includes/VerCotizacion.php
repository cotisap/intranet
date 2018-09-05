<?php
include "../includes/mssqlconn.php";
include "../includes/mysqlconn.php";
include '../head.php';

$Id_Cot= $_GET["id"];	
	
?>
<html>
<head>
</head>
<body>
	<center><h1>Cotizacion</h1></center>
        <p></p>
<table>
	<tr>
    	<td>
        <?php 
		$queryMC = "SELECT Codigo_SN, CardName FROM COTI WHERE Id_Cot1=$Id_Cot";
		$resultMC = mysql_query($queryMC);
		$rowMC = mysql_fetch_row($resultMC);
		echo "Codigo Cliente:". $rowMC[0];
		?>
        </td>
    </tr> 
    <tr>
        <td>
        <?php 
		$queryID= "SELECT Codigo_SN FROM COTI WHERE Id_Cot1=$Id_Cot";
		$resultID= mysql_query($queryID);
		$rowID = mysql_fetch_row($resultID);
		$id_SN =$rowID[0];
		
		$queryCN = "SELECT CardName FROM OCRD WHERE CardCode= $id_SN";
		$resultCN = mssql_query($queryCN);
		$rowCN = mssql_fetch_row($resultCN);
		echo "Nombre Cliente:". $rowCN[0];
		?>
        </td>
    </tr>
</table>
<p></p>
<table width="80%" border="1">
  <thead>
  	<tr>
        <td>Codigo Articulo</td>
        <td>Cantidad</td>
        <td>Precio</td>
        <td>Descuento</td>
        <td>Sub Total</td>

    </tr>
  <tbody>
  		<?php  
		$queryMC = "SELECT Line, Codigo_Art, Cantidad, Precio_Unidad, Factor, Sub_Tot_Line FROM COT1 where Refe_Cot=$Id_Cot ORDER BY Line DESC";
		$resultMC = mysql_query($queryMC);
		$rowMC = mysql_fetch_array($resultMC);
			while($rowMC= mysql_fetch_array($resultMC)) 
			{ ?>
        <tr>
            <td><?php echo $rowMC["Codigo_Art"];?></td>
            <td><?php echo $rowMC["Cantidad"];?></td>
            <td><?php echo $rowMC["Precio_Unidad"];?></td>
            <td><?php echo $rowMC["Factor"]; ?></td>
            <td><?php echo $rowMC["Sub_Tot_Line"]; ?></td>
        </tr>
        <?php }?>
  </tbody>
 </table>
</body>
</html>