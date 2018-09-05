<?php
session_start();
require('fpdf.php');


//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
	$R = substr($couleur, 1, 2);
	$rouge = hexdec($R);
	$V = substr($couleur, 3, 2);
	$vert = hexdec($V);
	$B = substr($couleur, 5, 2);
	$bleu = hexdec($B);
	$tbl_couleur = array();
	$tbl_couleur['R']=$rouge;
	$tbl_couleur['V']=$vert;
	$tbl_couleur['B']=$bleu;
	return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
	return $px*25.4/72;
}

function txtentities($html){
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
}
////////////////////////////////////

class PDF_MC_Table extends FPDF
{
	
//variables of html parser
var $B;
var $I;
var $U;
var $HREF;
var $fontList;
var $issetfont;
var $issetcolor;

function PDF_HTML($orientation='P', $unit='mm', $format='A4')
{
	//Call parent constructor
	$this->FPDF($orientation,$unit,$format);
	//Initialization
	$this->B=0;
	$this->I=0;
	$this->U=0;
	$this->HREF='';
	$this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
	$this->issetfont=false;
	$this->issetcolor=false;
}

function WriteHTML($html)
{
	//HTML parser
	$html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><pre><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
	$html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
	foreach($a as $i=>$e)
	{
		if($i%2==0)
		{
			//Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			else
				$this->Write(5,stripslashes(txtentities($e)));
		}
		else
		{
			//Tag
			if($e[0]=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
				{
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				}
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag, $attr)
{
	//Opening tag
	switch($tag){
		case 'STRONG':
			$this->SetStyle('B',true);
			break;
		case 'EM':
			$this->SetStyle('I',true);
			break;
		case 'B':
		case 'I':
		case 'U':
			$this->SetStyle($tag,true);
			break;
		case 'A':
			$this->HREF=$attr['HREF'];
			break;
		case 'IMG':
			if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
				if(!isset($attr['WIDTH']))
					$attr['WIDTH'] = 0;
				if(!isset($attr['HEIGHT']))
					$attr['HEIGHT'] = 0;
				$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
			}
			break;
		case 'PRE':
                $this->SetFont('Courier','',11);
                $this->SetFontSize(11);
                $this->SetStyle('B',false);
                $this->SetStyle('I',false);
                $this->PRE=true;
                break;
		case 'TR':
		case 'BLOCKQUOTE':
		case 'BR':
			$this->Ln(5);
			break;
		case 'P':
			$this->Ln(10);
			break;
		case 'FONT':
			if (isset($attr['COLOR']) && $attr['COLOR']!='') {
				$coul=hex2dec($attr['COLOR']);
				$this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
				$this->issetcolor=true;
			}
			if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
				$this->SetFont(strtolower($attr['FACE']));
				$this->issetfont=true;
			}
			break;
	}
}

function CloseTag($tag)
{
	//Closing tag
	if($tag=='STRONG')
		$tag='B';
	if($tag=='EM')
		$tag='I';
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
	if($tag=='FONT'){
		if ($this->issetcolor==true) {
			$this->SetTextColor(0);
		}
		if ($this->issetfont) {
			$this->SetFont('arial');
			$this->issetfont=false;
		}
	}
}

function SetStyle($tag, $enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
	{
		if($this->$s>0)
			$style.=$s;
	}
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}

var $widths;
var $aligns;

function Header()
{	
	$fromDate = $_POST["fromDate"];
	$toDate = $_POST["toDate"];
	$repDate = $_POST["repDate"];
	$bPartner= $_POST["bPartner"];
	
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
	while ($rowLines = mssql_fetch_assoc($resultLines)) {
		$saldoTotal += $rowLines["SaldodocMN"];
	}

	$fromDate = date_create(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $fromDate))));
	$toDate = date_create(date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $toDate))));
	$repDate = date_create(date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $repDate))));

	
	$queryCte = "SELECT T1.CardCode, T1.CardName, T1.LicTradNum, T2.Street, T2.Block, T2.City, T2.ZipCode, T2.County, T2.State, T2.Country FROM OCRD T1 INNER JOIN CRD1 T2 ON T1.CardCode = T2.CardCode WHERE T1.CardCode = '$bPartner' AND T1.CardType = 'C'" ;
	$resultCte = mssql_query($queryCte);
	$rowCte = mssql_fetch_array($resultCte);
	
	
	$NumCte = $rowCte["CardCode"];
	$NomCte = $rowCte["CardName"];
	$RFCCte = $rowCte["LicTradNum"];
	$DomCte = $rowCte["Street"].", Col. ".$rowCte["Block"].", ".$rowCte["City"].", ".$rowCte["County"].", C.P. ".$rowCte["ZipCode"].", ".$rowCte["State"].", ".$rowCte["Country"];
	
	
    // Logo
	$company = $_SESSION["company"];
	if ($company == "fg") {
		$this->Image("images/logo-fg.png",10,10,70);
	} elseif ($company == "alianza" || $company == "sureste" || $company == "pacifico") {
		$this->Image("images/logo-alianza.png",10,10,70);
	} elseif ($company == "alianzati") {
		$this->Image("images/logo-alianzati.png",10,10,70);
	} elseif ($company == "mbr") {
		$this->Image("images/logo-mbr.png",10,10,20);
	}
    // Arial bold 20
    $this->SetFont('Narrow','B',18);
    // Move to the right
    //$this->Cell(190,1,' ',0,1);
	//$this->Cell(110);
    // Title
	$this->Cell(98,10,'',0,0,'');
	$this->Cell(98,10,'Estado de Cuenta',0,1,'R'); 
	$this->SetFont('Narrow','B',8); //Titulos
	$this->Cell(98,5,'',0,0,'');
	$this->Cell(32.66,5,utf8_decode(''),0,0,'L');
	$this->Cell(32.66,5,'Saldo al corte',0,0,'L');
	$this->Cell(32.66,5,utf8_decode('Fecha de corte'),0,1,'L');
	$this->SetFont('Narrow','',8);
	$this->Cell(98,5);
	$this->Cell(32.66,5,'',0,0,'L');
	$this->Cell(32.66,5,"$ ".number_format($saldoTotal, 2, '.', ','),0,0,'L');
	$this->Cell(32.66,5,date_format($repDate, 'd M Y'),0,1,'L');
	$this->SetFont('Narrow','B',8); //Titulos
	$this->Cell(98,5,utf8_decode('Cliente'),0,0,'L');
	$this->Cell(32.66,5,'',0,0,'L');
	$this->Cell(32.66,5,utf8_decode('Periodo del'),0,0,'L');
	$this->Cell(32.66,5,utf8_decode('Al'),0,1,'L');
	$this->SetFont('Narrow','',8);
	$this->Cell(98,5,$NomCte,0,0,'L');
	$this->Cell(32.66,5,'',0,0,'L');
	$this->Cell(32.66,5,date_format($fromDate, 'd M Y'),0,0,'L');
	$this->Cell(32.66,5,date_format($toDate, 'd M Y'),0,1,'L');
	$this->SetFont('Narrow','B',8);
	$this->Cell(32.66,5,'No. de cliente',0,0,'L');
	$this->Cell(32.66,5,'RFC',0,0,'L');
	$this->Cell(32.66,5,'',0,1,'L');
	$this->SetFont('Narrow','',8);
	$this->Cell(32.66,5,$NumCte,0,0,'L');
	$this->Cell(32.66,5,$RFCCte,0,0,'L');
	$this->Cell(32.66,5,'',0,1,'L');
	$this->SetFont('Narrow','B',8); //Titulos
	$this->Cell(98,5,'Domicilio',0,1,'L');
	$this->SetFont('Narrow','',8);
	$this->Cell(98,5,$DomCte,0,1,'L');
	$this->Ln(1);
	$this->Cell(196,.1,'',0,1,'',true);
	$this->Ln(3);
}

// Page footer
function Footer() {
	// Position from bottom
	$company = $_SESSION["company"];
    $this->SetY(-5);
	$this->SetX(-215.9);
	$this->SetFillColor(230,94,38);
	$this->SetTextColor(255,255,255);
    if ($company == "fg") {
		$this->SetFillColor(25,48,73);
		$this->Cell(215.9,5,'www.fgelectrical.com',0,0,'C',1);
	} elseif ($company == "alianza" || $company == "sureste" || $company == "pacifico") {
		$this->SetFillColor(230,94,38);
		$this->Cell(215.9,5,'www.alianzaelectrica.com',0,0,'C',1);
	} elseif($company == "alianzati") {
		$this->SetFillColor(0,85,184);
		$this->Cell(215.9,5,'www.alianza-ti.com',0,0,'C',1);
	} elseif ($company == "mbr") {
		$this->SetFillColor(85,187,234);
		$this->Cell(215.9,5,'www.mbrhosting.com',0,0,'C',1);
	}
} 
function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
}
?>