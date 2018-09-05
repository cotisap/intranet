<?php
include 'head.php';

$report = $_REQUEST["report"];

$empl = $_SESSION["salesPrson"];

$isManager = false;

$queryMng = "SELECT SlpCode, SlpName FROM OSLP WHERE Active = 'Y' AND SlpCode > 0 AND U_manager = '$empl'";
$resultMng = mssql_query($queryMng);
if(mssql_num_rows($resultMng) > 0) {
	$isManager = true;
}
?>

<div id="reportContainer">
	<?php 
    switch ($report) {
        case 'antSalCli':
            include "genAntSalCli.php";
            echo "<script type='text/javascript'>
                $(document).ready( function () {
                    $('#antSalCliBT').addClass('active');
					$('#subHeaderTitle').html('Antig&uuml;edad de saldos de clientes');
                });
            </script>";
            break;
        case 'estCom':
            include "genEstCom.php";
            echo "<script type='text/javascript'>
                $(document).ready( function () {
                    $('#estComBT').addClass('active');
					$('#subHeaderTitle').html('Comisiones estimadas');
                });
            </script>";
            break;
		case 'resCom':
            include "genResCom.php";
            echo "<script type='text/javascript'>
                $(document).ready( function () {
                    $('#resComBT').addClass('active');
					$('#subHeaderTitle').html('Resumen de comisiones');
                });
            </script>";
            break;
		case 'edoCta':
            include "genEdoCta.php";
            echo "<script type='text/javascript'>
                $(document).ready( function () {
                    $('#edoCtaBT').addClass('active');
					$('#subHeaderTitle').html('Estado de cuenta');
                });
            </script>";
            break;
    }
    ?>
</div>
    
<?php include 'footer.php'; ?>