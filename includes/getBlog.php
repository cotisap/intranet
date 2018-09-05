<?php 

 ?>

<div class="row">
	
	<div class="col-md-12">
		<h1>Blog</h1>
	</div>

    <?php 
        $query = "SELECT titulo, descripcion, img, autor, date FROM BLOG WHERE company = '".$_SESSION["company"]."'";
       	$result = mysql_query($query);
                    
        while($row = mysql_fetch_array($result)) {  
 
        ?>

		<div class="col-md-12">
			<h2><?php echo $row["titulo"]; ?>Entrada 1</h2>
			<hr>
			<?php echo utf8_encode($row["descripcion"]); ?>
		</div>


        


    <?php

        }
	?>

</div>