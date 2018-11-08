<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_user.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Usuario.class.php";

if(isset($_GET['nick'])){
	$nick = $_GET['nick'];
	$usuario=Utilidades_user::existe($nick);
	$usuario_cad=Utilidades::obj_a_cadenaurl($usuario);
	$ruta_txt=$usuario->getTxt_dig_usuario();
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Mis digimons</title>
	<style>
	.img-tabla{
		height:200px;
	
	}
	.imagen{
		text-align:center;
	}
	.caja{
	
		
	}
	.caja-digimon{
		display:flex;
		flex-flox: row wrap;
	}
	.logo{
		width:100px;
		height:100px;
	}
	</style>
</head>
<body>
	<div class="container-fluid p-0">
		<?= Utilidades::menu($usuario, $usuario_cad) ?>
	</div>
	<div class="container-fluid p-0">
	<div class="caja-digimon">

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
	}
	?>
	</div>
	


</div>
	

	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>