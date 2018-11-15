<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_user.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Usuario.class.php";



if(isset($_POST['otra_pagina'])){
	$usuario = Utilidades::cadenaurl_a_obj($_POST['usuario_cad']);
	$ruta_txt=$usuario->getTxt_dig_usuario();
}else{
	$errorUsuario="<span style='color:red'>Ha ocurrido un problema con su cuenta.</span>";
	header("Location:index.php=errorUsuario=$errorUsuario");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/user-style.css">
	<title>Mis digimons</title>

</head>
<body class="ver-mis-digimon">
	<div class="container-fluid p-0">

		<?= Utilidades::menu($usuario) ?>
	</div>
	<div class="container-fluid p-0">
	<div class="d-flex flex-wrap justify-content-center m-3">

		<?php

			
	$digimones = Utilidades_dig::listar($ruta_txt);

	for ($i = 0; $i < count($digimones); $i++) {
		$digimon_cad = Utilidades::obj_a_cadenaurl($digimones[$i]);
		if ($digimones[$i]->getNivel()==3) {
			$evolucion = "<span style='color:green;'> NIVEL MÁXIMO </span>";
		} else if($digimones[$i]->getEvolucion()==null){
			$evolucion="<span style='color:red;'> No disponible </span>";
		}else{
			$evolucion = $digimones[$i]->getEvolucion();
		}
		//TODO: CONTADOR DE PARTIDAS QUE QUEDAN PARA DIGIEVOLUCIONAR UN DIGIMON
		echo "<div class='m-2 rounded  border border-dark bg-dark '>";
		echo "<table class='table table-light mt-2 mb-2'>";
		echo "<tr>
		<td class='text-center bg-white ' colspan='2'><img class='img-tabla m-3' src='" . $digimones[$i]->getImagen() . "' alt=''></td></tr>
		<tr><th>Nombre</th><td>" . $digimones[$i]->getNombre() . "</td></tr>
		<tr><th>Ataque</th><td>" . $digimones[$i]->getAtaque() . "</td></tr>
		<tr><th>Defensa</th><td>" . $digimones[$i]->getDefensa() . "</td></tr>
		<tr><th>Tipo</th><td>" . $digimones[$i]->getTipo() . "</td></tr>
		<tr><th>Evoluciona a</th><td>$evolucion</td></tr>
		<tr><th>Nivel</th><td>" . $digimones[$i]->getNivel() . "</td></tr>
		</tr>";
		echo "</table>";
		echo "</div>";
	}


	?>
	</div>
	


</div>


	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>