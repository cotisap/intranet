<?php
include "../includes/mssqlconn.php";
$options="";
$pais= $_POST["elegido"];

if ($_POST["elegido"]==1) {
  					$options.='<option value="" disabled selected> Selecciona...</option>';
$queryII="SELECT Code, Name FROM OCST where country = 'US' order by Name";
			
                    $resultII = mssql_query($queryII);
                    $rowII = mssql_fetch_array($resultII);
                    while($rowII = mssql_fetch_array($resultII))
                    {
                     $options.=' <option value='.$rowII["Code"].'>'.$rowII["Name"].'</option>';
                    } 
}
if ($_POST["elegido"]==2) {
					$options.='<option value="" disabled selected> Selecciona...</option>';
$queryII="SELECT Code, Name FROM OCST where country = 'MX' order by Name";
                    $resultII = mssql_query($queryII);
                    $rowII = mssql_fetch_array($resultII);
                    while($rowII = mssql_fetch_array($resultII))
                    {
                     $options.='<option value='.$rowII["Code"].'>'.$rowII["Name"].'</option>';
                    } 
}

echo $options;
?>