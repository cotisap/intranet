<?php
$company = $_POST["company"];
$cType = $_REQUEST["ctype"];
include_once 'mssqlconn.php';

session_start();
 
if (isset($_POST["company"], $_POST["email"], $_POST["password"]) && $_POST["company"] != "" && $_POST["email"] != "" && $_POST["password"] != "") {
    $email = $_POST["email"];
    $password = $_POST["password"];
	$company = $_POST["company"];
	
	if($cType == "c") {
		$query = "SELECT TOP 1 CardName, ValidFor, CardCode, E_Mail, Phone1, Password, (SELECT CompnyName FROM OADM) CompanyName, ListNum FROM OCRD WHERE E_Mail = '$email'";
	} else {
		$query = "SELECT TOP 1 SlpName, Active, SlpCode, Email, Telephone, Commission, U_pass, U_admin, U_branch, U_priceList, U_export, U_discounts, (SELECT CompnyName FROM OADM) CompanyName FROM OSLP WHERE Email = '$email'";
	}
	$result = mssql_query($query);
	
	if ($row = mssql_fetch_assoc($result)) {
		if($cType == "c") {
			$pass = $row["Password"];
			$active = $row["ValidFor"];
		} else {
			$pass = $row["U_pass"];
			$active = $row["Active"];
		}
		if ($active == "N") {
			if ($cType == "c") {
				header('Location: ../clientes.php?error=noactive');
			} else {
				header('Location: ../index.php?error=noactive');
			}
			
		} else {
			if ($password == $pass) {
				// Login success
				$_SESSION["authenticated_user"] = true;
				$_SESSION['timeout'] = time();
				$_SESSION["email"] = $email;
				if ($cType == "c") {
					$_SESSION["isBP"] = true;
					$_SESSION["phone"] = $row["Phone1"];
					$_SESSION["name"] = $row["CardName"];
					$_SESSION["customer"] = $row["CardCode"];
					$_SESSION["company"] = $company;
					$_SESSION["companyName"] = $row["CompanyName"];
					$_SESSION["priceList"] = $row["ListNum"];
					$_SESSION["admin"] = "BP";
				} else {
					$_SESSION["phone"] = $row["Telephone"];
					$_SESSION["name"] = $row["SlpName"];
					$_SESSION["branch"] = $row["U_branch"];
					$_SESSION["priceList"] = $row["U_priceList"];
					$_SESSION["commission"] = $row["Commission"];
					$_SESSION["salesPrson"] = $row["SlpCode"];
					$_SESSION["company"] = $company;
					$_SESSION["companyName"] = $row["CompanyName"];
					$_SESSION["admin"] = $row["U_admin"];
					$_SESSION["export"] = $row["U_export"];
					$_SESSION["discounts"] = $row["U_discounts"];
				}
				switch ($company) {
					case 'mbr':
						$_SESSION["FatherNum"] = '11100000';
						break;
					case 'fg':
					case 'alianza':
					case 'sureste':
					case 'pacifico':
					case 'alianzati':
					case 'manufacturing':
						$_SESSION["FatherNum"] = '1120-000-000';
						break;
				}
				header('Location: ../home.php');
			} else {
				// Login failed
				if ($cType == "c") {
					header('Location: ../clientes.php?error=badlogin');
				} else {
					header('Location: ../index.php?error=badlogin');
				}
				
			}
		}
	} else {
		if ($cType == "c") {
			header('Location: ../clientes.php?error=nosuchuser');
		} else {
			header('Location: ../index.php?error=nosuchuser');
		}
		
	}
	//OSLP
	

} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}