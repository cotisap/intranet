<?php include 'head.php'; ?>

<?php //if (login_check($mysqli) == true) : ?>

<p>Selecciona la sociedad:</p>

<ul id="socList">
	<li><a href="reportsGenerator.php?company=fg"><img src="images/logo-fg.png"></a></li>
    <li><a href="reportsGenerator.php?company=alianza"><img src="images/logo-alianza.png"></a></li>
</ul>



<?php //else : ?>

<!--script type="text/javascript">
window.location.href = '/no-auth.php';
</script-->

<?php //endif; ?>

<style>
#subHeader, .welcome, .mainNav {
	display:none;
}
</style>
    
<?php include 'footer.php'; ?>