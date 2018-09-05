<?php
session_start();
require '../PHPMailer/PHPMailerAutoload.php';

// General Info
$bpName = $_POST["bpName"];
$bpRFC = $_POST["bpRFC"];
$bpPhone = $_POST["bpPhone"];
$bpEmail = $_POST["bpEmail"];
$bpWeb = $_POST["bpWeb"];
$bpType = $_POST["bpType"];

// Billing Address
$bStreet = $_POST["bpBStreet"];
$bCol = $_POST["bpBCol"];
$bCity = $_POST["bpBCity"];
$bCounty = $_POST["bpBCounty"];
$bState = $_POST["bpBState"];
$bCountry = $_POST["bpBCountry"];
$bZip = $_POST["bpBZip"];

// Shipping Address
$sStreet = $_POST["bpSStreet"];
$sCol = $_POST["bpSCol"];
$sCity = $_POST["bpSCity"];
$sCounty = $_POST["bpSCounty"];
$sState = $_POST["bpSState"];
$sCountry = $_POST["bpSCountry"];
$sZip = $_POST["bpSZip"];

// Contact Persons
$cmName = $_POST["cmName"];
$cmPhone = $_POST["cmPhone"];
$cmEmail = $_POST["cmEmail"];
$rdName = $_POST["rdName"];
$rdPhone = $_POST["rdPhone"];
$rdEmail = $_POST["rdEmail"];
$pgName = $_POST["pgName"];
$pgPhone = $_POST["pgPhone"];
$pgEmail = $_POST["pgEmail"];

$Comentarios = $_POST["Comentarios"];

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
$mail->Subject = "Solicitud de Alta de Cliente SAP";
//convert HTML into a basic plain-text alternative body
$body = "<h1>Solicitud de Alta de Cliente SAP</h1>
<h3>Informaci&oacute;n General</h3>
<strong>Nombre o raz&oacute;n social:</strong> ".$bpName."<br>
<strong>RFC:</strong> ".$bpRFC."<br>
<strong>Tel&eacute;fono conmutador:</strong> ".$bpPhone."<br>
<strong>E-mail:</strong> ".$bpEmail."<br>
<strong>Website:</strong> ".$bpWeb."<br>
<strong>Tipo de persona:</strong> ".$bpType."<br>

<h3>Domicilio Fiscal</h3>
<strong>Calle y No.:</strong> ".$bStreet."<br>
<strong>Colonia:</strong> ".$bCol."<br>
<strong>Ciudad:</strong> ".$bCity."<br>
<strong>Municipio / Delegaci&oacute;n:</strong> ".$bCounty."<br>
<strong>Estado:</strong> ".$bState."<br>
<strong>Pa&iacute;s:</strong> ".$bCountry."<br>
<strong>C.P.:</strong> ".$bZip."<br>

<h3>Domicilio de Env&iacute;o</h3>
<strong>Calle y No.:</strong> ".$sStreet."<br>
<strong>Colonia:</strong> ".$sCol."<br>
<strong>Ciudad:</strong> ".$sCity."<br>
<strong>Municipio / Delegaci&oacute;n:</strong> ".$sCounty."<br>
<strong>Estado:</strong> ".$sState."<br>
<strong>Pa&iacute;s:</strong> ".$sCountry."<br>
<strong>C.P.:</strong> ".$sZip."<br>

<h3>Personas de Contacto</h3>
<table border='1'>
	<tr>
		<td></td>
		<td><strong>Nombre</strong></td>
		<td><strong>Tel&eacute;fono</strong></td>
		<td><strong>Email</strong></td>
	</tr>
	<tr>
		<td>Compras</td>
		<td>".$cmName."</td>
		<td>".$cmPhone."</td>
		<td>".$cmEmail."</td>
	</tr>
	<tr>
		<td>Recepci&oacute;n de Documentos</td>
		<td>".$rdName."</td>
		<td>".$rdPhone."</td>
		<td>".$rdEmail."</td>
	</tr>
	<tr>
		<td>Pagos</td>
		<td>".$pgName."</td>
		<td>".$pgPhone."</td>
		<td>".$pgEmail."</td>
	</tr>
</table><br>

<h3>Comentarios</h3>
".$Comentarios."<br><br>

<strong>Solicitante:</strong> ".$_SESSION["salesPrson"]." - ".$_SESSION["name"]."<br>";
$mail->msgHTML($body);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

if ($bpType == "F") {
	$mail->AddAttachment($_FILES['fisIdOficial']['tmp_name'], $_FILES['fisIdOficial']['name']);
	$mail->AddAttachment($_FILES['fisCompDom']['tmp_name'], $_FILES['fisCompDom']['name']);
	$mail->AddAttachment($_FILES['fisCedFis']['tmp_name'], $_FILES['fisCedFis']['name']);
	$mail->AddAttachment($_FILES['fisFormR1']['tmp_name'], $_FILES['fisFormR1']['name']);
} else {
	$mail->AddAttachment($_FILES['morActa']['tmp_name'], $_FILES['morActa']['name']);
	$mail->AddAttachment($_FILES['morCompDom']['tmp_name'], $_FILES['morCompDom']['name']);
	$mail->AddAttachment($_FILES['morFormR2']['tmp_name'], $_FILES['morFormR2']['name']);
	$mail->AddAttachment($_FILES['morCedFis']['tmp_name'], $_FILES['morCedFis']['name']);
}

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    header("Location: /solCliente.php?msg=sentemail");
}



?>