<?php
include_once "clases/Utilidades.class.php";
include_once "clases/Utilidades_dig.class.php";
include_once "clases/Digimon.class.php";

$digimon_cad = "";
$solucion_subida = array();
$solucion_subida['foto-principal'] = "";
$solucion_subida['foto-victoria'] = "";
$solucion_subida['foto-derrota'] = "";

if (isset($_REQUEST['digimon_cad'])) {
	//Viene de la pagina admin_ver_digimon.php de pulsar en el boton modificar/Añadir Imagen
	$digimon = Utilidades::cadenaurl_a_obj($_REQUEST['digimon_cad']);
}

if (isset($_FILES)) {
	$digimon = Utilidades::cadenaurl_a_obj($_REQUEST['digimon_cad']);
	foreach ($_FILES as $key => $valor) {
		if ($_FILES[$key]['name'] != '') {
			//Se cambia el nombre de la foto por si desea identificar la foto por el nombre
			//no es necesario ya que se guarda dentro de cada objeto.
			$ruta_temporal = $_FILES[$key]['tmp_name'];
			$posicionPunto = strpos($_FILES[$key]['name'], ".");
			$extension = substr($_FILES[$key]['name'], $posicionPunto, strlen($_FILES[$key]['name']));
			$nombreFoto = $key . $extension;
			//Se controla que el archivo subido sea una imagen
			$array_extensiones = array(".jpeg", ".jpg", ".gif", ".png", ".raw", ".BMP", ".psd");
			if (in_array($extension, $array_extensiones)) {
				$destino = DIGIMONESDIR . $digimon->getNombre() . "/" . $nombreFoto;
				if (move_uploaded_file($ruta_temporal, $destino)) {
					$solucion_subida[$key] = "<span style='color:green;'>Archivo " . $_FILES[$key]['name'] . " subido con exito.</span>";

					//Establecer la imagen al objeto
					if (isset($_POST['subir-fotos'])) {
						if ($key == 'foto-principal') {
							$digimon->setImagen($destino);
						} else if ($key == 'foto-victoria') {
							$digimon->setImagenVictoria($destino);
						} else if ($key == 'foto-derrota') {
							$digimon->setImagenDerrota($destino);
						}
					}

					Utilidades_dig::sobreescribir($digimon, DIGIMONES);

				} else {
					$solucion_subida[$key] = "<span style='color:red;'>Ocurrio un error al subir el archivo " . $_FILES[$key]['name'] . "</span>";
				}
			} else {
				$solucion_subida[$key] = "<span style='color:red;'>Extension no admitida.</span>";
			}



		} else {
			foreach ($_FILES as $key => $valor) {
				if (isset($_POST['eliminar-foto'])) {
					if ($key == 'foto-principal') {
						$digimon->setImagen("img/porDefecto.png");
					} else if ($key == 'foto-victoria') {
						$digimon->setImagenVictoria("img/porDefecto.png");
					} else if ($key == 'foto-derrota') {
						$digimon->setImagenDerrota("img/porDefecto.png");
					}
				}
			}
		}

	}

}
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
	<title>Imagen digimon</title>

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
      <li class="nav-item text-center ml-5">
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
	  <li class="nav-item text-center">
        <a class="nav-link" href="admin_ver_digimon.php"> <i class="far fa-eye nav-link  d-block mb-1"></i>Ver digimones</a>
      </li>
    </ul>

  </div>
</nav>

	<div class="container-fluid p-0">
		<section class=" text-center info-digimon ">
			<h2 class="bg-dark text-white p-1">Información del digimon</h2>
			<div class="row d-flex justify-content-between mt-5">
				<div class="col text-right">
					<img class='imagen-info ' src=<?= $digimon->getImagen() ?> alt="">
				</div>
				<div class="col text-left p-4">
					Nombre: <h4 class="d-inline">
						<?= $digimon->getNombre() ?>
					</h4><br>
					Ataque: <strong>
						<?= $digimon->getAtaque() ?></strong><br>
					Defensa: <strong>
						<?= $digimon->getDefensa() ?></strong><br>
					Tipo: <strong>
						<?= $digimon->getTipo() ?></strong><br>
					Nivel: <strong>
						<?= $digimon->getNivel() ?></strong><br>
					<?php
				if ($digimon->getEvolucion() == null) {
					$evolucion = "<span style='color:blue;'> No definida </span>";
				} else {
					$evolucion = $digimon->getEvolucion();
				}
				?>
					Evoluciona a: <strong><?= $evolucion ?></strong><br>
				</div>

			</div>
		</section>

		<section class="row mt-5 p-5 d-flex justify-content-between ">
		<!-- CAJA FOTO PRINCIPAL -->
			<div class="col-4"><div class=" border border-secondary rounded p-0 m-1">
			
			<form action="admin_imagen_digimon.php"  method="post" enctype="multipart/form-data" class="text-center ">
			<div class="text-center bg-primary text-white p-1 ">
			<h5 class="ml-2">Subir foto principal: </h5>
			</div>
			<label for="file1">
			<img class='imagen-info mt-2' src=<?= $digimon->getImagen() ?> alt="">
			</label>
			<input type="file" id="file1" name="foto-principal" class="btn bg-dark rounded-0 text-white w-100"  />
			
			<?= $solucion_subida['foto-principal'] ?>
			
			<div class="d-block mt-3 mb-2  d-flex justify-content-center">
			<label for="subir-principal" class="btn btn-outline-success btn-rounded waves-effect btn-lg float-left">
			Subir foto
			<i class="fa fa-upload ml-2" aria-hidden="true"></i>
			<input type="submit" class="d-none"  name="subir-fotos" id="subir-principal" >
			</label>

			<label for="eliminar-foto-principal" class="btn btn-outline-danger btn-rounded waves-effect btn-lg float-left ml-2">
			Eliminar foto
			<i class="far fa-trash-alt"></i>
			<input type="submit" class="d-none"  name="eliminar-foto" id="eliminar-foto-principal" >
			</label>
			</div>
			<?php

		$digimon_cad = Utilidades::obj_a_cadenaurl($digimon);
		?>
			<input type="hidden" name="digimon_cad" value=<?= $digimon_cad ?>>
			</form>
			
			</div></div>
<!-- CAJA FOTO VICTORIA -->
		<div class="col-4"><div class="  border border-secondary rounded p-0 m-1">
		
		<form action="admin_imagen_digimon.php"  method="post" enctype="multipart/form-data" class="text-center ">
		<div class="text-center  bg-success text-white p-1 ">
		<h5 class="ml-2">Subir foto victoria: </h5>
		</div>
		<label for="file2">
		<img class='imagen-info mt-2' src=<?= $digimon->getImagenVictoria() ?> alt="">
		</label>
		<input type="file" id="file2" name="foto-victoria" class="btn bg-dark rounded-0 text-white w-100"  />
		
		<?= $solucion_subida['foto-victoria'] ?>
		
		
		<div class="d-block mt-3 mb-2 d-flex justify-content-center">
		<label for="subir-victoria" class="btn btn-outline-success btn-rounded waves-effect btn-lg float-left">
		Subir foto
		<i class="fa fa-upload ml-2" aria-hidden="true"></i>
		<input type="submit" class="d-none"  name="subir-fotos" id="subir-victoria" >
		</label>

		<label for="eliminar-foto-victoria" class="btn btn-outline-danger btn-rounded waves-effect btn-lg float-left ml-2">
		Eliminar foto
		<i class="far fa-trash-alt"></i>
		<input type="submit" class="d-none"  name="eliminar-foto" id="eliminar-foto-victoria" >
		</label>
		</div>
		<?php

	$digimon_cad = Utilidades::obj_a_cadenaurl($digimon);
	?>
		<input type="hidden" name="digimon_cad" value=<?= $digimon_cad ?>>
		</form>
		
		</div></div>
<!-- CAJA FOTO DERROTA -->
<div class="col-4">
	<div class="imagenes border border-secondary rounded p-0 m-1">

<form action="admin_imagen_digimon.php"  method="post" enctype="multipart/form-data" class="text-center ">
<div class="text-center  bg-danger text-white p-1 ">
<h5 class="ml-2">Subir foto derrota: </h5>
</div>
<label for="file3">
<img class='imagen-info mt-2' src=<?= $digimon->getImagenDerrota() ?> alt="">
</label>
<input type="file" id="file3" name="foto-derrota" class="btn bg-dark rounded-0 text-white w-100"  />

<?= $solucion_subida['foto-derrota'] ?>


<div class="d-block mt-3 mb-2 d-flex justify-content-center">

<label for="subir-derrota" class="btn btn-outline-success btn-rounded waves-effect btn-lg float-left">
Subir foto
<i class="fa fa-upload ml-2" aria-hidden="true"></i>
<input type="submit" class="d-none"  name="subir-fotos" id="subir-derrota" >
</label>

<label for="eliminar-foto-derrota" class="btn btn-outline-danger btn-rounded waves-effect btn-lg float-left ml-2">
Eliminar foto
<i class="far fa-trash-alt"></i>
<input type="submit" class="d-none"  name="eliminar-foto" id="eliminar-foto-derrota" >
</label>
</div>
<?php

$digimon_cad = Utilidades::obj_a_cadenaurl($digimon);
?>
<input type="hidden" name="digimon_cad" value=<?= $digimon_cad ?>>
</form>

</div>
</div>

</section>
		


	</div>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>