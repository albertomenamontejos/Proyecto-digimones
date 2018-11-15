<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_user.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Combate.class.php";

if (isset($_POST['otra_pagina'])) {
	$usuario = Utilidades::cadenaurl_a_obj($_POST['usuario_cad']);
} else if (isset($_POST['seleccionar-adversario'])) {
	$usuario = Utilidades::cadenaurl_a_obj($_POST['usuario_cad']);
	$adversario = Utilidades_user::existe($_POST['adversario']);
	$array_equipoVS = Utilidades::cadenaurl_a_obj($_REQUEST['equipoVS']);
	$array_equipo = Utilidades::cadenaurl_a_obj($_REQUEST['equipo']);
} else if (isset($_POST['adversario_cad'])) {
	$usuario = Utilidades::cadenaurl_a_obj($_POST['usuario_cad']);
	$adversario = Utilidades::cadenaurl_a_obj($_POST['adversario_cad']);
	$mi_equipo = Utilidades_dig::leer_equipo($usuario->getTxt_equipo_usuario());
	$equipo_adversario = Utilidades_dig::leer_equipo($adversario->getTxt_equipo_usuario());
	$array_equipoVS = Utilidades::cadenaurl_a_obj($_REQUEST['equipoVS']);
	$array_equipo = Utilidades::cadenaurl_a_obj($_REQUEST['equipo']);
	$partidas = array();
	if (isset($_POST['combatir'])) {//Si viene de pulsar en Comenzar ronda

		for ($i = 0; $i < 3; $i++) {
			$ronda = new Combate($mi_equipo, $equipo_adversario, $usuario->getNick(), $adversario->getNick());
			$ganador = $ronda->getGanador();
			Combate::registrarPartidaDigimon($usuario,$adversario,$array_equipo[$i],$ganador[$i+1][0]);
		}
		$cont_usuario = 0;
		$cont_adversario = 0;
		$cont_empate = 0;
		for ($i = 1; $i <= count($ganador); $i++) {
			if ($ganador[$i][0] == $usuario->getNick()) {
				$cont_usuario++;
			} else if ($ganador[$i][0] == $adversario->getNick()) {
				$cont_adversario++;
			} else $cont_empate++;

		}
		if ($ganador[1][0] == $ganador[2][0]) {
			//Para mostrar solo las rondas jugadas hasta que alguien gana o empata
			unset($ganador[3]);
			if ($ganador[1][0] == $usuario->getNick()) {
				$cont_usuario = 2;
				$cont_adversario = 0;
			} else {
				$cont_adversario = 2;
				$cont_usuario = 0;
			}
		}
		//Se escoge el ganador de la PARTIDA (juntando todas las rondas)
		//Se envia al historial (registro-partidas.txt de cada usuario)
		$ganador_partida = "";
		if ($cont_usuario >= 2 || ($cont_usuario == 1 && $cont_empate == 2)) {
			$ganador_partida = $usuario->getNick();
		} else if ($cont_adversario >= 2 || ($cont_adversario == 1 && $cont_empate == 2)) {
			$ganador_partida = $adversario->getNick();
		}
		Combate::registrarPartida($ganador_partida, $usuario, $adversario);
		
	}

	$info_partida_usuario = $ronda->getUs_info();
	$info_partida_adversario = $ronda->getAdv_info();
} else {
	$errorUsuario = "<p>Ha ocurrido un error</p>";
	header("Location: index.php?error=$errorUsuario");
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
	<title>Jugar Partida</title>
	<style>

	</style>
</head>
<body>
	<div class="container-fluid p-0">

		<?= Utilidades::menu($usuario) ?>
		<section class="container-fluid  bg-imagen border border-info border-left-0 border-right-0 ">
		<div class="capa1  mt-3 mb-4 rounded" >

<!-- MI EQUIPO (Caja de la izquierda) -->
			<div class="row">
				<div class="col-4 p-5 pt-5 rounded">
					<h3 class="text-center text-white mt-3 mb-5 pb-5">Tu equipo</h3>
						<?php
					if (Utilidades_dig::contar_dig($usuario->getTxt_equipo_usuario()) == 3) {
						$array_equipo = Utilidades_dig::listar($usuario->getTxt_equipo_usuario());
						for ($i = 0; $i < count($array_equipo); $i++) {

								if (isset($_POST['combatir'])) {
									if (isset($ganador[$i + 1][0]) && $ganador[$i + 1][0] == $usuario->getNick()) {
										echo "<div class='row gana bg-white  rounded  text-center d-flex  justify-content-between align-items-center p-2 m-3'>";
										echo "<div class='col'>";
										echo "<img src='" . $array_equipo[$i]->getImagenVictoria() . " ' width='120px;' alt=''>";
									} else if (isset($ganador[$i + 1][0]) && $ganador[$i + 1][0] == $adversario->getNick()) {
										echo "<div class='row pierde bg-white   rounded   text-center d-flex  justify-content-between align-items-center p-2 m-3'>";
										echo "<div class='col'>";
										echo "<img src='" . $array_equipo[$i]->getImagenDerrota() . " ' width='120px;' alt=''>";
									} else {
										echo "<div class='row empate bg-white    rounded   text-center d-flex  justify-content-between align-items-center p-2 m-3'>";
										echo "<div class='col'>";
										echo "<img src='" . $array_equipo[$i]->getImagen() . " ' width='120px;' alt=''>";
									}
								} else {
									echo "<div class='row bg-white    rounded  text-center d-flex  justify-content-between align-items-center p-1 m-3'>";
									echo "<div class='col'>";
									echo "<img src='" . $array_equipo[$i]->getImagen() . " ' width='120px;' alt=''>";
								}
								?>	
										</div>
										<div class='col'>
											<h4><?= $array_equipo[$i]->getNombre() ?></h4>
											<p>ATAQUE: <?= $array_equipo[$i]->getAtaque() ?></p>
											<p>DEFENSA: <?= $array_equipo[$i]->getDefensa() ?></p>
											<p>TIPO: <?= $array_equipo[$i]->getTipo() ?></p>
										</div>
									</div>
							<?php
					}
					?>
				</div>
<!-- FIN MI EQUIPO -->

<!-- INFORMACION PARTIDA (Caja del medio) -->
					<?php
				if (isset($_POST['seleccionar-adversario']) || isset($_POST['adversario_cad'])) {
					?>
					<div class="col-4 mt-4 ">
					<h3 class="text-center text-white  mt-5">Información de partida</h3>
						<div class=" d-flex align-items-end flex-wrap ">
							<div class=" w-100 bg-light rounded mt-3 w-100">
							<h3 class="text-center mt-2 w-100 text-white bg-dark p-2"><?= $usuario->getNick() ?> <span style="color:red;">VS </span>  <?= $adversario->getNick() ?></h3>
							
							<?php
						if (isset($_POST['combatir'])) {

							if ($ganador_partida == $usuario->getNick()) {
								echo "<h5 class=' text-success text-center font-weight-bold pt-3 pb-3'>HAS GANADO<span class='d-block text-dark'>$cont_usuario  - $cont_adversario</span></h5>";
							} else if ($ganador_partida == $adversario->getNick()) {
								echo "<h5 class='text-danger text-center font-weight-bold pt-3 pb-3'>HAS PERDIDO<span class='d-block text-dark'>$cont_usuario  - $cont_adversario</span></h5>";
							} else {
								echo "<h5 class='text-warning text-center font-weight-bold pt-3 pb-3'>HAS EMPATADO<span class='d-block text-dark'>$cont_usuario  - $cont_adversario</span></h5>";
							}


							foreach ($ganador as $key => $array_win) {

								echo "<div class='border  border-top-0 border-left-0 border-right-0 border-tranparent text-center '>";
								echo "<div class='text-white bg-success p-2 w-100'>RONDA: " . $key . "</div>";
								if ($array_win[1] != null) { //Cuando quedan empate
									echo "<div><p>Usuario ganador: " . $array_win[0] . "</p></div>";
									echo "<div><p>Digimon ganador: " . $array_win[1]->getNombre() . "</p></div>";
								} else {
									echo "<div><p >Usuario ganador: <span class='text-warning'> EMPATE</span></div>";
									echo "<div><p >Digimon ganador: <span class='text-warning'> EMPATE</span></p></div>";
								}
								?>
									<!-- Tabla informacion de partida -->
						<div>
						<table class="table table-striped table-light  m-0">
						<tr><th></th><th colspan="2"><?= $array_equipo[$key - 1]->getNombre() ?></th><th>VS</th><th colspan="2"><?= $array_equipoVS[$adversario->getNick()][$key - 1]->getNombre() ?></th></tr>
						<tr><th class="text-left">Ataque</th><td colspan="2" ><?= $info_partida_usuario[$key][0] ?></td><td ></td><td colspan="2"><?= $info_partida_adversario[$key][0] ?></td></tr>
						<tr><th class="text-left">Defensa</th><td colspan="2"><?= $info_partida_usuario[$key][1] ?></td><td></td><td colspan="2"><?= $info_partida_adversario[$key][1] ?></td></tr>
						<tr><th class="text-left">Tipo</th><td colspan="2"><?= $array_equipo[$key - 1]->getTipo() . " vs  " . $array_equipoVS[$adversario->getNick()][$key - 1]->getTipo() ?> 
										<br><?= $info_partida_usuario[$key][2] ?></td><td></td><td colspan="2"><?= $array_equipoVS[$adversario->getNick()][$key - 1]->getTipo() . " VS " . $array_equipo[$key - 1]->getTipo() ?> 
										<br><?= $info_partida_adversario[$key][2] ?></td></tr>
						<tr><th class="text-left">Estado </th><td colspan="2"><?= $info_partida_usuario[$key][3] ?></td><td></td><td colspan="2"><?= $info_partida_adversario[$key][3] ?></td></tr>
						<tr class="table-success"><th class="text-left s">Total </th><td colspan="2"><?= $info_partida_usuario[$key][4] ?></td><td></td><td colspan="2"><?= $info_partida_adversario[$key][4] ?></td></tr>
						</table>
						</div>
						<?php
					echo "</div>";

				}

			}
			?>
							</div>
<!-- Btn-Combatir -->
							<div class="mt-4 mb-5 w-100">
								<form action="jugar_partida.php"  method="POST">
								<?php
							if (isset($_POST['seleccionar-adversario'])) {
								echo "<input type='submit' class='btn btn-danger w-100 mb-5' name='combatir' value='Combatir'>";
							}
							?>
								<?php
							$equipoVS = Utilidades::obj_a_cadenaurl($array_equipoVS);
							$equipo = Utilidades::obj_a_cadenaurl($array_equipo);
							$adversario_cad = Utilidades::obj_a_cadenaurl($adversario);
							$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
							?>
							
								<input type="hidden" name="equipoVS" value=<?= $equipoVS ?>>
								<input type="hidden" name="equipo" value=<?= $equipo ?>>
								<input type="hidden" name="usuario_cad" value=<?= $usuario_cad ?>>
								<input type="hidden" name="adversario_cad" value=<?= $adversario_cad ?>>
								</form>
							</div>
						</div>
					</div>
					<?php

			} else {
				echo "<div class='col-4  d-block  mb-5'>";
				echo "</div>";
			}

			?>
			<!-- FIN INFORMACION PARTIDA (Caja del medio) -->

			<!-- INFORMACION ADVERSARIO (Caja de la derecha) -->
					<!-- Select: se recorre todos los usuarios que tengan un equipo organizado -->
			<div class="col-4 mt-5 ">
				<?php
			$array_equipoVS = Utilidades_user::user_con_equipo($usuario);
			if (!empty($array_equipoVS)) {
				if (!isset($_POST['adversario_cad']) || isset($_POST['seleccionar-adversario']) || isset($_POST['combatir'])) {
					?>
						<form action="jugar_partida.php" method="POST">

						<h3 class="text-center text-white mt-4 mb-5">Selecciona tu adversario</h3>
						<div class="row mb-5 ">
							<div class="col">
								<select name="adversario" id="adversario" class="form-control d-inline">
									<?php
								foreach ($array_equipoVS as $key => $valor) {
									if (isset($_POST['seleccionar-adversario']) || isset($_POST['adversario_cad'])) {
										if ($key == $adversario->getNick())
											echo "<option value=$key selected>$key</option>";
										else
											echo "<option value=$key >$key</option>";
									} else {
										echo "<option value=$key >$key</option>";
									}

								}
								?>
								</select>
							</div>
							<div class="col">
							<?php
						$equipoVS = Utilidades::obj_a_cadenaurl($array_equipoVS);
						$equipo = Utilidades::obj_a_cadenaurl($array_equipo);
						$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
						?>
							<input type="hidden" name="equipoVS" value=<?= $equipoVS ?>>
							<input type="hidden" name="equipo" value=<?= $equipo ?>>
							<input type="hidden" name="usuario_cad" value=<?= $usuario_cad ?>>
							<input type="submit" name="seleccionar-adversario" class="btn btn-success d-inline" value="Seleccionar">
							</div>
						</div>
						</form>
						<?php

				} else {
					?>
					<h3 class="text-center text-white mt-4 mb-3">Equipo del adversario</h3>
						<div class="row mb-5 "></div>
				<?php

		}
	} else {
		?>
			<h4 class='text-danger font-weight-bold '>No hay usuarios con equipos disponibles</h4>;
			<p class='text-white '>¡ Vuelve más tarde !</p>";
		<?php

}
//CAJAS DE DIGIMONES ADVERSARIO
if (isset($_POST['seleccionar-adversario']) || isset($_POST['adversario_cad'])) {
	foreach ($array_equipoVS as $key => $valor) {
		if ($key == $adversario->getNick()) {
			for ($i = 0; $i < count($valor); $i++) {
				if (isset($_POST['combatir'])) {
					if (isset($ganador[$i + 1][0]) && $ganador[$i + 1][0] == $adversario->getNick()) {
						echo "<div class='gana bg-white  p-3 m-3 rounded '>";
						echo "<img src='" . $valor[$i]->getImagenVictoria() . " ' width='120px;' alt=''>";
					} else if (isset($ganador[$i + 1][0]) && $ganador[$i + 1][0] == $usuario->getNick()) {
						echo "<div class='pierde bg-white  p-3 m-3 rounded '>";
						echo "<img src='" . $valor[$i]->getImagenDerrota() . " ' width='120px;' alt=''>";
					} else {
						echo "<div class='empate bg-white  p-3 m-3 rounded '>";
						echo "<img src='" . $valor[$i]->getImagen() . " ' width='120px;' alt=''>";
					}
				} else {
					echo "<div class='bg-white  p-3 m-3 rounded '>";
					echo "<img src='" . $valor[$i]->getImagen() . " ' width='120px;' alt=''>";
				}
				?>
								<h5 class="d-inline ml-5"><?= $valor[$i]->getNombre() ?></h5>			
							</div>
							<?php	
					}
				}
			}
		}
	} else {
		?>
					<div class="row bg-white    rounded p-1  text-center d-flex  justify-content-center ">
						<div class='col-12 d-flex justify-content-center align-items-center flex-column ' style="min-height:300px;">
							<p style="color:red">Debes tener 3 digimones en el equipo</p>
							<form action="organizar_equipo.php"  method="POST">
							<input type="submit" name="otra_pagina" class="btn btn-info mt-4" value="Organizar equipo">
							<?php $usuario_cad = Utilidades::obj_a_cadenaurl($usuario) ?>
							<input type="hidden" name="usuario_cad" value=<?= $usuario_cad ?>>
							</form>
						</div>
					</div>
					<?php

			}
			?>
					</div>
				</div>	
			</div>
		</section>
		</div>
	</div>
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>