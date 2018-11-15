<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_user.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Usuario.class.php";

if (isset($_POST['otra_pagina'])) {
	$usuario = Utilidades::cadenaurl_a_obj($_POST['usuario_cad']);
} else if (isset($_POST['btn-digievolucionar'])) {
	$usuario = Utilidades::cadenaurl_a_obj($_POST['usuario_cad']);
	$digimon = Utilidades::cadenaurl_a_obj($_POST['digimon_cad']);
	 //Buscamos si el digimon que digievolucionara esta en el equipo o no
	if (!Utilidades_dig::buscar($usuario->getTxt_dig_usuario(), false, $digimon->getEvolucion())) {//Por si recarga la página
		Utilidades_dig::digievolucionar($digimon, $usuario);
		$fichero = fopen($usuario->getTxt_registro(), "r");
		$tamaño = filesize($usuario->getTxt_registro());
		$registro = explode("\n", fread($fichero, $tamaño));
		$ganadas = $registro[0];
		$perdidas = $registro[1];
		$empatadas = $registro[2];
		$digievoluciones = $registro[3];
		$digievoluciones--;
		fclose($fichero);
		$fichero = fopen($usuario->getTxt_registro(), "w");
		fwrite($fichero, "$ganadas\n$perdidas\n$empatadas\n$digievoluciones");
		fclose($fichero);
	}

} else {
	$errorUsuario = "<p>Ha ocurrido un error</p>";
	header("Location: index.php?error=$errorUsuario");
}
$partidas = Utilidades_user::leerPartidas($usuario);
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Digievolucionar</title>
	<link rel="stylesheet" href="css/user-style.css">
</head>

<body>
<?= Utilidades::menu($usuario) ?>
	<div class="container-fluid p-0 ">
		
		<div class="row pt-3 ">
			<div class="col-3 p-5 text-center ">
				<div class="border border-0 card card-stats">
					<div class="card-header bg-info">
					<h3 class="card-title text-center text-white"><?= $partidas[3] ?></h3>
					</div>
					<div class="card-footer">
						<div class="stats">
						<h4 class="card-category">EVOLUCIONES DISPONIBLES</h4>
						</div>
					</div>
				</div>
			</div>
<?php
//Mostrar el digimon que acaba de evolucionar y recordar ir a reorganizar equipo
if (isset($_POST['btn-digievolucionar'])) {
	?>

		<div class="col-9 pb-3">
			<div class="text-center">
				<h3>DIGIEVOLUCIÓN REALIZADA</h3>
			</div>
			<div class="row d-flex flex-row justify-content-center">
				<div class="col-4">
				<?php

			if ($digimon->getNivel() == 3) {
				$evolucion = "<span class='text-success'> NIVEL MÁXIMO </span>";
			} else {
				$evolucion = $digimon->getEvolucion();
			}

			?>
					<table class='table table-light m-0  '>
						<tr>
						<td class='text-center bg-white ' colspan='2'><img class='img-tabla ' src='<?= $digimon->getImagen() ?>' alt=''></td></tr>
						<tr><th>Nombre</th><td><?= $digimon->getNombre() ?></td></tr>
						<tr><th>Ataque</th><td><?= $digimon->getAtaque() ?></td></tr>
						<tr><th>Defensa</th><td><?= $digimon->getDefensa() ?></td></tr>
						<tr><th>Tipo</th><td><?= $digimon->getTipo() ?></td></tr>
						<tr><th>Evoluciona a</th><td><?= $evolucion ?></td></tr>
						<tr ><th >Nivel</th><td ><?= $digimon->getNivel() ?> </td></tr>
						</tr>
					</table>
				</div>
				<div class="row d-flex flex-column flex-wrap justify-content-center mt-5">
				<div class="col mt-5"><img src="img/flecha.png" alt=""><img src="img/flecha.png" alt=""><img src="img/flecha.png" alt="">
					<img src="img/flecha.png" alt=""></div>
				<div class="col"><img src="img/flecha.png" alt=""><img src="img/flecha.png" alt=""><img src="img/flecha.png" alt="">
					<img src="img/flecha.png" alt=""></div>
				</div>

				<div class="col-4">
				<?php
			$digievolucionar = Utilidades_dig::buscar(DIGIMONES, false, $digimon->getEvolucion());
			if ($digievolucionar->getNivel() == 3) {
				$evolucion = "<span class='text-success'> NIVEL MÁXIMO </span>";
			} else {
				$evolucion = $digimon->getEvolucion();
			}
			?>
					<table class='table table-light m-0  '>
						<tr>
						<td class='text-center bg-white ' colspan='2'><img class='img-tabla ' src='<?= $digievolucionar->getImagen() ?>' alt=''></td></tr>
						<tr><th>Nombre</th><td><?= $digievolucionar->getNombre() ?></td></tr>
						<tr><th>Ataque</th><td><?= $digievolucionar->getAtaque() ?></td></tr>
						<tr><th>Defensa</th><td><?= $digievolucionar->getDefensa() ?></td></tr>
						<tr><th>Tipo</th><td><?= $digievolucionar->getTipo() ?></td></tr>
						<tr><th>Evoluciona a</th><td><?= $evolucion ?></td></tr>
						<tr ><th >Nivel</th><td ><?= $digievolucionar->getNivel() ?> </td></tr>
						</tr>
					</table>
				</div>
			</div>
			<form action="organizar_equipo.php" method="POST">
			<?php $usuario_cad = Utilidades::obj_a_cadenaurl($usuario); ?>
			<input type="hidden" name="usuario_cad" value="<?= $usuario_cad ?>">
			<div class="text-center m-3">	<input type="submit" name="otra_pagina" class="btn btn-success  w-25  mt-2 " value="Reoganizar equipo"></div>
			
			</form>
		</div>

<?php

}
?>
	<section class="container-fluid m-2 ">
	<div class="row d-flex justify-content-center m-0 p-3 border border-info rounded bg-light">	

<?php
$imprimido = false;
$imprimido2 = false;
$txt_digimones = $usuario->getTxt_dig_usuario();
$digimones = Utilidades_dig::listar($txt_digimones);
for ($i = 0; $i < count($digimones); $i++) {
	$digimon_cad = Utilidades::obj_a_cadenaurl($digimones[$i]);
	if ($digimones[$i]->getNivel() == 3) {
		$evolucion = "<span class='text-success'> NIVEL MÁXIMO </span>";
	} else {
		$evolucion = $digimones[$i]->getEvolucion();
	}

	if ($partidas[3] != 0) {
		$imprimido = true;
		if ($digimones[$i]->getEvolucion() != null && !Utilidades_dig::buscar($usuario->getTxt_dig_usuario(), false, $digimones[$i]->getEvolucion())) {
			$imprimido2 = true;
			?>
			<div class='col-3 info-d rounded  border border-info bg-white mw-100 m-0 p-3'>
			<table class='table table-light m-0  '>
			<tr>
	<td class='text-center bg-white ' colspan='2'><img class='img-tabla ' src='<?= $digimones[$i]->getImagen() ?>' alt=''></td></tr>
	<tr><th>Nombre</th><td><?= $digimones[$i]->getNombre() ?></td></tr>
	<tr><th>Ataque</th><td><?= $digimones[$i]->getAtaque() ?></td></tr>
	<tr><th>Defensa</th><td><?= $digimones[$i]->getDefensa() ?></td></tr>
	<tr><th>Tipo</th><td><?= $digimones[$i]->getTipo() ?></td></tr>
	<tr><th>Evoluciona a</th><td><?= $evolucion ?></td></tr>
	<tr ><th >Nivel</th><td ><?= $digimones[$i]->getNivel() ?> </td></tr>
	</tr>
	</table>
			<?php
		$digimon_cad = Utilidades::obj_a_cadenaurl($digimones[$i]);
		$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
		?>
			<form action="digievolucionar.php" method="POST">
			<input type="hidden" name="usuario_cad" value="<?= $usuario_cad ?>">
				<input type="hidden" name="digimon_cad" value="<?= $digimon_cad ?>">
				<input type="hidden" name="digimon_elegido" value=<?= Utilidades::obj_a_cadenaurl($digimones[$i]) ?>>
				<input type="submit" name="btn-digievolucionar" class="btn btn-success border border-0 w-100 rounded-0 mt-2" value="Digievolucionar">
			</form>
			</div>
			<?php

	}
}

}

if (!$imprimido) {
	?>
<div class='row m-2 border border-info rounded  text-center p-3 text-info   '>
	<div class="col p-5 d-flex justify-content-center">
	<?php
$contador = 0;
$ganadas = $partidas[0];
while ($ganadas % 10 != 0) {
	$ganadas++;
	$contador++;
}
?>
		<div class='m-2 rounded   bg-light text-center p-3 text-info'>
			<h3>TE FALTA GANAR <?php
																					if ($partidas[0] % 10 == 0) echo 10;
																					else echo ($contador);
																					?> PARTIDAS PARA PODER DIGIEVOLUCIONAR AL DIGIMON QUE QUIERAS</h3>
			<h4>¡ÁNIMO!</h4>
		</div>

	<?php

} else if (!$imprimido2) {
	?>
	<div class='m-2 rounded  border border-info bg-light text-center p-3 text-info'>
	<h3>ACTUALMENTE NO TENEMOS DIGIMONES QUE PUEDAN EVOLUCIONAR</h3>
	<h4>Intentelo de nuevo más tarde</h4>
	</div>
	</div>
	</div>
<?php

}
?>

				</div>
			</div>
		</div>
	</div>
</section>
</div>
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>