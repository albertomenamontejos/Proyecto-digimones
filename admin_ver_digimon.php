
<?php
include_once "funciones.php";
include_once "admin_digimon.class.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Ver digimones</title>
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
	<div class="caja nivel1">
	<h3>Digimons nivel 1</h3>	
	<div class="caja-digimon">

		<?php
	$digimones_nivel1 = Digimon::listar_digimones(1);
	for ($i = 0; $i < count($digimones_nivel1); $i++) {
		$digimon_cad = obj_a_cadenaurl($digimones_nivel1[$i]);
		if ($digimones_nivel1[$i]->getEvolucion() == null) {
			$evolucion = "<span style='color:blue;'> No definida </span>";
		} else {
			$evolucion = $digimones_nivel1[$i]->getEvolucion();
		}
		echo "<table border ='1'>";
		echo "<tr>
		<td class='imagen' colspan='2'><img class='img-tabla' src='" . $digimones_nivel1[$i]->getImagen() . "' alt=''></td></tr>
		<tr><th>Nombre</th><td>" . $digimones_nivel1[$i]->getNombre() . "</td></tr>
		<tr><th>Ataque</th><td>" . $digimones_nivel1[$i]->getAtaque() . "</td></tr>
		<tr><th>Defensa</th><td>" . $digimones_nivel1[$i]->getDefensa() . "</td></tr>
		<tr><th>Tipo</th><td>" . $digimones_nivel1[$i]->getTipo() . "</td></tr>
		<tr><th>Evoluciona a</th><td>$evolucion</td></tr>
		<tr><td colspan='2'><a href='admin_imagen_digimon.php?digimon_cad=$digimon_cad'><button class='btn btn-info'>Añadir/Modificar Imagen</button></a></td>
		</tr>";
		echo "</table>";
	}
	?>
	</div>
	
	</div>
	<div class="caja nivel2">
	<h3>Digimons nivel 2</h3>
	<div class="caja-digimon">
	<?php
	$digimones_nivel2 = Digimon::listar_digimones(2);
	for ($i = 0; $i < count($digimones_nivel2); $i++) {
		$digimon_cad = obj_a_cadenaurl($digimones_nivel2[$i]);
		if ($digimones_nivel2[$i]->getEvolucion() == null) {
			$evolucion = "<span style='color:blue;'> No definida </span>";
		} else {
			$evolucion = $digimones_nivel2[$i]->getEvolucion();
		}
		echo "<table border ='1'>";
		echo "<tr>
		<td class='imagen' colspan='2'><img class='img-tabla' src='" . $digimones_nivel2[$i]->getImagen() . "' alt=''></td></tr>
		<tr><th>Nombre</th><td>" . $digimones_nivel2[$i]->getNombre() . "</td></tr>
		<tr><th>Ataque</th><td>" . $digimones_nivel2[$i]->getAtaque() . "</td></tr>
		<tr><th>Defensa</th><td>" . $digimones_nivel2[$i]->getDefensa() . "</td></tr>
		<tr><th>Tipo</th><td>" . $digimones_nivel2[$i]->getTipo() . "</td></tr>
		<tr><th>Evoluciona a</th><td>$evolucion</td></tr>
		<tr><td colspan='2'><a href='admin_imagen_digimon.php?digimon_cad=$digimon_cad'><button class='btn btn-info'>Añadir/Modificar Imagen</button></a></td>
		</tr>";
		echo "</table>";
	}
	?>
	</div>
	<div class="caja nivel3">
	<h3>Digimons nivel 3</h3>
	
<div class="caja-digimon">
<?php
$digimones_nivel3 = Digimon::listar_digimones(3);
for ($i = 0; $i < count($digimones_nivel3); $i++) {
	$digimon_cad = obj_a_cadenaurl($digimones_nivel3[$i]);

	echo "<table border ='1'>";
	echo "<tr>
<td class='imagen' colspan='2'><img class='img-tabla' src='" . $digimones_nivel3[$i]->getImagen() . "' alt=''></td></tr>
<tr><th>Nombre</th><td>" . $digimones_nivel3[$i]->getNombre() . "</td></tr>
<tr><th>Ataque</th><td>" . $digimones_nivel3[$i]->getAtaque() . "</td></tr>
<tr><th>Defensa</th><td>" . $digimones_nivel3[$i]->getDefensa() . "</td></tr>
<tr><th>Tipo</th><td>" . $digimones_nivel3[$i]->getTipo() . "</td></tr>
<tr><th>Evoluciona a</th><td><span style='color:red;'>No disponible</td></tr>
<tr><td colspan='2'><a href='admin_imagen_digimon.php?digimon_cad=$digimon_cad'><button class='btn btn-info'>Añadir/Modificar Imagen</button></a></td>
</tr>";
	echo "</table>";
}
?></div>



	</div>
</div>
	

</body>
</html>