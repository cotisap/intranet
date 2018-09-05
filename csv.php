<?php
include "includes/mysqlconn.php";
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Column 1', 'Column 2', 'Column 3'));

$rows = mysql_query("SELECT  Id_Cot1, DATE_FORMAT(FechaCreacion, '%Y%m%d') as Date, Codigo_SN, CardName, Total_MN, Total_Doc, TC FROM COTI where Id_Cot1=2000031");

// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);

?>