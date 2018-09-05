<?php
session_start();
if ($_SESSION["admin"] != 'Y') {
	header("Location: no-auth.php");
	die();
}

include 'head.php';

$admin = $_REQUEST["admin"];
?>

<script src="/js/jquery-1.4.4.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.7/jquery-ui.js"></script>
<script src="/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/js/jquery.dataTables.rowGrouping.js" type="text/javascript"></script>

<div id="reportContainer">
	<?php 
    switch ($admin) {
        case 'herramientas':
            include "herramientasadmin.php";
            echo "<script type='text/javascript'>
                $(document).ready( function () {
                    $('#toolsBT').addClass('active');
					$('#subHeaderTitle').html('Herramientas');
                });
            </script>";
            break;
        case 'capacitacion':
            include "capacitacionadmin.php";
            echo "<script type='text/javascript'>
                $(document).ready( function () {
                    $('#trainBT').addClass('active');
					$('#subHeaderTitle').html('Capacitaci&oacute;n');
                });
            </script>";
            break;
		case 'eventos':
            include "eventosadmin.php";
            echo "<script type='text/javascript'>
                $(document).ready( function () {
                    $('#eventBT').addClass('active');
					$('#subHeaderTitle').html('Eventos');
                });
            </script>";
            break;
		case 'noticias':
            include "noticiasadmin.php";
            echo "<script type='text/javascript'>
                $(document).ready( function () {
                    $('#newsBT').addClass('active');
					$('#subHeaderTitle').html('Noticias');
                });
            </script>";
            break;
    }
    ?>
</div>
    
<?php include 'footer.php'; ?>