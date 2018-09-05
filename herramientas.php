<?php
include 'head.php';
?>

<p class="pageTitle">Herramientas</p>

<div class="docsWrapper">
	<ul>
    <?php
	$query = "SELECT T1.id, T1.title, T1.file, T1.remarks, T2.category FROM TOOL T1 INNER JOIN TCAT T2 ON T1.category = T2.id WHERE T1.active = 'Y'";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$file = $row["file"];
		$ext = substr($file, strrpos($file,'.'), strlen($file)-1);
		$icon = "";
		switch ($ext) {
			case ".docx":
			case ".doc":
				$icon = "word.png";
				break;
			case ".xlsx":
			case ".xls":
				$icon = "excel.png";
				break;
			case ".ppt":
			case ".pptx":
				$icon = "powerpoint.png";
				break;
			case ".jpg":
			case ".png":
				$icon = "image.png";
				break;
			case ".pdf":
				$icon = "pdf.png";
				break;
		}
		echo "<li><a href='http://folium.idited.com/ftp/herramientas/".$row["file"]."' target='_blank'><img src='images/".$icon."'><p class='title'>".$row["title"]."</p><p class='desc'>".$row["remarks"]."</p><p class='catNote'>".$row["category"]."</p></a></li>";
	}
	?>
    </ul>
</div>

<script>
$("p.desc").text(function (_, text) {
    return $.trim(text).substring(0, 100)+"...";
});
</script>


<?php include 'footer.php'; ?>