<?php ob_start() ?>
<?php
$target_dir = "images/";
$target_file = $target_dir . basename("fondo.jpg") ;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["imglogin"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}


if ($_FILES["imglogin"]["size"] > 10000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";

} else {
    if (move_uploaded_file($_FILES["imglogin"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["imglogin"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$host = $_SERVER['HTTP_HOST'];
$ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$html = 'genadmin.php';
$url = "http://$host$ruta/$html";
header("Location: $url");
exit;

?>
<?php ob_end_flush();?>