<?php
session_start();



$timeout = 1800; // Number of seconds until it times out.
 
// Check if the timeout field exists.
if(isset($_SESSION['timeout'])) {
    // See if the number of seconds since the last
    // visit is larger than the timeout period.
    $duration = time() - (int)$_SESSION['timeout'];
    if($duration > $timeout) {
        // Destroy the session and restart it.
        session_destroy();
        session_start();
    }
}
 
// Update the timout field with the current time.
$_SESSION['timeout'] = time();


if($_SESSION['authenticated_user'] != true) {
	echo "<p>No session</p>";
	//header('Location: index.php');
	//die();
} else {
	echo "You are logged in!";
}

?>
<html>
<head>
<script src="http://code.jquery.com/jquery-2.0.0.min.js"></script>
</head>
<body>
<p>P&aacute;gina de prueba</p>

<?php
date_default_timezone_set('America/Mexico_City');
echo date('c')."<br>";
echo "E-mail: ".$_SESSION['email']." <br>";
echo "Name: ".$_SESSION['name']." <br>";
echo "Emp Code: ".$_SESSION['salesPrson']." <br>";
echo "Branch: ".$_SESSION['branch']." <br>";
echo "Price List: ".$_SESSION['priceList']." <br>";
echo "Commission: ".$_SESSION['commission']." <br>";
echo "Company: ".$_SESSION['company']." <br>";
echo "Is admin: ".$_SESSION['admin']." <br>";



?>

<form action="" method="get">

<input type="submit" name="sb" value="One">
<input type="submit" name="sb" value="Two">
<input type="submit" name="sb" value="Three">


<select id="prueba">
	<option value="1">Uno</option>
    <option value="2">Dos</option>
    <option value="3">Tres</option>
    <option value="4">Cuatro</option>
    <option value="5">Cinco</option>
</select>

</form>


<p><a href="includes/logout.php">Salir</a></p>

<script>

$("#prueba").on("change", function() {
	alert($(this).val());
});

</script>

</body>
</html>