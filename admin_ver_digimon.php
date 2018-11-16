
<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Digimon.class.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link rel="stylesheet" href="css/admin-style.css">

	<title>Ver digimones</title>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary ">
  <a class="navbar-brand mr-5" href="admin.php">Menu administrador</a>
  <button class="navbar-toggler mr-5" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="ml-5"></div>
  <div class="collapse navbar-collapse   ml-5" id="navbarText">
    <ul class="navbar-nav mr-auto ml-5">
      <li class="nav-item text-center  ml-5">
        <a class="nav-link" href="admin.php"><i class="nav-link fas fa-home  d-block mb-1"></i>Inicio<span class="sr-only">(current)</span></a>
      </li>
    <ul class="navbar-nav mr-auto ">
      <li class="nav-item text-center">
        <a class="nav-link" href="admin_alta_usuario.php"><i class="nav-link far fa-user  d-block mb-1"></i>Alta a usuario<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item text-center">
        <a class="nav-link" href="admin_alta_digimon.php"> <i class="nav-link fas fa-paw  d-block mb-1"></i>Alta a digimon</a>
      </li>
      <li class="nav-item text-center">
        <a class="nav-link" href="admin_definir_evolucion.php"> <i class="fas fa-long-arrow-alt-up nav-link d-block mb-1"></i>Definir evoluciones</a>
	  </li>
	  <li class="nav-item text-center active">
        <a class="nav-link" href="admin_ver_digimon.php"> <i class="far fa-eye nav-link  d-block mb-1"></i>Ver digimones</a>
      </li>
    </ul>

  </div>
</nav>

<div class="container-fluid p-5 pt-0">
	<div class="caja nivel1 pt-0">
	<h3 class="bg-warning text-white w-100 mt-5 mb-4 p-2">DIGIMONES DE NIVEL 1</h3>	
	<div class="caja-digimon row d-flex flex-row flex-wrap">
	
		<?php
	$digimones_nivel1 = Utilidades_dig::listar(DIGIMONES, 1);

	if (!empty($digimones_nivel1)) {
		for ($i = 0; $i < count($digimones_nivel1); $i++) {
			$digimon_cad = Utilidades::obj_a_cadenaurl($digimones_nivel1[$i]);
			if ($digimones_nivel1[$i]->getEvolucion() == null) {
				$evolucion = "<span style='color:blue;'> No definida </span>";
			} else {
				$evolucion = $digimones_nivel1[$i]->getEvolucion();
			}
			?>
			<div class='col-3'>
			<table class='table table-light'>
			<tr><td class='imagen' colspan='2'><img class='img-tabla' src='<?= $digimones_nivel1[$i]->getImagen() ?>' alt=''></td></tr>
				<tr><th>Nombre</th><td><?= $digimones_nivel1[$i]->getNombre() ?></td></tr>
				<tr><th>Ataque</th><td><?= $digimones_nivel1[$i]->getAtaque() ?></td></tr>
				<tr><th>Defensa</th><td><?= $digimones_nivel1[$i]->getDefensa() ?></td></tr>
				<tr><th>Tipo</th><td><?= $digimones_nivel1[$i]->getTipo() ?></td></tr>
				<tr><th>Evoluciona a</th><td><?= $evolucion ?></td></tr>
				<tr><td colspan='2'>
				<form action="admin_imagen_digimon.php" method="POST">
				<input type="submit"  class='btn btn-info w-100 rounded-0' name="btn-modificarImg"  value="Añadir/Modificar Imagen">
				<input type="hidden" name="digimon_cad" value="<?=$digimon_cad?>">
				</form></td></tr>
			</table>
			</div>
		
		<?php
		}
	} else {
		echo "<span style='color:red;'> No hay digimones del nivel 1 </span>";
	}

	?>
	
	</div>
	</div>
	<div class="caja nivel2">
	<h3 class="bg-warning text-white w-100 mt-5 mb-4 p-2">DIGIMONES DE NIVEL 2</h3>	
	<div class="caja-digimon row d-flex flex-row flex-wrap">

	<?php
$digimones_nivel2 = Utilidades_dig::listar(DIGIMONES, 2);
if (!empty($digimones_nivel2)) {
	for ($i = 0; $i < count($digimones_nivel2); $i++) {
		$digimon_cad = Utilidades::obj_a_cadenaurl($digimones_nivel2[$i]);
		if ($digimones_nivel2[$i]->getEvolucion() == null) {
			$evolucion = "<span style='color:blue;'> No definida </span>";
		} else {
			$evolucion = $digimones_nivel2[$i]->getEvolucion();
		}
?>
	<div class='col-3'>
	<table class='table table-light'>
	<tr><td class='imagen' colspan='2'><img class='img-tabla' src='<?= $digimones_nivel2[$i]->getImagen() ?>' alt=''></td></tr>
		<tr><th>Nombre</th><td><?= $digimones_nivel2[$i]->getNombre() ?></td></tr>
		<tr><th>Ataque</th><td><?= $digimones_nivel2[$i]->getAtaque() ?></td></tr>
		<tr><th>Defensa</th><td><?= $digimones_nivel2[$i]->getDefensa() ?></td></tr>
		<tr><th>Tipo</th><td><?= $digimones_nivel2[$i]->getTipo() ?></td></tr>
		<tr><th>Evoluciona a</th><td><?= $evolucion ?></td></tr>
		<tr><td colspan='2'>
		<form action="admin_imagen_digimon.php" method="POST">
		<input type="submit"  class='btn btn-info w-100 rounded-0' name="btn-modificarImg" value="Añadir/Modificar Imagen">
		<input type="hidden" name="digimon_cad" value="<?=$digimon_cad?>" >
		</form></td></tr>
	</table>
	</div>

<?php
	}
} else {
	echo "<span style='color:red;'> No hay digimones del nivel 2 </span>";
}
?>
	</div>
	<div class="caja nivel3">
	<h3 class="bg-warning text-white w-100 mt-5 mb-4 p-2">DIGIMONES DE NIVEL 3</h3>	
	<div class="caja-digimon row d-flex flex-row flex-wrap">
<?php
$digimones_nivel3 = Utilidades_dig::listar(DIGIMONES, 3);
if (!empty($digimones_nivel3)) {
	for ($i = 0; $i < count($digimones_nivel3); $i++) {
		$digimon_cad = Utilidades::obj_a_cadenaurl($digimones_nivel3[$i]);
		?>
		<div class='col-3'>
		<table class='table table-light'>
		<tr><td class='imagen' colspan='2'><img class='img-tabla' src='<?= $digimones_nivel3[$i]->getImagen() ?>' alt=''></td></tr>
			<tr><th>Nombre</th><td><?= $digimones_nivel3[$i]->getNombre() ?></td></tr>
			<tr><th>Ataque</th><td><?= $digimones_nivel3[$i]->getAtaque() ?></td></tr>
			<tr><th>Defensa</th><td><?= $digimones_nivel3[$i]->getDefensa() ?></td></tr>
			<tr><th>Tipo</th><td><?= $digimones_nivel3[$i]->getTipo() ?></td></tr>
			<tr><th>Evoluciona a</th><td><?= $evolucion ?></td></tr>
			<tr><td colspan='2'>
			<form action="admin_imagen_digimon.php" method="POST">
			<input type="submit"  class='btn btn-info w-100 rounded-0' name="btn-modificarImg"  value="Añadir/Modificar Imagen">
			<input type="hidden" name="digimon_cad" value="<?=$digimon_cad?>">
			</form></td></tr>
		</table>
		</div>
	
	<?php
	}
} else {
	echo "<span style='color:red;'> No hay digimones del nivel 3 </span>";
}
?></div>



	</div>
</div>
	
<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>