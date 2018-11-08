<?php
require_once "clases/Utilidades_user.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Usuario.class.php";
require_once "clases/Digimon.class.php";


if (isset($_GET['nick'])) {
	$nick = $_GET['nick'];
	$usuario = Utilidades_user::existe($nick);
	$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
	$txt_digimones = $usuario->getTxt_dig_usuario();
	$txt_equipo = $usuario->getTxt_equipo_usuario();
	$espacio_equipo = "";
	$añadido = "";
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
} else {
	$error = "Ha ocurrido un error";
	header("Location: index.php?error=$error");
}


//Cuando viene de elegir un digimon
if (isset($_GET['dig_elegido'])) {
	$digimon = Utilidades_dig::buscar($_GET['dig_elegido'], DIGIMONES);
	$directorio = $usuario->getDirectorio_usuario();
	$txt_equipo = $usuario->getTxt_equipo_usuario();

	if (!Utilidades_dig::buscar($digimon->getNombre(), $txt_equipo)) {
		if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
			Utilidades_dig::guardar($digimon, $txt_equipo, $directorio);
			$array_equipo = Utilidades_dig::leer_equipo($txt_equipo);
			$añadido = "<span style='color:green;'>Nuevo Digimon añadido.</span>";
			if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
				$espacio_equipo = true;
			} else {
				$espacio_equipo = false;
			}
		}
	} else {
		$añadido = "<span style='color:red;'>Ya esta en el equipo</span>";
		if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
			$espacio_equipo = true;
		} else {
			$espacio_equipo = false;
		}

	}


} else if (isset($_GET['dig_eliminado'])) {
	$digimon = Utilidades_dig::buscar($_GET['dig_eliminado'], $txt_equipo);
	Utilidades_dig::sobreescribir($digimon, $txt_equipo, true);
	$array_equipo =Utilidades_dig::leer_equipo($txt_equipo);
	if (Utilidades_dig::contar_dig($txt_equipo) != 3) {
		$espacio_equipo = true;
	} else {
		$espacio_equipo = false;
	}
	$añadido = "";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Organizar equipo</title>
	<style>
		.img-tabla{
			height:200px;
		
		}
		.imagen{
			text-align:center;
		}
		.cajas{
			display:flex;
			flex-flox: row wrap;
		}
		.cajas-digimon{

		}
		.logo{
			width:100px;
			height:100px;
		}
	</style>
	
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
				echo "<div class='col-3 m-3 pt-2 pb-2 border bg-white text-center'>";
				echo "<img class='img-tabla' src='" . $array_equipo[$i]->getImagen() . "' alt=''>";
				echo "<h4 class='titulo pt-2'>" . $array_equipo[$i]->getNombre() . "</h4>";
				echo "<a href='organizar_equipo.php?nick=$nick&dig_eliminado=" . $array_equipo[$i]->getNombre() . "'><button class='btn btn-danger w-100'>Eliminar</button></a>";
				if (isset($_GET['dig_elegido']) && $array_equipo[$i]->getNombre() == $_GET['dig_elegido']) {
					echo $añadido;
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
			echo "<a href='jugar_partida.php?nick=$nick'><button class='btn btn-success w-75 mb-3'>Jugar Partida</button></a>";
		}
	?>
	</div>

</div>
</section>

<section class="container-fluid m-2">
	<div class="cajas">
	<?php
$digimones = Utilidades_dig::listar($txt_digimones);
for ($i = 0; $i < count($digimones); $i++) {
	$digimon_cad = Utilidades::obj_a_cadenaurl($digimones[$i]);
	if ($digimones[$i]->getNivel() == 3) {
		$evolucion = "<span style='color:green;'> NIVEL MÁXIMO </span>";
	} else if ($digimones[$i]->getEvolucion() == null) {
		$evolucion = "<span style='color:red;'> No disponible </span>";
	} else {
		$evolucion = $digimones[$i]->getEvolucion();
	}
	//TODO: CONTADOR DE PARTIDAS QUE QUEDAN PARA DIGIEVOLUCIONAR UN DIGIMON
	echo "<div class='caja-digimon m-2'>";
	echo "<table border ='1'>";
	echo "<tr>
	<td class='imagen' colspan='2'><img class='img-tabla' src='" . $digimones[$i]->getImagen() . "' alt=''></td></tr>
	<tr><th>Nombre</th><td>" . $digimones[$i]->getNombre() . "</td></tr>
	<tr><th>Ataque</th><td>" . $digimones[$i]->getAtaque() . "</td></tr>
	<tr><th>Defensa</th><td>" . $digimones[$i]->getDefensa() . "</td></tr>
	<tr><th>Tipo</th><td>" . $digimones[$i]->getTipo() . "</td></tr>
	<tr><th>Evoluciona a</th><td>$evolucion</td></tr>
	<tr><th>Nivel</th><td>" . $digimones[$i]->getNivel() . "</td></tr>
	</tr>";
	echo "</table>";

	if ($espacio_equipo || !file_exists($txt_equipo))
		echo "<div class='text-center'><a href='organizar_equipo.php?nick=$nick&dig_elegido=" . $digimones[$i]->getNombre() . "'><button class='btn btn-success border-0 rounded-0 w-100'>Añadir al equipo</button></a></div>";
	else echo "<div class='text-center'><span style='color:blue;'> El equipo esta completo </span></div>";
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