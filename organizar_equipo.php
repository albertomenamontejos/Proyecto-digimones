<?php
require_once "clases/Utilidades_user.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Usuario.class.php";
require_once "clases/Digimon.class.php";
$mensajeAñadido = "";
$espacio_equipo = "";
$añadido = "";
$usuario = Utilidades::cadenaurl_a_obj($_POST['usuario_cad']);
$txt_digimones = $usuario->getTxt_dig_usuario();
$txt_equipo = $usuario->getTxt_equipo_usuario();
$directorio = $usuario->getDirectorio_usuario();
if (isset($_POST['otra_pagina']) ) {
	if (!file_exists($txt_equipo)) {
		$fichero = fopen($txt_equipo, "a");
		fclose($fichero);
	}
	$array_equipo = Utilidades_dig::leer_equipo($txt_equipo);
	if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
		$espacio_equipo = true;
	} else {
		$espacio_equipo = false;
	}
}else if (isset($_POST['elegir_digimon'])) {
	//Cuando viene de elegir un digimon
	$dig_elegido=Utilidades::cadenaurl_a_obj($_POST['dig_elegido']);
	$digimon = Utilidades_dig::buscar(DIGIMONES,$dig_elegido);
	$array_equipo = Utilidades_dig::leer_equipo($txt_equipo);
	if (!Utilidades_dig::buscar( $txt_equipo,$digimon)) {
		if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
			Utilidades_dig::guardar($digimon, $txt_equipo, $directorio);
			$array_equipo = Utilidades_dig::leer_equipo($txt_equipo);
			$mensajeAñadido = "<span style='color:green;'>Nuevo Digimon añadido.</span>";
			if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
				$espacio_equipo = true;
			} else {
				$espacio_equipo = false;
			}	
		}	
	} else {
		$añadido = true;
		if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
			$espacio_equipo = true;
		} else {
			$espacio_equipo = false;
		}
	}
} else if (isset($_POST['eliminar_digimon'])) {
	$dig_eliminado=Utilidades::cadenaurl_a_obj($_POST['dig_eliminado']);
	$digimon = Utilidades_dig::buscar($txt_equipo,$dig_eliminado);
	if($digimon!=false){
		Utilidades_dig::sobreescribir($digimon, $txt_equipo,true);
	}

	$array_equipo =Utilidades_dig::leer_equipo($txt_equipo);
	if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
		$espacio_equipo = true;
	} else {
		$espacio_equipo = false;
	}
	
} else {
	$errorUsuario="<span style='color:red'>Ha ocurrido un problema con su cuenta.</span>";
	header("Location:index.php?errorUsuario=$errorUsuario");
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

	<title>Organizar equipo</title>

	
</head>

<body>
<div class="container-fluid p-0">
		<?= Utilidades::menu($usuario) ?>
	</div>
	<div class="container-fluid p-0">
		<section  class="row bg-light " style="min-height:100px;">
			<div class="col-12">
				<div class="col mt-3 text-center">
					<h2 class="text-secondary text-secondary font-italic">Tu equipo</h2>
				</div>
			</div>
	
			<div class="col-12 d-flex justify-content-center align-items-center">
		
			<?php

		for ($i = 0; $i < 3; $i++) {
			if (!empty($array_equipo[$i])) {
				$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
				$dig_eliminado = Utilidades::obj_a_cadenaurl($array_equipo[$i]);


				?>
				<div class='col-3 m-3 pt-2 pb-2 border bg-white text-center'>
				<img class='img-tabla' src='<?=$array_equipo[$i]->getImagen()?>' alt=''>
				<h4 class='titulo pt-2'> <?=$array_equipo[$i]->getNombre() ?></h4>
				<form  method="post" action="organizar_equipo.php">
					<input type="submit" name="eliminar_digimon" value="Quitar del equipo"  class='btn btn-danger w-100' >
					<input type="hidden" name="usuario_cad" value=<?=$usuario_cad?>>	
					<input type="hidden" name="dig_eliminado" value=<?= $dig_eliminado?>>
				</form>
			<?php

				if (isset($_POST['elegir_digimon'])) {
					$dig_elegido=Utilidades::cadenaurl_a_obj($_POST['dig_elegido']);
					if( $array_equipo[$i]->getNombre() == $dig_elegido->getNombre()){
						echo $mensajeAñadido;
					}
				}
			} else {
				echo "<div class='col-3 m-3 pt-5 pb-5 border bg-white text-center'>";
				echo "Escoge a tu digimon abajo";
			}
			echo "</div>";
		}
		?>
	</div>
	<div class='row  w-100 d-flex justify-content-center  text-center'>
	<div class="col">

	<?php
		if (Utilidades_dig::contar_dig($txt_equipo) == 3) {
			$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
			?>
			<form  method="post" action="jugar_partida.php">
				<input type="submit" name="otra_pagina" value="Jugar partida"  class='btn btn-success w-75 mb-3' >
				<input type="hidden" name="usuario_cad" value=<?=$usuario_cad?>>	
			</form>
		<?php
		}
	?>
	</div>

</div>
</section>

<section class="container-fluid m-2">
<div class="d-flex flex-wrap justify-content-center m-3">
	<?php
$digimones = Utilidades_dig::listar($txt_digimones);
for ($i = 0; $i < count($digimones); $i++) {
	$digimon_cad = Utilidades::obj_a_cadenaurl($digimones[$i]);
	if ($digimones[$i]->getNivel() == 3) {
		$evolucion = "<span class='text-success'> NIVEL MÁXIMO </span>";
	} else if ($digimones[$i]->getEvolucion() == null) {
		$evolucion = "<span class='text-danger'> No disponible</span>";
	} else {
		$evolucion = $digimones[$i]->getEvolucion();
	}
	//TODO: CONTADOR DE PARTIDAS QUE QUEDAN PARA DIGIEVOLUCIONAR UN DIGIMON
	echo "<div class='m-2 rounded  border border-dark bg-dark '>";
	echo "<table class='table table-light mt-2 mb-0'>";
	echo "<tr>
	<td class='text-center bg-white ' colspan='2'><img class='img-tabla m-3' src='" . $digimones[$i]->getImagen() . "' alt=''></td></tr>
	<tr><th>Nombre</th><td>" . $digimones[$i]->getNombre() . "</td></tr>
	<tr><th>Ataque</th><td>" . $digimones[$i]->getAtaque() . "</td></tr>
	<tr><th>Defensa</th><td>" . $digimones[$i]->getDefensa() . "</td></tr>
	<tr><th>Tipo</th><td>" . $digimones[$i]->getTipo() . "</td></tr>
	<tr><th>Evoluciona a</th><td>$evolucion</td></tr>
	<tr class='border border-dark border-right-0' ><th >Nivel</th><td >" . $digimones[$i]->getNivel() . "</td></tr>
	</tr>";
	echo "</table>";
	

	if ($espacio_equipo || !file_exists($txt_equipo)){

		if(Utilidades_dig::buscar($txt_equipo,$digimones[$i])){
			 echo "<div class='text-center'><span class='text-success'> Ya está en el equipo </span></div>";
		}else{
			$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
			$dig_elegido = Utilidades::obj_a_cadenaurl($digimones[$i]);
		?>	
			<form  method="post" action="organizar_equipo.php">
			<input type="submit" name="elegir_digimon" value="Añadir al equipo"  class='btn btn-success border-0 rounded-0 w-100' >
			<input type="hidden" name="usuario_cad" value=<?=$usuario_cad?>>	
			<input type="hidden" name="dig_elegido" value=<?=$dig_elegido ?>>
			</form>
		<?php	
		}
	}
	else echo "<div class='text-center'><span class='text-success'> El equipo esta completo </span></div>";
	echo "</div>";
}
?>
	</div>
</section>
</div>
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>