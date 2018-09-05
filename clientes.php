<?php
session_start();
if($_SESSION["authenticated_user"]) {
	header("Location: home.php");
	die();
}

?>
<!DOCTYPE HTML>

<html>
<head>

<link class="user" href="/css/style.css" rel="stylesheet" type="text/css" />
<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="content-type" />

<style>
html {
  height: 100%;
}
body {
	background:#FFF url(images/background-login.jpg) no-repeat right center;
	background-size:cover;
	color:#193049;
	margin:0;
	padding:0;
	box-sizing:border-box;
	font-size:14px;
}
a {
	color:#FFF;
	text-decoration:none;
}
input {
	width: 100%;
	margin: 5px 0 15px;
	height: 24px;
    font-size: 14px;
    padding: 0 0 0 6px;
	border: 1px solid #39a6ff;
	background-color: #FFF;
	border-radius: 3px;
	color:#193049;
	box-sizing:border-box;
}
select {
	width:100%;
	font-size:14px;
	margin:5px 0 15px;
	height:24px;
	border: 1px solid #39a6ff;
	border-radius:5px;
}
.button {
    font-size: 14px;
    background-color: #43bdff;
    border: none;
    color: #FFF;
    width: 100%;
    padding: 8px;
	cursor:pointer;
}
.iniLogo {
	display:block;
	width:350px;
	margin-bottom:20px;
}
#content {
	width:350px;
	box-sizing:border-box;
	position:absolute;
	left:80px;
	bottom:15%;
	z-index:100;
}
.login-logo {
	margin:0 auto;
	width:210px;
	display:block;
}
#form {
	background-color:rgba(255,255,255,.50);
	padding: 25px;
	color:#193049;
	box-sizing:border-box;
	border:1px solid #FFF;
}
#footer {
	border:none;
	padding-top:120px;
	position:absolute;
	bottom:0;
	margin:0;
	width:100%;
	box-sizing:border-box;
	background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(0,0,0,.8) 80%);
	background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(0,0,0,.8) 80%);
	background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,rgba(0,0,0,.8) 80%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#000000',GradientType=0 );
}
#footer img {
	height: 15px;
    float: right;
}
.welcome {
	color: #FFF;
    font-size: 53px;
    text-shadow: 0 0 6px #000;
}
@media (max-width: 680px) {
	.iniLogo {
		margin-top:20px;
		width:100%;
	}
	#content {
		margin-top:20px;
		width: calc(100% - 40px);
		left: 20px;
		top: 0;
		transform: translateY(0%);
	}
}
</style>
    </head>
    <body>        
        
<div id="content">
	<span class="welcome">Acceso a Clientes</span><br>

    <div id="form">
        <form action="includes/process_login.php?ctype=c" method="post" name="login_form" class="loginForm">                      
                
        <div style="background-color: #FCF8E3;">
            <span id="error"></span>
        </div> 
        <div>
            <label for="company">
            Empresa</label><br>
            <select id="company" name="company" required>
            	<option value="" selected disabled>Selecciona...</option>
            	<option value="alianza">Alianza Eléctrica</option>
                <option value="sureste">Alianza Eléctrica Sureste</option>
                <option value="pacifico">Alianza Eléctrica Pacífico</option>
                <option value="fg">FG Electrical</option>
            </select>
        </div>
        <div>
            <label for="email">
            E-mail</label><br><input type="text" id="email" name="email" required />
        </div>
        
        <div>
            <label id="passwordLabel" for="password">
            Contraseña</label><br><input type="password" name="password" id="password" required />
        </div>
        
        
        <div>
            <button type="submit" class="button">Entrar</button>
        </div>
        </form>
</div>
</div>

</div>
    
<div id="footer">
	<a href="http://idited.com" target="_blank"><img src="images/desarrollado-por-idited.png"></a>
</div>
        
</body>
</html>