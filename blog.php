<?php
session_start();
if ($_SESSION["admin"] != 'Y') {
	header("Location: no-auth.php");
	die();
}




include 'head.php';

if(!empty($_POST['titulo'])){

  $titulo = $_POST['titulo'];
  $descripcion = $_POST['descripcion'];

  mysql_query("INSERT INTO BLOG(titulo, descripcion, img, autor, company) VALUES ('$titulo', '$descripcion', '3', '4', '5')");

}


?>

<link rel="stylesheet" type="text/css" href="js/dt/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>

<div class="container">
  
  <div class="row">
    <div class="col-md-12">
      <h2>Blog</h2>
      <hr>
    </div>
  </div>

  <div class="row">
    
    <div class="col-md-12">

        <table id="" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>T&iacute;tulo</th>
                        <th>Descripci&oacute;n</th>
                        <th>Img</th>
                        <th>Autor</th>
                        <th>Company</th>
                        <th>Fecha</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                  <?php 
                    $query = "SELECT titulo, descripcion, img, autor, date FROM BLOG WHERE company = '".$_SESSION["company"]."'";
                    $result = mysql_query($query);
                    
                    while($row = mysql_fetch_array($result)) {  
                        echo "<tr>
                                <td>".$row["titulo"]."</td>
                                <td>".utf8_encode($row["descripcion"])."</td>
                                <td>".utf8_encode($row["img"])."</td>
                                <td>".$row["autor"]."</td>
                                <td>".$row["company"]."</td>
                                <td>".$row["date"]."</td>
                                <td align='center'><img src='images/pencil.png' class='viewDetails' data-id=''></td>
                                <td align='center'><img src='images/remove-icon.png' class='removeDetails' data-id=''></td>
                              </tr>";
                    }?>
                </tbody>
          </table>

    </div>

    <hr>  

    <div class="col-md-12">
      <form method="post">
        
        <div class="row">
          
          <div class="col-md-6">

              <div class="form-group">
                <label for="titulo">Titulo:</label>
                <input type="text" class="form-control" id="titulo" name="titulo">
              </div>
              <br>
              <div class="form-group">
                <label for="descripcion">Descripci&oacute;n:</label>
                <textarea class="form-control" rows="5" id="descripcion" name="descripcion"></textarea>
              </div>
              <br>
              <div class="form-group">
                <label for="file">File:</label>
                <input type="file" class="form-control" id="file" name="file">
              </div>
              <br>
              <div class="form-group">
                <input style="width: 150px;" type="submit" name="addNoticia" class="btn" value="Agregar noticia">   
              </div>
          
          </div>

        </div>


         
      </form>
    </div>
  
  </div> 

</div>

<script type="text/javascript">
$(document).ready(function() {
    
    $('table.display').DataTable();




} );
</script>

<?php include 'footer.php'; ?>