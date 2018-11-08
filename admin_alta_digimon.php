<?php

require_once "clases/Utilidades.class.php";
require_once "clases/Digimon.class.php";
require_once "clases/Utilidades_dig.class.php";



$nom_digimon = "";
$ataque = "";
$defensa = "";
$errorNombre="";
$errorAtaque="";
$errorDefensa ="";
$registro_confirmado="";
if (isset($_POST['btn-alta'])) {
    $nom_digimon = $_POST['nom_digimon'];
    $ataque = $_POST['ataque'];
    $defensa = $_POST['defensa'];
    $tipo = $_POST['tipo'];
    $nivel = $_POST['level'];
    $errores=Utilidades_dig::erroresDatos($nom_digimon, $ataque, $defensa);
    if (empty($errores)) {
        $new_digimon = new Digimon($nom_digimon, $ataque, $defensa, $tipo, $nivel);
        Utilidades_dig::guardar($new_digimon,DIGIMONES,DIGIMONESDIR);
        $registro_confirmado='<span style="color:green;">Digimon añadido correctamente</span>';
    } else {
        foreach ($errores as $key => $error){
            $$key=$error;
        }
    }
} 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Alta digimon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<body>
<nav id=menu>
<a href="admin_alta_usuario.php">Alta a usuario.</a><br>
<a href="admin_alta_digimon.php">Alta a digimon.</a><br>
<a href="admin_definir_evolucion.php">Definir evoluciones.</a><br>
<a href="admin_ver_digimon.php">Ver digimones</a>
</nav>

<h2>Dar de alta digimon</h2>

<form action="admin_alta_digimon.php" method="POST">
Nombre<input type="text" name="nom_digimon" id="nom_digimon" value="<?= $nom_digimon ?>"><?= $errorNombre?><br>
Ataque<input type="number" name="ataque" id="ataque" value="<?= $ataque ?>"><?= $errorAtaque?><br>
Defensa<input type="number" name="defensa" id="defensa" value="<?= $defensa ?>"><?= $errorDefensa?><br>
Tipo <select name="tipo" id="tipo">
        <option value="vacuna">Vacuna</option>
        <option value="virus">Virus</option>
        <option value="animal">Animal</option>
        <option value="planta">Planta</option>
        <option value="elemental">Elemental</option>
    </select> <br>
Nivel:<select name="level" id="level">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
</select><br>
<input type="submit" name="btn-alta" id="btn-alta" value="Enviar">
</form>


<a href="admin.php"><button>Volver</button></a>
<?=$registro_confirmado?>

</body>
</html>