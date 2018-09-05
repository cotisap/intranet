<?php
require_once("includes/class.compressor.php");
$compressor = new compressor('css,javascript,page');

session_start();

$timeout = 28800;
if(isset($_SESSION['timeout'])) {
    $duration = time() - (int)$_SESSION['timeout'];
    if($duration > $timeout) {
        session_destroy();
        session_start();
    }
}
$_SESSION['timeout'] = time();

if($_SESSION["authenticated_user"] != true) {
	header("Location: index.php");
	die();
}

include_once "includes/mssqlconn.php";
include_once "includes/mysqlconn.php";

header('Content-Type: text/html; charset=ISO-8859-1');
date_default_timezone_set('America/Mexico_City');
?>
<!doctype html>
<html>
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Intranet - <?php echo $_SESSION["companyName"]; ?>
</title>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap-grid.min.css">
<link href="/css/style.css" rel="stylesheet" type="text/css" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-2.0.0.min.js"></script>

<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<script src="/js/modernizr.js"></script><!-- Modernizr -->


<!-- Validate -->
<script src="/js/jquery.validate.min.js"></script>
<script src="/js/additional-methods.min.js"></script>
<!-- Add multiemail method -->
<script>
jQuery.validator.addMethod(
    "multiemail",
     function(value, element) {
         if (this.optional(element)) // return true on optional element 
             return true;
         var emails = value.split(/[;,]+/); // split element by , and ;
         valid = true;
         for (var i in emails) {
             value = emails[i];
             valid = valid &&
                     jQuery.validator.methods.email.call(this, $.trim(value), element);
         }
         return valid;
     },
    jQuery.validator.messages.email
);
</script>

<!-- Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

<script src="https://use.fontawesome.com/2cc2963e4c.js"></script>
<!-- Theme -->
<link href="/css/themes/<?php
						switch ($_SESSION["company"]) {
							case "alianza":
							case "sureste":
							case "pacifico":
								echo "alianza";
								break;
							case "fg":
								echo "fg";
								break;
							case "alianzati":
								echo "alianzati";
								break;
							case "mbr":
								echo "mbr";
								break;
							case "manufacturing";
								echo "manufacturing";
								break;
						}
						?>.css" rel="stylesheet" type="text/css" />
</head>
<?php flush(); ?>
<body>

<div id="mainWrapper">
    <div id="navigation">
        <?php include_once 'navigation.php'; ?>
    </div>
    
    <div id="contentWrapper">
    <?php include_once 'header.php'; ?>