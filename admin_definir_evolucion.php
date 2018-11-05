<?php
include_once "funciones.php";
include_once "admin_digimon.class.php";

$nom_digimon = "";
$ataque = "";
$defensa = "";
$errorNombre = "";
$errorAtaque = "";
$errorDefensa = "";
$registro_confirmado = "";
$array_digimones_nivel1 = Digimon::digimones_evolucionables(1);
$array_digimones_nivel2 = Digimon::digimones_evolucionables(2);
$view_caja_evolucion=false;
if (isset($_POST['btn-evolucionado'])) {
    $digimon_original = Digimon::buscar_digimon($_POST['digimones']);
    $digimon_original->setEvolucion($_POST['nombreEvolucion']);
    Digimon::sobreescribir_digimon($digimon_original);
    $errores = Digimon::erroresDatos($_POST['nombreEvolucion'], $_POST['ataqueEvolucion'], $_POST['defensaEvolucion']);
    if (empty($errores)) {
        $new_digimon = new Digimon($_POST['nombreEvolucion'], $_POST['ataqueEvolucion'], $_POST['defensaEvolucion'], $_POST['tipoEvolucion'], $_POST['nivelEvolucion']);
        $new_digimon->guardar_digimon($new_digimon);
        $registro_confirmado = '<span style="color:green;">Digimon añadido correctamente</span>';
        $array_digimones_nivel1 = Digimon::digimones_evolucionables(1);
        $array_digimones_nivel2 = Digimon::digimones_evolucionables(2);
        $view_caja_evolucion=false;
    } else {
        $view_caja_evolucion=true;
        $nom_digimon = $_POST['nombreEvolucion'];
        $ataque = $_POST['ataqueEvolucion'];
        $defensa = $_POST['defensaEvolucion'];
        foreach ($errores as $key => $error) {
            $$key = $error;
        }
    }
}else if (isset($_POST['btn-nivel1'])){
    $view_caja_evolucion=true;
}else if (isset($_POST['btn-nivel2'])){
    $view_caja_evolucion=true;
}else{
    $view_caja_evolucion=false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Definir evolución</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
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

<h2>Definir devoluciones</h2>
<?php 


if (empty($array_digimones_nivel1)) {
    echo "<span style='color:red;'> No hay digimones disponibles del nivel 1.</span><br>";
} else {
    ?>
<p>Seleccionar Digimon: </p>
<form action="admin_definir_evolucion.php" method="post">
    Digimones del nivel 1: <select name="digimones" id="digimones">
    <?php
    for ($i = 0; $i < count($array_digimones_nivel1); $i++) {
        echo "<option value='" . $array_digimones_nivel1[$i]->getNombre() . "'>" . $array_digimones_nivel1[$i]->getNombre() . "</option>";
    }


?>
 
    </select>
    <input type="submit" name="btn-nivel1" value="Definir evolución">
</form>

<?php
}
if (empty($array_digimones_nivel2)) {
    echo "<span style='color:red;'> No hay digimones disponibles del nivel 2.</span><br>";
} else {
    ?>
<form action="admin_definir_evolucion.php" method="post">
    Digimones del nivel 2: <select name="digimones" id="digimones">
    <?php
    for ($i = 0; $i < count($array_digimones_nivel2); $i++) {
        echo "<option value='" . $array_digimones_nivel2[$i]->getNombre() . "'>" . $array_digimones_nivel2[$i]->getNombre() . "</option>";
    }

    ?>
 
    </select>
    <input type="submit" name="btn-nivel2" value="Definir evolución">

</form>
<?php 
}
if ($view_caja_evolucion) {
    $digimon_original = Digimon::buscar_digimon($_POST['digimones']);
    ?>
<h4>Digimon original: </h4>
<img class='imagen-info' src="<?= $digimon_original->getImagen() ?>" alt="">
<p>Nombre: <?= $digimon_original->getNombre() ?></p>
<p>Ataque: <?= $digimon_original->getAtaque() ?></p>
<p>Defensa: <?= $digimon_original->getDefensa() ?></p>
<p>Tipo: <?= $digimon_original->getTipo() ?></p>
<p>Nivel: <?= $digimon_original->getNivel() ?></p>


<form action="admin_definir_evolucion.php" method="POST">
<h4>Digimon evolucionado:</h4>
Nombre: <input type="text" name="nombreEvolucion" id="nombreEvolucion" value="<?= $nom_digimon ?>"><?= $errorNombre ?><br>
Ataque: <input type="text" name="ataqueEvolucion" id="ataqueEvolucion" value="<?= $ataque ?>"><?= $errorAtaque ?><br>
Defensa: <input type="text" name="defensaEvolucion" id="defensaEvolucion" value="<?= $defensa ?>"><?= $errorDefensa ?><br>
Tipo: <select name="tipo" id="tipo" disabled>
    <option value="<?= $digimon_original->getTipo() ?>"><?= $digimon_original->getTipo() ?></option>
</select><br>
Nivel: <input type="text" name="nivelEvolucionado" id="nivelEvolucionado" value="<?= $digimon_original->getNivel() + 1 ?>" disabled>
<br>
<input type="submit" name="btn-evolucionado" name="btn-evolucionado">

<input type="hidden" name="digimones" value="<?= $_POST['digimones'] ?>">
<input type="hidden" name="tipoEvolucion" value="<?= $digimon_original->getTipo() ?>">
<input type="hidden" name="nivelEvolucion" value="<?= $digimon_original->getNivel() + 1 ?>">

</form>
<?php

echo $registro_confirmado;

}


?>
</body>
</html>