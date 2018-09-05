<?php
include_once 'mssqlconn.php';
include_once 'mysqlconn.php';
session_start();

$requestData = $_REQUEST;
$term = $requestData['search']['value'];
$columns = array( 
// datatable column index  => database column name
	0 => 'Id_Cot1', 
	1 => 'FechaCreacion',
	2 => 'Total_Doc',
	3 => 'DocCur',
	4 => 'Empl_Ven',
	5 => 'Id_Cot1'
);

// getting total number records without any search
$queryTotal = "SELECT COUNT(*) quant FROM COTI WHERE Codigo_SN = '".$_SESSION["customer"]."' AND company = '".$_SESSION["company"]."'";
$resultTotal = mysql_query($queryTotal);
$rowTotal = mysql_fetch_assoc($resultTotal);
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$queryQuotes = "SELECT Id_Cot1, DocCur, Total_Doc, FechaCreacion, Empl_Ven FROM COTI WHERE Codigo_SN = '".$_SESSION["customer"]."' AND company = '".$_SESSION["company"]."'";

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$queryQuotes.= " AND Id_Cot1 LIKE '%".$term."%'";

	$result = mysql_query($queryQuotes);
	$totalFiltered = mysql_num_rows($result); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

$queryQuotes.= " ORDER BY ".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start'].", ".$requestData['length']."";

$result = mysql_query($queryQuotes);

$data = array();
while($row = mysql_fetch_assoc($result)) {
	$qEmpName = "SELECT SlpName FROM OSLP WHERE SlpCode = '".$row["Empl_Ven"]."'";
	$rEmpName = mssql_query($qEmpName);
	$rowEmpName = mssql_fetch_assoc($rEmpName);
	$nestedData = array();

	$nestedData[] = $row["Id_Cot1"];
	$nestedData[] = $row["FechaCreacion"];
	$nestedData[] = "$".number_format($row["Total_Doc"], 2, '.', ',');
	$nestedData[] = $row["DocCur"];
	$nestedData[] = utf8_encode($rowEmpName["SlpName"]);
	$nestedData[] = "<a href='cotizacioncliente.php?idCot=".$row["Id_Cot1"]."'><i class='fa fa-external-link-square' aria-hidden='true'></i></a> <a href='pdf.php?idcot=".$row["Id_Cot1"]."' target='_blank'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>";
	//$nestedData[] = "<a href='inflowDetail.php?infID=".$row["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a>";
	$data[] = $nestedData;	
}

$json_data = array(
	"draw" => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal" => intval( $totalData ),  // total number of records
	"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data" => $data   // total data array
);

//close the connection
mysql_close($dbhandle);

echo json_encode($json_data);


?>