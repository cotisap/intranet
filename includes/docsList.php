<?php
session_start();

include "mysqlconn.php";

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$term = utf8_decode($requestData['search']['value']);
$category = $_REQUEST["cat"];
$brand = $_REQUEST["brand"];
$by = $_REQUEST["by"];
$columns = array( 
// datatable column index  => database column name
	0 => 'category', 
	1 => 'brand',
	2 => 'title',
	3 => 'file',
	4 => 'link',
	5 => 'id'
);

// getting total number records without any search
$queryTotal = "SELECT COUNT(*) quant FROM TOOL WHERE company = '".$_SESSION["company"]."'";
if ($category == "" || $category == NULL || $category == 0) {
	$queryTotal.= "";
} else {
	$queryTotal.= " AND category = '$category'";
}
if ($brand == "" || $brand == NULL || $brand == 0) {
	$queryTotal.= "";
} else {
	$queryTotal.= " AND brand = '$brand'";
}

$resultTotal = mysql_query($queryTotal);
$rowTotal = mysql_fetch_assoc($resultTotal);
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT T2.category, T3.brand, T1.title, T1.file, T1.file link, T1.remarks, T1.id FROM TOOL T1 JOIN TCAT T2 ON T1.category = T2.id JOIN TBNS T3 ON T1.brand = T3.id WHERE T1.company = '".$_SESSION["company"]."'";

if ($category == "" || $category == NULL || $category == 0) {
	$query.= "";
} else {
	$query.= " AND T2.id = '$category'";
}

if ($brand == "" || $brand == NULL || $brand == 0) {
	$query.= "";
} else {
	$query.= " AND T3.id = '$brand'";
}

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	switch ($by) {
		case "TITULO":
			$query.= " AND T1.title LIKE '%".$term."%'";
			break;
		case "ARCHIVO":
			$query.= " AND T1.file LIKE '%".$term."%'";
			break;
	}

	$result = mysql_query($query);
	$totalFiltered = mysql_num_rows($result); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

//$query.= " ORDER BY ID ASC LIMIT 0, 10";
$query.= " ORDER BY ".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start'].", ".$requestData['length']."";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */

$result = mysql_query($query);

$data = array();
while($row = mysql_fetch_assoc($result)) {
	$nestedData = array();

	$nestedData[] = utf8_encode($row["category"]);
	$nestedData[] = utf8_encode($row["brand"]);
	$nestedData[] = utf8_encode($row["title"]);
	$nestedData[] = utf8_encode($row["remarks"]);
	$nestedData[] = $row["file"];
	$nestedData[] = "<a href='ftp/herramientas/".utf8_encode($row["link"])."' target='_blank' download><i class='fa fa-external-link' aria-hidden='true'></i></a>";
	$nestedData[] = "<a data-id='".$row["id"]."' class='updateDoc'><img src='images/pencil.png' ></a>";
	$nestedData[] = "<a data-id='".$row["id"]."' class='removeDoc'><img src='images/remove-icon.png' ></a>";
	$data[] = $nestedData;
}

$json_data = array(
	"draw" => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal" => intval( $totalData ),  // total number of records
	"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
