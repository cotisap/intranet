<?php
function shortify( $slp ){
	if( strlen($slp) >= 15 ){
		$aux = substr($slp, 0, 15);
		$aux = $aux."...";
		return $aux;
	}else return $slp;
}
include 'mssqlconn.php';
/*function shortify( str ){
	return ;
}*/
$toDate = date('Y-m-d H:i:s');
$fromDate = strtotime ( '-45 day' , strtotime ( $toDate ) ) ;
$fromDate = date ( 'Y-m-j' , $fromDate );
//$fromDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', "01/11/2017")));
$repDate = date('Y-m-d H:i:s');


$bPartner = $_POST["bPartner"];
//$bPartner = "00001";
			
//declare the SQL statement that will query the database
$query = "DECLARE @P1 varchar(30) = '$fromDate'; --fecha de contabilización desde
DECLARE @P2 varchar(30) = '$toDate'; --fecha de contabilización hasta
DECLARE @P6 varchar(10) = '$bPartner' --'00625'--'$sp' --Socio de negocios
DECLARE @P3 varchar(30) = '$repDate'; --fecha de reconciliación (vencimiento)

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
ORDER BY MAX(T4.[CardName]), Max(T0.[taxdate]) DESC";

//execute the SQL query and return records
$result = mssql_query($query);
	
echo "<div class='reportContainer'><table class='reportTable'><thead><tr><th>Cliente</th><th>#</th><th>Contabilización</th><th>Vencimiento</th><th>Total</th><th class='hSal'>Saldo</th><th class='h0'>Abono futuro</th><th class='h30'>0 - 30</th><th class='h60'>31 - 60</th><th class='h90'>61 - 90</th><th class='h120'>91 - 120</th><th class='h121'>120+</th></tr></thead><tfoot><tr><th></th><th></th><th></th><th></th><th></th><th class='cdSal'></th><th class='cd0'></th><th class='cd30'></th><th class='cd60'></th><th class='cd90'></th><th class='cd120'></th><th class='cd121'></th></tr></tfoot><tbody>";
date_default_timezone_set('America/Mexico_City');
//display the results 
$num = mssql_num_rows( $result );
if( $num == 0){

echo "</tbody></table></div>";
echo "<p></p>";
echo "<h4 style='text-align:center;'>NO TIENE ESTADOS DE CUENTA CON ANTIGUEDAD DE 45 DÍAS</h4>";
}else{

while($row = mssql_fetch_array($result))
{
	$curDate = new DateTime();
	$repDate1 = date_create($repDate);
	$cntDate = date_create($row["fechadocumento"]);
	$dueDate = date_create($row["fechavencimiento"]);
	$daysCount = date_diff($dueDate, $repDate1);
	$daysCount = $daysCount->format('%R%a');
	
	if ($daysCount < 0) {
		$m0 = number_format($row["SaldodocMN"], 2,'.',',');
		$m30 = "";
		$m60 = "";
		$m90 = "";
		$m120 = "";
		$m121 = "";
	} else if ($daysCount >= 0 && $daysCount < 31) {
		$m0 = "";
		$m30 = number_format($row["SaldodocMN"], 2,'.',',');
		$m60 = "";
		$m90 = "";
		$m120 = "";
		$m121 = "";
	} else if ($daysCount >= 31 && $daysCount < 61) {
		$m0 = "";
		$m30 = "";
		$m60 = number_format($row["SaldodocMN"], 2,'.',',');
		$m90 = "";
		$m120 = "";
		$m121 = "";
	} else if ($daysCount >= 61 && $daysCount < 91) {
		$m0 = "";
		$m30 = "";
		$m60 = "";
		$m90 = number_format($row["SaldodocMN"], 2,'.',',');
		$m120 = "";
		$m121 = "";
	} else if ($daysCount >= 91 && $daysCount < 121) {
		$m0 = "";
		$m30 = "";
		$m60 = "";
		$m90 = "";
		$m120 = number_format($row["SaldodocMN"], 2,'.',',');
		$m121 = "";
	} else {
		$m0 = "";
		$m30 = "";
			$m60 = "";
			$m90 = "";
		$m120 = "";
		$m121 = number_format($row["SaldodocMN"], 2,'.',',');
	}
	
  echo "<tr><td>".shortify($row["SocioNegocios"])."</td><td>".$row["Documento"]."</td><td>".date_format($cntDate, "d/m/Y")."</td><td>".date_format($dueDate, "d/m/Y")."</td><td class='dtot'>$".number_format($row["TotaldocMN"], 2,'.',',')."</td><td class='dSal'>".number_format($row["SaldodocMN"], 2,'.',',')."</td><td class='d0'>".$m0."</td><td class='d30'>".$m30."</td><td class='d60'>".$m60."</td><td class='d90'>".$m90."</td><td class='d120'>".$m120."</td><td class='d121'>".$m121."</td></tr>";
}
echo "</tbody></table></div>";
echo "<p></p>";
}
?>
