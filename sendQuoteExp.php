<?php
include_once "includes/mysqlconn.php";
include_once "includes/mssqlconn.php";

require 'PHPMailer/PHPMailerAutoload.php';

$folioCot = $_REQUEST["idcot"];

// PDF Quote
include "cotExp.php";


$pdfdoc = $pdf->Output('', 'S');


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
$mail->addReplyTo($_SESSION["email"], $_SESSION["companyName"]);
//Set who the message is to be sent to

$postedEmail = $_POST["email"];
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
$mail->Subject = $_POST["subject"];
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($_POST["eMessage"]);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file

//$attachment = chunk_split(base64_encode($pdfdoc));
$comp = "";
if ($_SESSION["company"] == fg) {$comp = "FG Electrical";} else {$comp = "Alianza Electrica";}
$mail->addStringAttachment($pdfdoc, "Cotizacion ".$folioCot." - ".$comp.".pdf");

$totalFiles = count($_FILES["attach"]["tmp_name"]);

for ($i=0; $i<$totalFiles; $i++) {
   $mail->AddAttachment($_FILES["attach"]["tmp_name"][$i], $_FILES["attach"]["name"][$i]);
}

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    header("Location: /vercotizacionexp.php?idCot=".$folioCot."&msg=sentemail");
}
?>
