<?php
include_once "Utilidades.class.php";
include_once "admin_digimon.class.php";

$digimon_cad="";
$solucion_subida['foto-principal']="";
$solucion_subida['foto-victoria']="";
$solucion_subida['foto-derrota']="";

if (isset($_GET['digimon_cad'])) {
	//Viene de la pagina admin_ver_digimon.php de pulsar en el boton modificar/Añadir Imagen
	$digimon = Utilidades::cadenaurl_a_obj($_GET['digimon_cad']);
	$digimon_cad=Utilidades::obj_a_cadenaurl($digimon);
}else if (isset($_POST['subir-fotos'])){
	$digimon = Utilidades::cadenaurl_a_obj($_POST['digimon_cad']);
	$digimon_cad=Utilidades::obj_a_cadenaurl($digimon);
	$solucion_subida=array();
	foreach($_FILES as $key => $valor){
		if($_FILES[$key]['name']!=''){
			$ruta_temporal=$_FILES[$key]['tmp_name'];
			$posicionPunto=strpos($_FILES[$key]['name'],".");
			$extension=substr($_FILES[$key]['name'],$posicionPunto,strlen($_FILES[$key]['name']));
			$nombreFoto=$key.$extension;
			$destino= DIGIMONESDIR.$digimon->getNombre()."/".$nombreFoto;
			if(move_uploaded_file($ruta_temporal,$destino)){
				$solucion_subida[$key]= "<span style='color:green;'>Archivo subidos con exito.</span>";
				//Establecer la imagen al objeto
				if($key=='foto-principal')$digimon->setImagen($destino);
				else if($key=='foto-victoria')$digimon->setImagenVictoria($destino);
				else if ($key=='foto-derrota')$digimon->setImagenDerrota($destino);
				Utilidades::sobreescribir_digimon($digimon);
			}else{
				$solucion_subida[$key]= "<span style='color:red;'>Ocurrio un error, no se ha podido subir el archivo.</span>";
			}		
		}else{
			$img_defecto="img/porDefecto.png";
			$destino= DIGIMONESDIR.$digimon->getNombre()."/".$key.".png";
			if(copy($img_defecto,$destino)){
				$solucion_subida[$key]= "<span style='color:green;'>Se ha asignado una imagen por defecto</span>";
				if($key=='foto-principal')$digimon->setImagen($destino);
				else if($key=='foto-victoria')$digimon->setImagenVictoria($destino);
				else if ($key=='foto-derrota')$digimon->setImagenDerrota($destino);
				Utilidades::sobreescribir_digimon($digimon);
			}else{
				$solucion_subida[$key]= "<span style='color:red;'>Ocurrio un error, no se ha podido subir el archivo.</span>";
			}		
		}	
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<style>


		.d-inline{
			display:inline;
		}
		.imagen-info{
			height:200px;
		}

	</style>
</head>
<body>
<nav id=menu>
<a href="admin_alta_usuario.php">Alta a usuario.</a><br>
<a href="admin_alta_digimon.php">Alta a digimon.</a><br>
<a href="admin_definir_evolucion.php">Definir evoluciones.</a><br>
<a href="admin_ver_digimon.php">Ver digimones</a>
</nav>
	<div class="container">
		
		<section class="info-digimon">
			<div class="imagenes ">
			<form action="admin_imagen_digimon.php" name="subir" method="post" enctype="multipart/form-data">
					Subir foto principal: <input  type="file" name="foto-principal"><?=$solucion_subida['foto-principal']?><br>
					Subir foto de victoria: <input  type="file" name="foto-victoria"><?=$solucion_subida['foto-victoria']?><br>
					Subir foto de derrota: <input  type="file" name="foto-derrota"><?=$solucion_subida['foto-derrota']?><br>
					<input type="submit" name="subir-fotos" id="subir-fotos" value="Subir fotos">
					<input type="hidden" name="digimon_cad" value="<?=$digimon_cad?>">
				</form>
			</div>
		
	
		<div class="info">
		<h2>Información del digimon</h2>
		<img class='imagen-info' src="<?= $digimon->getImagen() ?>" alt=""><br>
			Nombre: <h4 class="d-inline"><?= $digimon->getNombre() ?></h4><br>
			Ataque: <strong><?= $digimon->getAtaque() ?></strong><br>
			Defensa: <strong><?= $digimon->getDefensa() ?></strong><br>
			Tipo: <strong><?= $digimon->getTipo() ?></strong><br>
			Nivel: <strong><?= $digimon->getNivel() ?></strong><br>
			<?php
		if ($digimon->getEvolucion() == null) {
			$evolucion = "<span style='color:blue;'> No definida </span>";
		} else {
			$evolucion = $digimon->getEvolucion();
		}
		?>
			Evoluciona a: <strong><?= $evolucion ?></strong><br>
		</div>
		</section>
		
		
	</div>
</body>
</html>