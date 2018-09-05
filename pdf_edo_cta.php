<?php
$fromDate = $_POST["fromDate"];
$toDate = $_POST["toDate"];
$repDate = $_POST["repDate"];
$bPartner= $_POST["bPartner"];


include_once "includes/mysqlconn.php";
include_once "includes/mssqlconn.php";

require('fpdf/mc_table_edo_cta.php');

$folioCot = $_REQUEST["idcot"];
$pdf = new PDF_MC_Table();
$pdf->AddFont('Narrow','','narrow.php');
$pdf->AddFont('Narrow','B','narrowb.php');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Narrow','B',8,'C');
$pdf->SetWidths(array(16,20,20,19,19,17,17,17,17,17,17));
$pdf->Row(array("Referencia",utf8_decode('Contabilización'),"Vencimiento","Total","Saldo","Abono F","0 - 30","31 - 60","61 - 90","91 - 120","120+"));
$pdf->SetFont('Narrow','',8, 'C');	
	
// Lineas de Estado
$queryLines = "DECLARE @P1 varchar(30) = '$fromDate';
DECLARE @P2 varchar(30) = '$toDate';
DECLARE @P3 varchar(10) = '$repDate'
DECLARE @P6 varchar(30) = '$bPartner';

SELECT          T0.[transid],
			T0.[line_id],
			Max(T0.[account]),
			Max(T0.[shortname]),
			Max(T0.[transtype]),
			Max(T0.[createdby]),
			Max(T0.[baseref]) AS Documento,
			Max(T0.[sourceline]), 
			Max(T0.[refdate]), 
			Max(T0.[duedate]) AS fechavencimiento, 
			Max(T0.[taxdate]) AS fechadocumento, 
			Max(T0.[balduecred]) + Sum(T1.[reconsum]) AS TotaldocMN, 
			Max(T0.[balfccred])  + Sum(T1.[reconsumfc]), 
			Max(T0.[balsccred])  + Sum(T1.[reconsumsc]), 
			Max(T0.[linememo]) AS comentario, 
			Max(T3.[foliopref]), 
			Max(T3.[folionum]), 
			Max(T0.[indicator]) AS indicador, 
			Max(T4.[cardname]), 
			Max(T5.[cardcode]) AS CodeSN, 
			Max(T5.[cardname]) AS SocioNegocios, 
			Max(T4.[balance]), 
			Max(T5.[numatcard]), 
			Max(T5.[slpcode]) AS CodVend, 
			Max(T0.[project]) AS Proyecto, 
			Max(T0.[debit])   - Max(T0.[credit]) AS SaldodocMN, 
			Max(T0.[fcdebit]) - Max(T0.[fccredit]), 
			Max(T0.[sysdeb])  - Max(T0.[syscred]), 
			Max(T4.[pymcode]), 
			Max(T5.[blockdunn]), 
			Max(T5.[dunnlevel]), 
			Max(T5.[transtype]), 
			Max(T5.[issales]) AS EsVenta, 
			Max(T4.[currency]), 
			Max(T0.[fccurrency]), 
			Max(T6.[slpname]) AS Vendedor, 
			Max(T4.[dunterm]), 
			Max(T0.[dunnlevel]), 
			T0.[bplname] 
FROM            [dbo].[jdt1] T0 
INNER JOIN      [dbo].[itr1] T1 
ON              T1.[transid] = T0.[transid] 
AND             T1.[transrowid] = T0.[line_id] 
INNER JOIN      [dbo].[oitr] T2 
ON              T2.[reconnum] = T1.[reconnum] 
INNER JOIN      [dbo].[ojdt] T3 
ON              T3.[transid] = T0.[transid] 
INNER JOIN      [dbo].[ocrd] T4 
ON              T4.[cardcode] = T0.[shortname] 
LEFT OUTER JOIN [dbo].[b1_journaltranssourceview] T5 
ON              T5.[objtype] = T0.[transtype] 
AND             T5.[docentry] = T0.[createdby] 
AND             ( 
							T5.[transtype] <> N'I' 
			OR              ( 
											T5.[transtype] = N'I' 
							AND             T5.[instlmntid] = T0.[sourceline] )) 
LEFT OUTER JOIN [dbo].[oslp] T6 
ON              T6.[slpcode] = T5.[slpcode] 
OR              ( 
							T6.[slpname] = N'-Ningún empleado del departament'
			AND             ( 
											T0.[transtype] = N'30' 
							OR              T0.[transtype] = N'321' 
							OR              T0.[transtype] = N'-5' 
							OR              T0.[transtype] = N'-2' 
							OR              T0.[transtype] = N'-3' 
							OR              T0.[transtype] = N'-4' )) 
WHERE           T0.[refdate] <= (@P2) --To
AND             T0.[refdate] >= (@P1) --From
AND             T0.[refdate] <= (@P3) 
AND             T4.[cardtype] = ('C') --(@P4) 
AND             T4.[balance] <> 0 --(@P5) 
AND             T5.[cardcode] = (@P6) 
--AND             T6.[slpname] <= (@P7) 
--AND             T6.[SlpCode] = (@P6)
--AND             T6.[active] = (@P8) 
AND             T2.[recondate] > (@P3) 
AND             T1.[iscredit] = 'D' --(@P10) 
GROUP BY        T0.[transid], 
			T0.[line_id], 
			T0.[bplname] 
HAVING          Max(T0.[balfccred]) <> - Sum(T1.[reconsumfc]) 
OR              Max(T0.[balduecred]) <>- Sum(T1.[reconsum]) 
UNION ALL 
SELECT          T0.[transid], 
			T0.[line_id], 
			Max(T0.[account]), 
			Max(T0.[shortname]), 
			Max(T0.[transtype]), 
			Max(T0.[createdby]), 
			Max(T0.[baseref]), 
			Max(T0.[sourceline]), 
			Max(T0.[refdate]), 
			Max(T0.[duedate]), 
			Max(T0.[taxdate]), 
			- Max(T0.[balduedeb]) - Sum(T1.[reconsum]), 
			- Max(T0.[balfcdeb]) - Sum(T1.[reconsumfc]), 
			- Max(T0.[balscdeb]) - Sum(T1.[reconsumsc]), 
			Max(T0.[linememo]), 
			Max(T3.[foliopref]), 
			Max(T3.[folionum]), 
			Max(T0.[indicator]), 
			Max(T4.[cardname]), 
			Max(T5.[cardcode]), 
			Max(T5.[cardname]), 
			Max(T4.[balance]), 
			Max(T5.[numatcard]), 
			Max(T5.[slpcode]), 
			Max(T0.[project]), 
			Max(T0.[debit])   - Max(T0.[credit]), 
			Max(T0.[fcdebit]) - Max(T0.[fccredit]), 
			Max(T0.[sysdeb])  - Max(T0.[syscred]), 
			Max(T4.[pymcode]), 
			Max(T5.[blockdunn]), 
			Max(T5.[dunnlevel]), 
			Max(T5.[transtype]), 
			Max(T5.[issales]), 
			Max(T4.[currency]), 
			Max(T0.[fccurrency]), 
			Max(T6.[slpname]), 
			Max(T4.[dunterm]), 
			Max(T0.[dunnlevel]), 
			T0.[bplname] 
FROM            [dbo].[jdt1] T0 
INNER JOIN      [dbo].[itr1] T1 
ON              T1.[transid] = T0.[transid] 
AND             T1.[transrowid] = T0.[line_id] 
INNER JOIN      [dbo].[oitr] T2 
ON              T2.[reconnum] = T1.[reconnum] 
INNER JOIN      [dbo].[ojdt] T3 
ON              T3.[transid] = T0.[transid] 
INNER JOIN      [dbo].[ocrd] T4 
ON              T4.[cardcode] = T0.[shortname] 
LEFT OUTER JOIN [dbo].[b1_journaltranssourceview] T5 
ON              T5.[objtype] = T0.[transtype] 
AND             T5.[docentry] = T0.[createdby] 
AND             ( 
							T5.[transtype] <> N'I' 
			OR              ( 
											T5.[transtype] = N'I' 
							AND             T5.[instlmntid] = T0.[sourceline] )) 
LEFT OUTER JOIN [dbo].[oslp] T6 
ON              T6.[slpcode] = T5.[slpcode] 
OR              ( 
							T6.[slpname] = N'-Ningún empleado del departamento de ventas-'
			AND             ( 
											T0.[transtype] = N'30' 
							OR              T0.[transtype] = N'321' 
							OR              T0.[transtype] = N'-5' 
							OR              T0.[transtype] = N'-2' 
							OR              T0.[transtype] = N'-3' 
							OR              T0.[transtype] = N'-4' )) 
WHERE           T0.[refdate] <= (@P2) --(@P11) --To
AND             T0.[refdate] >= (@P1) --(@P12) --From
AND             T0.[refdate] <= (@P3) 
AND             T4.[cardtype] = ('C') --(@P14) 
AND             T4.[balance] <> 0 --(@P15) 
AND             T5.[cardcode] = (@P6) 
--AND             T6.[slpname] <= (@P17)
--AND             T6.[SlpCode] = (@P6) 
--AND             T6.[active] = (@P18) 
AND             T2.[recondate] > (@P3) 
AND             T1.[iscredit] = 'D'--(@P20) 
GROUP BY        T0.[transid], 
			T0.[line_id], 
			T0.[bplname] 
HAVING          Max(T0.[balfcdeb]) <> - Sum(T1.[reconsumfc]) 
OR              Max(T0.[balduedeb]) <>- Sum(T1.[reconsum]) 
UNION ALL 
SELECT          T0.[transid], 
			T0.[line_id], 
			Max(T0.[account]), 
			Max(T0.[shortname]), 
			Max(T0.[transtype]), 
			Max(T0.[createdby]), 
			Max(T0.[baseref]), 
			Max(T0.[sourceline]), 
			Max(T0.[refdate]), 
			Max(T0.[duedate]), 
			Max(T0.[taxdate]), 
			Max(T0.[balduecred]) - Max(T0.[balduedeb]), 
			Max(T0.[balfccred])  - Max(T0.[balfcdeb]), 
			Max(T0.[balsccred])  - Max(T0.[balscdeb]), 
			Max(T0.[linememo]), 
			Max(T1.[foliopref]), 
			Max(T1.[folionum]), 
			Max(T0.[indicator]), 
			Max(T2.[cardname]), 
			Max(T3.[cardcode]), 
			Max(T3.[cardname]), 
			Max(T2.[balance]), 
			Max(T3.[numatcard]), 
			Max(T3.[slpcode]), 
			Max(T0.[project]), 
			Max(T0.[debit])   - Max(T0.[credit]), 
			Max(T0.[fcdebit]) - Max(T0.[fccredit]), 
			Max(T0.[sysdeb])  - Max(T0.[syscred]), 
			Max(T2.[pymcode]), 
			Max(T3.[blockdunn]), 
			Max(T3.[dunnlevel]), 
			Max(T3.[transtype]), 
			Max(T3.[issales]), 
			Max(T2.[currency]), 
			Max(T0.[fccurrency]), 
			Max(T4.[slpname]), 
			Max(T2.[dunterm]), 
			Max(T0.[dunnlevel]), 
			T0.[bplname] 
FROM            [dbo].[jdt1] T0 
INNER JOIN      [dbo].[ojdt] T1 
ON              T1.[transid] = T0.[transid] 
INNER JOIN      [dbo].[ocrd] T2 
ON              T2.[cardcode] = T0.[shortname] 
LEFT OUTER JOIN [dbo].[b1_journaltranssourceview] T3 
ON              T3.[objtype] = T0.[transtype] 
AND             T3.[docentry] = T0.[createdby] 
AND             ( 
							T3.[transtype] <> N'I' 
			OR              ( 
											T3.[transtype] = N'I' 
							AND             T3.[instlmntid] = T0.[sourceline] )) 
LEFT OUTER JOIN [dbo].[oslp] T4 
ON              T4.[slpcode] = T3.[slpcode] 
OR              ( 
							T4.[slpname] = N'-Ningún empleado del departamento de ventas-'
			AND             ( 
											T0.[transtype] = N'30' 
							OR              T0.[transtype] = N'321' 
							OR              T0.[transtype] = N'-5' 
							OR              T0.[transtype] = N'-2' 
							OR              T0.[transtype] = N'-3' 
							OR              T0.[transtype] = N'-4' )) 
WHERE           T0.[refdate] <= (@P2) --(@P21) --To
AND             T0.[refdate] >= (@P1) --(@P22) --From
AND             T0.[refdate] <= (@P3) 
AND             T2.[cardtype] = ('C') --(@P24) 
AND             T2.[balance] <> 0 --(@P25) 
AND             T2.[cardcode] = (@P6) 
--AND             T4.[slpname] <= (@P27) 
--AND  T4.[SlpCode] = (@P6)
--AND  T4.[SlpCode] = (@P6) --nombre vendedor hasta
--AND             T4.[active] = (@P8)--(@P28) 
AND             ( 
							T0.[balduecred] <> T0.[balduedeb] 
			OR              T0.[balfccred] <> T0.[balfcdeb] ) 
AND             NOT EXISTS 
			( 
					   SELECT     U0.[transid], 
								  U0.[transrowid] 
					   FROM       [dbo].[itr1] U0 
					   INNER JOIN [dbo].[oitr] U1 
					   ON         U1.[reconnum] = U0.[reconnum] 
					   WHERE      T0.[transid] = U0.[transid] 
					   AND        T0.[line_id] = U0.[transrowid] 
					   --AND        U1.[recondate] > (@P29) 
					   GROUP BY   U0.[transid], 
								  U0.[transrowid]) 
GROUP BY        T0.[transid], 
			T0.[line_id], 
			T0.[bplname]
ORDER BY MAX(T4.[CardName]), MAX(T0.[BaseRef])";



$resultLines = mssql_query($queryLines);
$saldoTotal = 0;
$m0Tot = 0;
$m30Tot = 0;
$m60Tot = 0;
$m90Tot = 0;
$m120Tot = 0;
$m121Tot = 0;
$pdf->SetDrawColor(255,255,255);
while ($rowLines = mssql_fetch_assoc($resultLines)) {
	$curDate = new DateTime();
	$repDate1 = date_create($repDate);
	$cntDate = date_create($rowLines["fechadocumento"]);
	$dueDate = date_create($rowLines["fechavencimiento"]);
	$daysCount = date_diff($dueDate, $repDate1);
	$daysCount = $daysCount->format('%R%a');
	
	$m0 = 0;
	$m30 = 0;
	$m60 = 0;
	$m90 = 0;
	$m120 = 0;
	$m121 = 0;

	if ($daysCount < 0) {
		$m0 = $rowLines["SaldodocMN"];
		$m0Tot += $m0;
	} else if ($daysCount >= 0 && $daysCount < 31) {
		$m30 = $rowLines["SaldodocMN"];
		$m30Tot += $m30;
	} else if ($daysCount >= 31 && $daysCount < 61) {
		$m60 = $rowLines["SaldodocMN"];
		$m60Tot += $m60;
	} else if ($daysCount >= 61 && $daysCount < 91) {
		$m90 = $rowLines["SaldodocMN"];
		$m90Tot += $m90;
	} else if ($daysCount >= 91 && $daysCount < 121) {
		$m120 = $rowLines["SaldodocMN"];
		$m120Tot += $m120;
	} else {
		$m121 = $rowLines["SaldodocMN"];
		$m121Tot += $m121;
	}
	
	$pdf->Row(array($rowLines["Documento"],date_format($cntDate, 'd M Y'),date_format($dueDate, 'd M Y'),"$ ".number_format($rowLines["TotaldocMN"], 2, '.',','),"$ ".number_format($rowLines["SaldodocMN"], 2, '.', ','),"$ ".number_format($m0, 2, ".", ","),"$ ".number_format($m30, 2, ".", ","),"$ ".number_format($m60, 2, ".", ","),"$ ".number_format($m90, 2, ".", ","),"$ ".number_format($m120, 2, ".", ","),"$ ".number_format($m121, 2, ".", ",")));
	$pdf->SetWidths(array(16,20,20,19,19,17,17,17,17,17,17));
	$saldoTotal += $rowLines["SaldodocMN"];
}
$pdf->SetFont('Narrow','B',8);
$pdf->Row(array("","","","","$ ".number_format($saldoTotal, 2, '.', ','),"$ ".number_format($m0Tot, 2, ".", ","),"$ ".number_format($m30Tot, 2, ".", ","),"$ ".number_format($m60Tot, 2, ".", ","),"$ ".number_format($m90Tot, 2, ".", ","),"$ ".number_format($m120Tot, 2, ".", ","),"$ ".number_format($m121Tot, 2, ".", ",")));

$pdf->SetDrawColor(0,0,0);
////////////////////////////////////////////////////////////////////
	
	$pdf->Ln(2);
		
	$pdf->SetFont('Narrow','B',8);
	$pdf->Cell(196,.1,'',0,1,'',true);
	$pdf->Cell(196,5,'',0,1,'');

$pdf->Output();
?>