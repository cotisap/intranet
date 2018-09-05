<?php
include_once 'mssqlconn.php';
include_once 'mysqlconn.php';
session_start();

$requestData = $_REQUEST;
$term = $requestData['search']['value'];
$columns = array( 
// datatable column index  => database column name
	0 => 'ItemCode', 
	1 => 'ItemName',
	2 => 'FirmName',
	3 => 'OnHand',
	4 => 'OnOrder',
	5 => 'SalUnitMsr',
	6 => 'Price',
	7 => 'Currency'
);

// getting total number records without any search
$queryTotal = "SELECT COUNT(*) quant FROM OITM WHERE validFor = 'Y' AND frozenFor = 'N' AND SellItem = 'Y'";
$resultTotal = mssql_query($queryTotal);
$rowTotal = mssql_fetch_assoc($resultTotal);
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$queryItems = "SELECT T1.ItemCode, T1.ItemName, T3.FirmName, T1.OnHand, T1.OnOrder, T1.SalUnitMsr, T2.Price, T2.Currency FROM OITM T1 JOIN ITM1 T2 ON T1.ItemCode = T2.ItemCode JOIN OMRC T3 ON T1.FirmCode = T3.FirmCode WHERE T1.validFor = 'Y' AND T1.frozenFor = 'N' AND T1.SellItem = 'Y' AND T2.PriceList = '".$_SESSION["priceList"]."'";

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$queryItems.= " AND T1.ItemCode LIKE '%".$term."%'";

	$result = mssql_query($queryItems);
	$totalFiltered = mssql_num_rows($result); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

$queryItems.= " ORDER BY ".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." OFFSET ".$requestData['start']." ROWS FETCH NEXT ".$requestData['length']." ROWS ONLY";

$result = mssql_query($queryItems);

$data = array();
while($row = mssql_fetch_assoc($result)) {
	$nestedData = array();

	$nestedData[] = utf8_encode($row["ItemCode"]);
	$nestedData[] = utf8_encode($row["ItemName"]);
	$nestedData[] = utf8_encode($row["FirmName"]);
	$nestedData[] = number_format($row["OnHand"], 2, '.', ',');
	$nestedData[] = number_format($row["OnOrder"], 2, '.', ',');
	$nestedData[] = $row["SalUnitMsr"];
	$nestedData[] = "$".number_format($row["Price"], 2, '.', ',');
	$nestedData[] = $row["Currency"];
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