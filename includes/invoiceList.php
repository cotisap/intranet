<?php
include_once 'mssqlconn.php';
include_once 'mysqlconn.php';
session_start();

switch ($_SESSION["company"]) {
	case "fg":
		$idComp = "0010000102";
		break;
	case "alianza":
		$idComp = "0010000100";
		break;
	case "sureste":
		$idComp = "0010000101";
		break;
	case "pacifico":
		$idComp = "0010000103";
		break;
}

$requestData = $_REQUEST;
$term = $requestData['search']['value'];
$columns = array( 
// datatable column index  => database column name
	0 => 'DocNum', 
	1 => 'DocDate',
	2 => 'DocTotal',
	3 => 'DocCur',
	4 => 'Empl_Ven',
	5 => 'Id_Cot1'
);

// getting total number records without any search
$queryTotal = "SELECT COUNT(*) quant FROM OINV WHERE CardCode = '".$_SESSION["customer"]."'";
$resultTotal = mssql_query($queryTotal);
$rowTotal = mssql_fetch_assoc($resultTotal);
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$queryInvoices = "SELECT T1.DocNum, T1.DocDate, T1.CardCode, IIF(T1.DocCur = 'USD', T1.DocTotalFC, T1.DocTotal) as DocTotal, T1.DocCur, T1.EDocNum, T2.SlpName FROM OINV T1 JOIN OSLP T2 ON T1.SlpCode = T2.SlpCode WHERE CardCode = '".$_SESSION["customer"]."'";

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$queryInvoices.= " AND T1.DocNum LIKE '%".$term."%'";

	$result = mssql_query($queryInvoices);
	$totalFiltered = mssql_num_rows($result); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

$queryInvoices.= " ORDER BY ".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." OFFSET ".$requestData['start']." ROWS FETCH NEXT ".$requestData['length']." ROWS ONLY";

$result = mssql_query($queryInvoices);

$data = array();
while($row = mssql_fetch_assoc($result)) {
	$nestedData = array();

	$nestedData[] = $row["DocNum"];
	$nestedData[] = $row["DocDate"];
	$nestedData[] = "$".number_format($row["DocTotal"], 2, '.', ',');
	$nestedData[] = $row["DocCur"];
	$nestedData[] = utf8_encode($row["SlpName"]);
	$nestedData[] = "<a href='http://folium.idited.com/facturacliente.php?num=".$row["DocNum"]."'><i class='fa fa-external-link-square' aria-hidden='true'></i></a> <a href='http://corporativo-vallejo-tntchvdtpq.dynamic-m.com:8082/".$idComp."/".date('Y-m', strtotime(str_replace('/', '-', $row["DocDate"])))."/".$_SESSION["customer"]."/IN/".$row["EDocNum"].".pdf' target='_blank'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a> <a href='http://corporativo-vallejo-tntchvdtpq.dynamic-m.com:8082/".$idComp."/".date('Y-m', strtotime(str_replace('/', '-', $row["DocDate"])))."/".$_SESSION["customer"]."/IN/".$row["EDocNum"].".xml' target='_blank'><i class='fa fa-code' aria-hidden='true'></i></a>";
	//$nestedData[] = "<a href='inflowDetail.php?infID=".$row["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a>";
	$data[] = $nestedData;	
}

$json_data = array(
	"draw" => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal" => intval( $totalData ),  // total number of records
	"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data" => $data   // total data array
);

echo json_encode($json_data);


?>