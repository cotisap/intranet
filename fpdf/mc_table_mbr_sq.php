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
	$folioCot = $_REQUEST["idcot"];
	
	$curRates = array();
	$queryCurRates = "SELECT Currency, Rate FROM ORTT WHERE RateDate = CONVERT(date, getdate())";
	$resultCurRates = mssql_query($queryCurRates);
	while ($rowCurRates = mssql_fetch_assoc($resultCurRates)) {
		$curRates[$rowCurRates["Currency"]] = $rowCurRates["Rate"];
	}
	
	//Folio de Cotización
	$query = "SELECT Id_Cot1, Codigo_SN, FechaCreacion, Empl_Ven, company, DocCur FROM COTI WHERE Id_Cot1 = '$folioCot'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	
	$folioCot = $row["Id_Cot1"];
	$date = strtotime($row["FechaCreacion"]);
	$FechaDoc = date("d-m-Y", $date);
	$NumCte = $row["Codigo_SN"];
	$company = $row["company"];
	$DocCur = $row["DocCur"];
		
	//Datos de Cliente
	$isSAP = false;
	$querySapC = "SELECT COUNT(Id_SN) AS quant FROM SONE WHERE Id_SN = '$NumCte' AND company = '".$_SESSION["company"]."'";
	$resultSapC = mysql_query($querySapC);
	$rowSapC = mysql_fetch_assoc($resultSapC);
	$quant = $rowSapC["quant"];
	if ($quant != 0) {
		$isSAP = false;
	} else {
		$isSAP = true;
	}
	
	if ($isSAP) {
		$queryCte = "SELECT T1.CardCode, T1.CardName, T1.LicTradNum, T2.Street, T2.Block, T2.City, T2.ZipCode, T2.County, T2.State, T2.Country FROM OCRD T1 INNER JOIN CRD1 T2 ON T1.CardCode = T2.CardCode WHERE T1.CardCode = '$NumCte'";
		$resultCte = mssql_query($queryCte);
		$rowCte = mssql_fetch_assoc($resultCte);
	} else {
		$queryCte = "SELECT Name_SN AS CardName, RFC AS LicTradNum, Calle AS Street, Colonia AS Block, Ciudad AS City, Municipio AS County, CP As ZipCode, Estado AS State, Pais AS Country FROM SONE WHERE Id_SN = '$NumCte'";
		$resultCte = mysql_query($queryCte);
		$rowCte = mysql_fetch_assoc($resultCte);
	}		
	
	
	//$NumCte = $rowCte["CardCode"];
	$NomCte = $rowCte["CardName"];
	$RFCCte = $rowCte["LicTradNum"];
	$DomCte = $rowCte["Street"].", Col. ".$rowCte["Block"].", ".$rowCte["City"].", ".$rowCte["County"].", C.P. ".$rowCte["ZipCode"].", ".$rowCte["State"].", ".$rowCte["Country"];
	
	
    // Logo
	$this->Image("images/logo-mbr.png",186,10,20);
    $this->SetFont('Helvetica','B',14);
    // Title
	$this->Cell(196,10,utf8_decode('Cotización ').$folioCot,0,1,'C');
	$this->Cell(196,5,'',0,1);
	$this->SetFont('Helvetica','B',8); //Titulos
	$this->Cell(32.66,5,'Cliente',0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(98,5,$NumCte." - ".$NomCte,0,1,'L');
	
	$this->SetFont('Helvetica','B',8);
	$this->Cell(32.66,5,'RFC',0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(32.66,5,$RFCCte,0,1,'L');
	
	$this->SetFont('Helvetica','B',8); //Titulos
	$this->Cell(32.66,5,'Domicilio',0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(163.34,5,$DomCte,0,1,'L');
	
	$this->SetFont('Helvetica','B',8);
	$this->Cell(32.66,5,'Fecha',0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(32.66,5,$FechaDoc,0,0,'L');
	$this->SetFont('Helvetica','B',8);
	$this->Cell(32.66,5,'Moneda',0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(32.66,5,$DocCur,0,0,'L');
	$this->SetFont('Helvetica','B',8);
	$this->Cell(32.66,5,'Tipo de cambio',0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(32.66,5,'$'.number_format($curRates["USD"], 4),0,1,'L');
	
	$this->Ln(2);
}

// Page footer
function Footer() {
	$folioCot = $_REQUEST["idcot"];
	$query = "SELECT company FROM COTI WHERE Id_Cot1 = '$folioCot'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	
	//Folio de Cotización
	$query = "SELECT Empl_Ven FROM COTI WHERE Id_Cot1 = '$folioCot'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	
	$Empleado = $row["Empl_Ven"];
	
	//Query Empleado
	$queryEmpleado = "SELECT SlpName, Email, SlpCode, Telephone, Fax FROM OSLP WHERE SlpCode = $Empleado";
	$resultEmpleado = mssql_query($queryEmpleado);
	$rowEmpleado = mssql_fetch_array($resultEmpleado);
	
	$NomEmpleado = $rowEmpleado["SlpName"];
	$EmailEmpl = $rowEmpleado["Email"];
	if($rowEmpleado["Fax"] != "") {
		$TelEmpl = $rowEmpleado["Telephone"]." Ext. ".$rowEmpleado["Fax"];
	} else {
		$TelEmpl = $rowEmpleado["Telephone"];
	}
	
	$company = $row["company"];
	// Position from bottom
    $this->SetY(-15);
	$this->SetFont('Helvetica','B',8);
	$this->Cell(30,5,'Ejecutivo de cuenta',0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(45,5,utf8_decode($NomEmpleado),0,0,'L');
	$this->SetFont('Helvetica','B',8);
	$this->Cell(12,5,'E-mail',0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(45,5,$EmailEmpl,0,0,'L');
	$this->SetFont('Helvetica','B',8);
	$this->Cell(14,5,utf8_decode('Teléfono'),0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(30,5,$TelEmpl,0,0,'L');
	$this->SetFont('Helvetica','B',8);
	$this->Cell(8,5,utf8_decode('Hoja'),0,0,'L');
	$this->SetFont('Helvetica','',8);
	$this->Cell(32.66,5,$this->PageNo()." de {nb}",0,1,'L');
	$this->SetX(-215.9);
	$this->SetFont('Helvetica','',7);
	$this->SetTextColor(255,255,255);
	$this->SetFillColor(85,187,234);
	$this->Cell(215.9,5,utf8_decode('Cerro de la Estrella 156, P.B., Col. Campestre Churubusco, Del. Coyoacán, Tel. 5363 4040 www.mbrhosting.com'),0,2,'C',1);
	$this->Cell(215.9,5,'',0,0,'C',1);

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
        //$this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a,1);
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