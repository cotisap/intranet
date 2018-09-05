<?php
include_once "includes/mysqlconn.php";
include_once "includes/mssqlconn.php";

require 'PHPMailer/PHPMailerAutoload.php';

$folioCot = $_REQUEST["idcot"];


// Save payments
$allowed_filetypes = array('.jpg','.png','.pdf'); // These will be the types of file that will pass the validation.
$max_filesize = 1000000; // Maximum filesize in BYTES (currently 1MB).
$upload_path = "ftp/pagos/"; // The place the files will be uploaded to (currently a 'files' directory).

$emlBodyP = "";

if (count($_FILES["file"]) > 0) {
	foreach ($_FILES['file']['name'] as $key => $value) {
		$pMethod = $_POST["pMethod"][$key];
		$ammount = $_POST["ammount"][$key];
		$pCurrency = $_POST["pCurrency"][$key];
		$pRef = $_POST["ref"][$key];
		$pDate = date('Y-m-d', strtotime(str_replace('/', '-', $_POST["pDate"][$key])));
		
		$filename = $_FILES['file']['name'][$key]; // Get the name of the file (including file extension).
		$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
		
		// Check if the filetype is allowed, if not DIE and inform the user.
		if(!in_array($ext,$allowed_filetypes))
		  die('The file you attempted to upload is not allowed.');
		
		// Now check the filesize, if it is too large then DIE and inform the user.
		if(filesize($_FILES['file']['tmp_name'][$key]) > $max_filesize)
		  die('The file you attempted to upload is too large.');
		
		// Check if we can upload to the specified path, if not DIE and inform the user.
		if(!is_writable($upload_path))
		  die('You cannot upload to the specified directory, please CHMOD it to 777.');
		
		$newName = $idCot."-".round(microtime(true)).$filename; 
		
		// Upload the file to your specified path.
		if(move_uploaded_file($_FILES['file']['tmp_name'][$key],$upload_path . $newName)) {
			$queryP = "INSERT INTO PMNT (ref_q, ref_p, method, ammount, currency, date, file, status) VALUES ('$folioCot', '$pRef', '$pMethod', $ammount, '$pCurrency', '$pDate', '$newName', 'W')";
			
			$resultP = mysql_query($queryP);
			if(!$resultP) {
				die('Could not enter data PMNT: ' . mysql_error());
			}
			$emlBodyP.= "<p>Metodo: ".$pMethod."</p><p>Monto: ".$ammount."</p><p>Moneda: ".$pCurrency."</p><p>Referencia: ".$pRef."</p><p>Fecha de pago: ".$pDate."</p><p>Comprobante de pago: ".$filename."</p>";
			//echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
		} else {
			 echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
		}
	}
}

$queryEmail = "SELECT code, U_email FROM [@EMLS] WHERE code = 'accounting'";
$resultEmail = mssql_query($queryEmail);
$rowEmail = mssql_fetch_assoc($resultEmail);
$pmntEmail = $rowEmail["U_email"];


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

$postedEmail = $pmntEmail;
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
$mail->Subject = "Solicitud de validacion de pago Cot.: ".$folioCot;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML("Favor de validar los siguientes pagos en SAP".$emlBodyP);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file

//$attachment = chunk_split(base64_encode($pdfdoc));
//$mail->addStringAttachment($file, $file);

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    header("Location: /vercotizacion.php?idCot=".$folioCot."&msg=valpago");
}
?>
