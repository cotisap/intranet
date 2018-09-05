<?php
include '../head.php';
?>

Generar Pedido
<form method="post" action="GenerarOrder.php" >
<table width="0%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td align="left" class="linkTd"><input type="button" value="Click Me" id="button3"><button type="submit" class="Button">Generar Pedido</button></td>
    </tr>
  </tbody>
</table>
</form>

<script type="text/javascript">

$("#button3").click(function(){
    window.open("GenerarOrder.php");
	window.open("LineOrder.php");
});

</script>

<?php
include '../footer.php';
?>