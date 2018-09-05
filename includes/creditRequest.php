<?php
session_start();
include "mssqlconn.php";
require '../PHPMailer/PHPMailerAutoload.php';

$bpType = $_POST["bpType"];
$bpCode = $_POST["CardCode"];
$bpName = $_POST["CardName"];

$reqAmmount = $_POST["reqAmmount"];
$reqDays = $_POST["reqDays"];
$facType = $_POST["facType"];
$propValue = $_POST["propValue"];
$monthRent = $_POST["monthRent"];
$sinceRent = $_POST["sinceRent"];
$dueRent = $_POST["dueRent"];
$regNum = $_POST["regNum"];

// Referencias comerciales
$refComRazSoc1 = $_POST["refComRazSoc1"];
$refComCntPrs1 = $_POST["refComCntPrs1"];
$refComTel1 = $_POST["refComTel1"];
$refComRazSoc2 = $_POST["refComRazSoc2"];
$refComCntPrs2 = $_POST["refComCntPrs2"];
$refComTel2 = $_POST["refComTel2"];
$refComRazSoc3 = $_POST["refComRazSoc3"];
$refComCntPrs3 = $_POST["refComCntPrs3"];
$refComTel3 = $_POST["refComTel3"];

// Referencias bancarias
$refBanBan1 = $_POST["refBanBan1"];
$refBanCntPrs1 = $_POST["refBanCntPrs1"];
$refBanTel1 = $_POST["refBanTel1"];
$refBanSuc1 = $_POST["refBanSuc1"];
$refBanCta1 = $_POST["refBanCta1"];
$refBanBan2 = $_POST["refBanBan2"];
$refBanCntPrs2 = $_POST["refBanCntPrs2"];
$refBanTel2 = $_POST["refBanTel2"];
$refBanSuc2 = $_POST["refBanSuc2"];
$refBanCta2 = $_POST["refBanCta2"];

$Comentarios = $_POST["Comentarios"];

$repLegal = $_POST["repLegal"];

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "mail.idited.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 26;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "folium@idited.com";
//Password to use for SMTP authentication
$mail->Password = "Mexico1*";
//Set who the message is to be sent from
$mail->setFrom('folium@idited.com', $_SESSION["name"]." ".$_SESSION["companyName"]);
//Set an alternative reply-to address
$mail->addReplyTo($_SESSION["email"], $_SESSION["name"]." ".$_SESSION["companyName"]);
//Set who the message is to be sent to

$queryEmail = "SELECT code, U_email FROM [@EMLS] WHERE code = 'accounting'";
$resultEmail = mssql_query($queryEmail);
$rowEmail = mssql_fetch_assoc($resultEmail);
$accEmail = $rowEmail["U_email"];

$postedEmail = $accEmail;
$emails = get_all_emails($postedEmail);

/**
 * Extract valid email addresses from string.
 *
 * @param string $postedEmail
 * @return array
 */
function get_all_emails($postedEmail)
{
    $emails = preg_split('/[\s,;\<>]/', $postedEmail);
    foreach($emails as $index => $email) 
    {
        if(! filter_var($email, FILTER_VALIDATE_EMAIL))
            unset($emails[$index]);
    }
    return $emails;
}
//echo '<pre>'; print_r($emails); echo '</pre>';

foreach ($emails as $email) {
    $mail->addAddress($email);
}
//Set the subject line
$mail->Subject = "Solicitud de credito";
//convert HTML into a basic plain-text alternative body
$body = "<h1>Solicitud de cr&eacute;dito</h1>
<h3>Cliente</h3>
<strong>C&oacute;digo:</strong> ".$bpCode."<br>
<strong>Nombre:</strong> ".$bpName."<br>
<h3>Cr&eacute;dito solicitado</h3>
<strong>Monto de cr&eacute;dito solicitado (MN antes de IVA):</strong> $".$reqAmmount."<br>
<strong>Plazo del cr&eacute;dito solictado (d&iacute;as naturales):</strong> ".$reqDays."</br>
<strong>Tipo de domicilio:</strong> ".$facType."<br>";
if ($facType == "Propio") {
	$body.= "<strong>Valor aproximado de la propiedad (MN):</strong> ".$propValue."<br>";
} elseif ($facType == "Rentado") {
	$body.= "<strong>Monto de renta mensual (MN):</strong> ".$monthRent."<br>
			<strong>Antig&uuml;edad de arrendamiento del inmueble:</strong> ".$sinceRent."<br>
			<strong>Vencimiento del contrato de arrendamiento:</strong> ".$dueRent."<br>";
}
$body.= "<strong>Registro de importador n&uacute;mero (si aplica):</strong> ".$regNum."<br>
<h3>Referencias comerciales</h3>
<table border='1'>
	<tr>
		<td><strong>Raz&oacute;n social</strong></td>
		<td><strong>Persona de contacto</strong></td>
		<td><strong>Tel&eacute;fono(s)</strong></td>
	</tr>
	<tr>
		<td>".$refComRazSoc1."</td>
		<td>".$refComCntPrs1."</td>
		<td>".$refComTel1."</td>
	</tr>
	<tr>
		<td>".$refComRazSoc2."</td>
		<td>".$refComCntPrs2."</td>
		<td>".$refComTel2."</td>
	</tr>
	<tr>
		<td>".$refComRazSoc3."</td>
		<td>".$refComCntPrs3."</td>
		<td>".$refComTel3."</td>
	</tr>
</table><br>
<h3>Referencias bancarias</h3>
<table border='1'>
	<tr>
		<td><strong>Instituci&oacute;n</strong></td>
		<td><strong>Persona de contacto</strong></td>
		<td><strong>Tel&eacute;fono(s)</strong></td>
		<td><strong>Sucursal</strong></td>
		<td><strong>Cuenta</strong></td>
	</tr>
	<tr>
		<td>".$refBanBan1."</td>
		<td>".$refBanCntPrs1."</td>
		<td>".$refBanTel1."</td>
		<td>".$refBanSuc1."</td>
		<td>".$refBanCta1."</td>
	</tr>
	<tr>
		<td>".$refBanBan2."</td>
		<td>".$refBanCntPrs2."</td>
		<td>".$refBanTel2."</td>
		<td>".$refBanSuc2."</td>
		<td>".$refBanCta2."</td>
	</tr>
</table><br>
<h3>Comentarios</h3>
".$Comentarios."<br><br>
<strong>Representante legal:</strong> ".$repLegal."<br>
<strong>Solicitante:</strong> ".$_SESSION["salesPrson"]." - ".$_SESSION["name"]."<br>";
$mail->msgHTML($body);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

if ($bpType == "Fisica") {
	$mail->AddAttachment($_FILES['fisIdOficial']['tmp_name'], $_FILES['fisIdOficial']['name']);
	$mail->AddAttachment($_FILES['fisCompDom']['tmp_name'], $_FILES['fisCompDom']['name']);
	$mail->AddAttachment($_FILES['fisCedFis']['tmp_name'], $_FILES['fisCedFis']['name']);
	$mail->AddAttachment($_FILES['fisFormR1']['tmp_name'], $_FILES['fisFormR1']['name']);
	$mail->AddAttachment($_FILES['fisIdOfAval']['tmp_name'], $_FILES['fisIdOfAval']['name']);
} else {
	$mail->AddAttachment($_FILES['morActa']['tmp_name'], $_FILES['morActa']['name']);
	$mail->AddAttachment($_FILES['morCompDom']['tmp_name'], $_FILES['morCompDom']['name']);
	$mail->AddAttachment($_FILES['morEdosFin']['tmp_name'], $_FILES['morEdosFin']['name']);
	$mail->AddAttachment($_FILES['morFormR2']['tmp_name'], $_FILES['morFormR2']['name']);
	$mail->AddAttachment($_FILES['morCedFis']['tmp_name'], $_FILES['morCedFis']['name']);
	$mail->AddAttachment($_FILES['morIdOfAut']['tmp_name'], $_FILES['morIdOfAut']['name']);
	$mail->AddAttachment($_FILES['morIdOfApod']['tmp_name'], $_FILES['morIdOfApod']['name']);
}

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    header("Location: /solCredito.php?msg=sentemail");
}



?>