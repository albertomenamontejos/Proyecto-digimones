<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_user.class.php";


$errorUsuario = "";
$errorPass = "";
if (isset($_GET['errorUsuario'])) {
	$errorUsuario = $_GET['errorUsuario'];
} else if (isset($_GET['errorPass'])) {
	$errorPass = $_GET['errorPass'];
}

?>
<!DOCTYPE html>



<html>
  <head>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="css/user-style.css">
  </head>
<body class="login">
	<div class="container">
	
			<div class="row d-flex flex-column justify-content-center align-content-center  " style="min-height:500px;">
			<h1 class="text-center mb-5 text-white" >DIGIMONES</h1>
			<div class="col-5">
			<form id="Login" action="inicio_usuario.php" method="post">
				<p class="text-center text-white">Por favor introduzca su usuario y contraseña</p>
					<div class="form-group m-3 p-2">

						<input type="text" class="form-control" name="nick"   placeholder="Introduzca su usuario"><?= $errorUsuario ?>
					</div>
					<div class="form-group m-3 p-2">
						<input type="password" class="form-control" name="password" placeholder="Introduzca su contraseña"> <?= $errorPass ?>
					</div>
					<div class="form-group text-center">
					<input type="submit" class="btn btn-warning w-50 " name="btn-enviar" value="Entrar">
					</div>
				</form>

			
			</div>
				
			
			</div>
		</div>
	</div>

</body>
</html>
