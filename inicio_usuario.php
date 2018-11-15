<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_user.class.php";
require_once "clases/Usuario.class.php";

if (isset($_POST['nick'])) {

	$nick = $_POST['nick'];
	if (!$usuario = Utilidades_user::existe($nick)) {
		$errorUsuario = "<span style='color:red'>No se ha encontrado ningun usuario con ese nick.</span>";
		header("Location:index.php?errorUsuario=$errorUsuario");
	} else if (!Utilidades_user::comprobar_pass($_POST['password'])) {
		$errorPass = "<span style='color:red'>Contraseña inválida</span>";
		header("location: index.php?errorPass=$errorPass");
	}

} else if ($_POST['otra_pagina']) {
	$usuario = Utilidades::cadenaurl_a_obj($_POST['usuario_cad']);
} else {
	$errorUsuario = "<p>Ha ocurrido un error</p>";
	header("Location: index.php?error=$errorUsuario");
}

$partidas=Utilidades_user::leerPartidas($usuario);

?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/user-style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<title>Inicio</title>
	<style>
	.header-icon{
		width:25%;
		position:absolute;
		min-height:50px;
		bottom:105px;
		left:10px;
	}

	</style>
</head>

<body>
		
					
	<div class="container-fluid p-0">
	
	<?= Utilidades::menu($usuario) ?>

	<div class="row p-5">
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats">
				<div class="card-header bg-success">
				<div class="card-icon header-icon rounded bg-success text-center border border-light text-white d-flex justify-content-center align-items-center">
					<div><img src="img/como.png" alt=""></div>
				</div>
				<h3 class="card-title text-center text-white"><?=$partidas[0]?></h3>
				</div>
				<div class="card-footer">
				<div class="stats">
				<h4 class="card-category">PARTIDAS GANADAS</h4>
				</div>
				</div>
			</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats">
				<div class="card-header bg-danger">
				<div class="card-icon header-icon rounded bg-danger text-center border border-light text-white d-flex justify-content-center align-items-center">
					<div><img src="img/aversion.png" alt=""></div>
				</div>
				<h3 class="card-title text-center text-white"><?=$partidas[1]?></h3>
				</div>
				<div class="card-footer">
				<div class="stats">
				<h4 class="card-category">PARTIDAS PERDIDAS</h4>
				</div>
				</div>
			</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats">
				<div class="card-header bg-warning">
				<div class="card-icon header-icon rounded bg-warning text-center border border-light text-white d-flex justify-content-center align-items-center">
					<div><img src="img/empate.png" alt=""></div>
				</div>
				<h3 class="card-title text-center text-white"><?=$partidas[2]?></h3>
				</div>
				<div class="card-footer">
				<div class="stats">
				<h4 class="card-category">PARTIDAS EMPATE</h4>
				</div>
				</div>
			</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats">
				<div class="card-header bg-info">
				
				<h3 class="card-title text-center text-white"><?=$partidas[3]?></h3>
				</div>
				<div class="card-footer">
				<div class="stats">
				<h4 class="card-category">EVOLUCIONES DISPONIBLES</h4>
				</div>
				</div>
			</div>
			</div>
	</div>
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>