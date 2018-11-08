<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_user.class.php";
require_once "clases/Usuario.class.php";

if(isset($_GET['nick'])){
$nick=$_GET['nick'];
$usuario=Utilidades_user::existe($nick);
$usuario_cad=Utilidades::obj_a_cadenaurl($usuario);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Document</title>
	<style>
	.logo{
		width:100px;
		height:100px;
	}
	</style>
</head>

<body>
		
					
	<div class="container-fluid p-0">

	<?=Utilidades::menu($usuario)?>

	</div>


	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>