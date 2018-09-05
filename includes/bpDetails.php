<?php
include "mssqlconn.php";

$bpCode = $_GET["bpCode"];

$queryBP = "SELECT T1.CardCode, T1.CardName, T1.LicTradNum, T1.Phone1, T1.IntrntSite, T1.E_Mail FROM OCRD T1 WHERE T1.CardCode = '$bpCode' AND VatStatus = 'Y' AND CardType = 'C'";
$resultBP = mssql_query($queryBP);
$rowBP = mssql_fetch_array($resultBP);

$bpName = $rowBP["CardName"];
$bpRFC = $rowBP["LicTradNum"];
$bpPhone = $rowBP["Phone1"];
$bpEmail = $rowBP["E_Mail"];
$bpWeb = $rowBP["IntrntSite"];

// Contact Person
$queryCP = "SELECT TOP 1 T3.FirstName, T3.LastName, T3.Tel1 cpPhone, T3.E_MailL cpEmail FROM OCPR T3 JOIN OCRD T1 ON T3.CardCode = T1.CardCode WHERE T3.CardCode = $bpCode";
$resultCP = mssql_query($queryCP);
$rowCP = mssql_fetch_array($resultCP);

$cpName = $rowCP["FirstName"]." ".$rowCP["LastName"];
$cpPhone = $rowCP["cpPhone"];
$cpEmail = $rowCP["cpemail"];

// Billing Address
$queryB = "SELECT T2.Street bStreet, T2.Block bCol, T2.City bCity, T2.ZipCode bZip, T2.County bCounty, T4.Name bState, T5.Name bCountry FROM CRD1 T2 JOIN OCRD T1 ON T2.CardCode = T1.CardCode JOIN OCST T4 ON T2.State = T4.Code JOIN OCRY T5 ON T2.Country = T5.Code WHERE T2.CardCode = $bpCode AND T2.Address = 'FISCAL'";
$resultB = mssql_query($queryB);
$rowB = mssql_fetch_array($resultB);

$bpBStreet = $rowB["bStreet"];
$bpBCol = $rowB["bCol"];
$bpBCity = $rowB["bCity"];
$bpBCounty = $rowB["bCounty"];
$bpBState = $rowB["bState"];
$bpBCountry = $rowB["bCountry"];
$bpBZip = $rowB["bZip"];

// Shipping Address
$queryS = "SELECT T2.Street sStreet, T2.Block sCol, T2.City sCity, T2.ZipCode sZip, T2.County sCounty, T4.Name sState, T5.Name sCountry FROM CRD1 T2 JOIN OCRD T1 ON T2.CardCode = T1.CardCode JOIN OCST T4 ON T2.State = T4.Code JOIN OCRY T5 ON T2.Country = T5.Code WHERE T2.CardCode = $bpCode AND T2.Address = 'ENVIO'";
$resultS = mssql_query($queryS);
$rowS = mssql_fetch_array($resultS);

$bpSStreet = $rowS["sStreet"];
$bpSCol = $rowS["sCol"];
$bpSCity = $rowS["sCity"];
$bpSCounty = $rowS["sCounty"];
$bpSState = $rowS["sState"];
$bpSCountry = $rowS["sCountry"];
$bpSZip = $rowS["sZip"];
?>

<div class="fullWidth">
    <div class="thirdFirst">
        Nombre<br>
        <input type="text" id="bpName" name="bpName" readonly value="<?php echo $bpName; ?>">
    </div>
    <div class="thirdSecond">
        C&oacute;digo<br>
        <input type="text" id="bpCode" name="bpCode" readonly value="<?php echo $bpCode; ?>">
    </div>
    <div class="thirdLast">
        RFC<br>
        <input type="text" id="bpRFC" name="bpRFC" readonly value="<?php echo $bpRFC; ?>">
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        Tel&eacute;fono<br>
        <input type="text" id="bpPhone" name="bpPhone" readonly value="<?php echo $bpPhone; ?>">
    </div>
    <div class="thirdSecond">
        E-mail<br>
        <input type="text" id="bpEmail" name="bpEmail" readonly value="<?php echo $bpEmail; ?>">
    </div>
    <div class="thirdLast">
        Website<br>
        <input type="text" id="bpWeb" name="bpWeb" readonly value="<?php echo $bpWeb; ?>">
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        <strong>Persona de contacto</strong>
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        Nombre<br>
        <input type="text" id="cpName" name="cpName" readonly value="<?php echo $cpName; ?>">
    </div>
    <div class="thirdSecond">
        Tel&eacute;fono<br>
        <input type="text" id="cpPhone" name="cpPhone" readonly value="<?php echo $cpPhone; ?>">
    </div>
    <div class="thirdLast">
        E-mail<br>
        <input type="text" id="cpEmail" name="cpEmail" readonly value="<?php echo $cpEmail; ?>">
    </div>
</div>
<!-- Billing Address -->
<div class="fullWidth">
    <div class="thirdFirst">
        <strong>Direcci&oacute;n fiscal</strong>
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        Calle y n&uacute;mero<br>
        <input type="text" id="bpBStreet" name="bpBStreet" readonly value="<?php echo $bpBStreet; ?>">
    </div>
    <div class="thirdSecond">
        Colonia<br>
        <input type="text" id="bpBCol" name="bpBCol" readonly value="<?php echo $bpBCol; ?>">
    </div>
    <div class="thirdLast">
        Ciudad<br>
        <input type="text" id="bpBCity" name="bpBCity" readonly value="<?php echo $bpBCity; ?>">
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        Municipio / Delegaci&oacute;n<br>
        <input type="text" id="bpBCounty" name="bpBCounty" readonly value="<?php echo $bpBCounty; ?>">
    </div>
    <div class="thirdSecond">
        Estado<br>
        <input type="text" id="bpBState" name="bpBState" readonly value="<?php echo $bpBState; ?>">
    </div>
    <div class="thirdLast">
        País<br>
        <input type="text" id="bpBCountry" name="bpBCountry" readonly value="<?php echo $bpBCountry; ?>">
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        C&oacute;digo postal<br>
        <input type="text" id="bpBZip" name="bpBZip" readonly value="<?php echo $bpBZip; ?>">
    </div>
</div>
<!-- Shipping Address -->
<div class="fullWidth">
    <div class="thirdFirst">
        <strong>Direcci&oacute;n de env&iacute;o</strong>
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        Calle y n&uacute;mero<br>
        <input type="text" id="bpSStreet" name="bpSStreet" readonly value="<?php echo $bpSStreet; ?>">
    </div>
    <div class="thirdSecond">
        Colonia<br>
        <input type="text" id="bpSCol" name="bpSCol" readonly value="<?php echo $bpSCol; ?>">
    </div>
    <div class="thirdLast">
        Ciudad<br>
        <input type="text" id="bpSCity" name="bpSCity" readonly value="<?php echo $bpSCity; ?>">
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        Municipio / Delegaci&oacute;n<br>
        <input type="text" id="bpSCounty" name="bpSCounty" readonly value="<?php echo $bpSCounty; ?>">
    </div>
    <div class="thirdSecond">
        Estado<br>
        <input type="text" id="bpSState" name="bpSState" readonly value="<?php echo $bpSState; ?>">
    </div>
    <div class="thirdLast">
        País<br>
        <input type="text" id="bpSCountry" name="bpSCountry" readonly value="<?php echo $bpSCountry; ?>">
    </div>
</div>
<div class="fullWidth">
    <div class="thirdFirst">
        C&oacute;digo postal<br>
        <input type="text" id="bpSZip" name="bpSZip" readonly value="<?php echo $bpSZip; ?>">
    </div>
</div>