           <?php
	 include "../includes/mysqlconn.php";
	  include "../includes/mssqlconn.php";
			  $buscar = $_POST['b'];
				   
				  if(!empty($buscar)) {
						buscar($buscar);
				  }
				   
				  function buscar($b) {
						$myQuery = mssql_query("SELECT TOP 10 ItemCode, ItemName FROM OITM  where QryGroup1 ='Y' and ItemName LIKE '%".$b."%' ");
						$contar = mysql_num_rows($myQuery);
						 
						if($contar == 0){
							  echo "No se han encontrado resultados para '<b>".$b."</b>'.";
						}else{
							  while($row=mysql_fetch_array($myQuery)){
									$nombre = $row['ItemName'];
									$id = $row['ItemCode'];
									 
									echo $id." - ".$nombre."<br /><br />";    
							  }
						}
				  }
				   
			?>