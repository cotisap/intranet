<?php
echo "SBO PHP TEST<br>";
$vCmp = new COM("SAPbobsCOM.PERPETUA") or die ("No connection");
$vCmp->server = "(138.94.140.25:1433)";
$vCmp->CompanyDB = "INTRANET_FG_AL";
$vCmp->LicenseServer = "138.94.140.25:1433";
$vCmp->username = "sa";
$vCmp->password = "Alianza123$";
$vCmp->DbServerType(PERPETUA.BoDataServerTypes.dst_MSSQL2008);
$lRetCode = $vCmp->Connect;
echo $vCmp->CompanyName;
echo '<br>';
$vItem = $vCmp->GetBusinessObject(oItems);
$RetVal = $vItem->GetByKey("A1010");
echo '$vItem->Itemname';
echo '<br><br>Ready';
?>