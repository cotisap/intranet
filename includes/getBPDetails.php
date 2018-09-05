<?php
session_start();

include "mssqlconn.php";
include "mysqlconn.php";

$bpCode = $_GET["bpCode"];

$queryCheckSAP = "SELECT COUNT(CardCode) isSAP FROM OCRD WHERE CardType = 'C' AND CardCode = '$bpCode'";
$resultCheckSAP = mssql_query($queryCheckSAP);
$rowCheckSAP = mssql_fetch_assoc($resultCheckSAP);

if ($rowCheckSAP["isSAP"] != 0) {
	$queryAsignee = "SELECT SlpCode FROM OCRD WHERE CardCode = '$bpCode'";
	$resultAsignee = mssql_query($queryAsignee);
	$rowAsignee = mssql_fetch_assoc($resultAsignee);
	
	if($_SESSION["admin"] == 'Y' || $rowAsignee["SlpCode"] == $_SESSION["salesPrson"] || ($_SESSION["isBP"] && $_SESSION["customer"] == $bpCode)){
		$queryBP = "SELECT TOP 1 T1.CardCode, T1.CardName, T1.CmpPrivate, T1.LicTradNum, T1.ValidFor, T1.Phone1, T1.IntrntSite, T1.E_Mail, T1.CreditLine, T1.Balance, (T1.CreditLine - T1.Balance) Available, T2.ExtraMonth, T2.ExtraDays, isSAP = 'Y' FROM OCRD T1 JOIN OCTG T2 ON T1.GroupNum = T2.GroupNum WHERE CardType = 'C' AND CardCode = '$bpCode'";
		$resultBP = mssql_query($queryBP);

		// Contact Person
		$queryCP = "SELECT TOP 1 (T3.FirstName + ' ' + T3.LastName) cpName, T3.Tel1 cpPhone, T3.E_MailL cpEmail FROM OCPR T3 JOIN OCRD T1 ON T3.CardCode = T1.CardCode WHERE T3.CardCode = '$bpCode'";
		$resultCP = mssql_query($queryCP);

		// Billing Address
		$queryB = "SELECT TOP 1 T2.Street bStreet, T2.Block bCol, T2.City bCity, T2.ZipCode bZip, T2.County bCounty, T4.Name bState, T5.Name bCountry FROM CRD1 T2 JOIN OCRD T1 ON T2.CardCode = T1.CardCode JOIN OCST T4 ON T2.State = T4.Code JOIN OCRY T5 ON T2.Country = T5.Code WHERE T2.CardCode = '$bpCode' AND T2.Address = 'FISCAL'";
		$resultB = mssql_query($queryB);

		// Shipping Address
		$queryS = "SELECT TOP 1 T2.Street sStreet, T2.Block sCol, T2.City sCity, T2.ZipCode sZip, T2.County sCounty, T4.Name sState, T5.Name sCountry FROM CRD1 T2 JOIN OCRD T1 ON T2.CardCode = T1.CardCode JOIN OCST T4 ON T2.State = T4.Code JOIN OCRY T5 ON T2.Country = T5.Code WHERE T2.CardCode = '$bpCode' AND T2.Address = 'ENVIO'";
		$resultS = mssql_query($queryS);

		// Payment Info
		$queryP = "SELECT TOP 1 CONVERT(VARCHAR(10),T1.DocDate,103) DocDate, T1.TrsfrSum FROM ORCT T1 WHERE T1.CardCode = '$bpCode' ORDER BY T1.DocDate DESC";
		$resultP = mssql_query($queryP);


		$rowBP = array_merge((array)mssql_fetch_assoc($resultBP), (array)mssql_fetch_assoc($resultCP), (array)mssql_fetch_assoc($resultB), (array)mssql_fetch_assoc($resultS), (array)mssql_fetch_assoc($resultP));
	} else {
		$queryBP = "SELECT CardCode, CardName, isSAP = 'Y' FROM OCRD WHERE CardCode = '$bpCode'";
		$resultBP = mssql_query($queryBP);
		$rowBP = mssql_fetch_assoc($resultBP);
	}
	
} else {
	$queryBPNoSAP = "SELECT Id_SN CardCode, Name_SN CardName, RFC LicTradNum, Name_CP cpName, email E_Mail, Tel Phone1, Calle bStreet, Colonia bCol, Municipio bCounty, Ciudad bCity, CP bZip, Estado bState, Pais bCountry, Calle_E sStreet, Colonia_E sCol, Ciudad_E sCity, Municipo_E sCounty, Estado_E sState, Pais_E sCountry, CP_E sZip, isSAP FROM SONE WHERE Id_SN = '".$bpCode."' LIMIT 1";
	$resultBPNoSAP = mysql_query($queryBPNoSAP);
	$rowBP = mysql_fetch_assoc($resultBPNoSAP);
}

if(count($rowBP) != 0) {
	foreach($rowBP as &$value) {
		$value = utf8_encode($value);
	}
	
	echo json_encode($rowBP);
}
?>
