<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_user.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Combate.class.php";


if (isset($_REQUEST['nick'])) {
	$nick = $_REQUEST['nick'];
	$usuario = Utilidades_user::existe($nick);
	$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
} else {
	$error = "<p>Ha ocurrido un error</p>";
	header("Location: index.php?error=$error");
}

if (isset($_POST['adversario'])) {
	$usuario = Utilidades_user::existe($nick);
	$adversario = Utilidades_user::existe($_POST['adversario']);
	$mi_equipo = Utilidades_dig::leer_equipo($usuario->getTxt_equipo_usuario());
	$equipo_adversario = Utilidades_dig::leer_equipo($adversario->getTxt_equipo_usuario());
	if (isset($_POST['comenzar-ronda'])) {

		$combate = new Combate($mi_equipo, $equipo_adversario, $nick, $_POST['adversario'], 0);
		var_dump($combate->getGanador());
		var_dump($combate->getRonda());
	} else if (isset($_POST['siguiente-ronda'])) {
		$combate1 = new Combate($mi_equipo, $equipo_adversario, $nick, $_POST['adversario'], 1);
		var_dump($combate1->getGanador());
		var_dump($combate1->getRonda());

	} else if (isset($_POST['ultima-ronda'])) {
		$combate2 = new Combate($mi_equipo, $equipo_adversario, $nick, $_POST['adversario'], 2);
		var_dump($combate2->getGanador());
		var_dump($combate2->getRonda());
	}
}





?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Jugar Partida</title>
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
		.bg-imagen{
			background-image:url(img/imagenVS.jpg);
			background-repeat: no-repeat;
			background-size: cover;
 			background-position: 45% 50%;
		}
		.capa1{
			background: rgba(0,0,0,.4);
		}
	</style>
</head>
<body>
	<div class="container-fluid p-0">
		<?= Utilidades::menu($usuario); ?>
		<section class="container-fluid  bg-imagen border border-info border-left-0 border-right-0 ">
		<div class="capa1  mt-3 mb-4 rounded" >

		<!-- MI EQUIPO (Caja de la izquierda) -->
			<div class="row">
				<div class="col-4 p-5 rounded">
				<h3 class="text-center text-white mt-3">Tu equipo</h3>
						<?php
					if (Utilidades_dig::contar_dig($usuario->getTxt_equipo_usuario()) == 3) {
						$array_equipo = Utilidades_dig::listar($usuario->getTxt_equipo_usuario());
						for ($i = 0; $i < count($array_equipo); $i++) {
							?>
								<div class="row bg-white mt-4   rounded p-2  text-center d-flex  justify-content-between align-items-center ">
									<div class='col'>
										<img src='<?= $array_equipo[$i]->getImagen() ?>' width="120px;" alt=''>
									</div>
									<div class='col '>
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
				if (isset($_POST['seleccionar-adversario']) || isset( $_POST['adversario'])) {
					?>
				

					<div class="col-4  d-flex align-items-end w-100  mb-5">
					
						<!-- Boton Comenzar combate/ Siguiente ronda / Ultima ronda -->
						<div class="formulario w-100">
						<form action="jugar_partida.php" method="POST">
						<?php
					if (isset($_POST['seleccionar-adversario'])) {
						echo "<input type='submit' class='btn btn-danger w-100 mb-5' name='comenzar-ronda' value='Comenzar combate'>";
					} else if (isset($_POST['comenzar-ronda'])) {
						echo "<input type='submit' class='btn btn-danger w-100 mb-5' name='siguiente-ronda' value='Siguiente ronda'>";
					} else if (isset($_POST['siguiente-ronda'])) {
						echo "<input type='submit' class='btn btn-danger w-100 mb-5' name='ultima-ronda' value='Ultima ronda'>";
					}

					?>
						<input type="hidden" name="nick" value=<?= $nick ?>>
						<input type="hidden" name="adversario" value="<?= $_POST['adversario'] ?>">

						</form>
						</div>
					</div>
					<?php

			} else if (isset($_POST['ultima-ronda'])) {

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
				if (!isset($_POST['adversario']) || isset($_POST['ultima-ronda'])) {
					?>
						<form action="jugar_partida.php?nick=<?= $nick ?>" method="POST">
						<h3 class="text-center text-white mt-4 mb-5">Selecciona tu adversario</h3>
						<div class="row mb-5 ">
							<div class="col">
								<select name="adversario" id="adversario" class="form-control d-inline">
									<?php
								foreach ($array_equipoVS as $key => $valor) {
									if (isset($_POST['seleccionar-adversario'])) {
										if ($key == $_POST['adversario'])
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
						<p class='text-danger font-weight-bold '>No hay usuarios con equipos disponibles</p>;
						<p class='text-white '>¡ Vuelve más tarde !</p>";
						<?php

				}

				if (isset($_POST['seleccionar-adversario']) || isset($_POST['adversario'])) {
					foreach ($array_equipoVS as $key => $valor) {
						if ($key == $_POST['adversario']) {
							for ($i = 0; $i < count($valor); $i++) {
								?>
							<div class="bg-white  p-3 m-3 rounded">
								<img src="<?= $valor[$i]->getImagen() ?>" class="ml-3" width="90px" alt="">
								<h5 class="d-inline ml-5"><?= $valor[$i]->getNombre() ?></h5>			
							</div>
							<?php	
					}
				}
			}
		}
	} else {
		?>
					<div class="row bg-white mt-3   rounded p-2  text-center d-flex  justify-content-center ">
						<div class='col-12 d-flex justify-content-center align-items-center flex-column ' style="min-height:300px;">
							<p style="color:red">Debes tener 3 jugadores en el equipo</p>
							<a href="organizar_equipo.php?nick=<?= $usuario->getNick(); ?>" ><button class="btn btn-info mt-4">Ir a Organizar Equipo</button></a>
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