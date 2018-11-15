<?php

require_once "clases/Utilidades.class.php";
require_once "clases/Digimon.class.php";
require_once "clases/Utilidades_dig.class.php";

$nom_digimon = "";
$ataque = "";
$defensa = "";
$errorNombre = "";
$errorAtaque = "";
$errorDefensa = "";
$registro_confirmado = "";
if (isset($_POST['btn-alta'])) {
  $nom_digimon = $_POST['nom_digimon'];
  $ataque = $_POST['ataque'];
  $defensa = $_POST['defensa'];
  $tipo = $_POST['tipo'];
  $nivel = $_POST['level'];
  $new_digimon = new Digimon($nom_digimon, $ataque, $defensa, $tipo, $nivel);
  $errores = Utilidades_dig::erroresDatos($new_digimon);
  if (empty($errores)) {
    Utilidades_dig::guardar($new_digimon, DIGIMONES, DIGIMONESDIR);
    $registro_confirmado = '<p class="text-center text-success">Digimon '.$new_digimon->getNombre() .' añadido correctamente</p>';
  } else {
    foreach ($errores as $key => $error) {
      $$key = $error;
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
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

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
        <a class="nav-link" href="admin_alta_usuario.php"><i class="nav-link fas fa-home  d-block mb-1"></i>Inicio<span class="sr-only">(current)</span></a>
      </li>
    <ul class="navbar-nav mr-auto ">
      <li class="nav-item text-center">
        <a class="nav-link" href="admin_alta_usuario.php"><i class="nav-link far fa-user  d-block mb-1"></i>Alta a usuario<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item text-center active">
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

<div class="container">
  <div class="row d-flex justify-content-center pt-5">
 
    <div class="col-9 p-3 border border-primary rounded d-flex flex-column justify-content-center">
      <h2 class="text-center">Dar de alta digimon</h2>

      

      <form action="admin_alta_digimon.php" method="POST" class="form-group">
      <div class="text-center">
        <p class="d-inline" >Nombre</p> <input type="text" class="form-control d-inline w-50 m-2" name="nom_digimon" id="nom_digimon" value="<?= $nom_digimon ?>">
        <br><?= $errorNombre ?>
        <p class="d-inline">Ataque</p> <input type="number" class="form-control  d-inline w-50 m-2"  name="ataque" id="ataque" value="<?= $ataque ?>">
        <br><?= $errorAtaque ?>
        <p class="d-inline">Defensa</p> <input type="number" class="form-control  d-inline w-50 m-2"  name="defensa" id="defensa" value="<?= $defensa ?>">
        <br><?= $errorDefensa ?>
        <p class="d-inline">Tipo</p>  <select name="tipo" id="tipo" class="form-control  d-inline w-50 m-2" >

                <option value="vacuna" <?php if(isset($_POST['btn-alta']) && $_POST['tipo']=="vacuna")echo "selected";?>>Vacuna</option>
                <option value="virus" <?php if(isset($_POST['btn-alta']) && $_POST['tipo']=="virus")echo "selected";?>>Virus</option>
                <option value="animal" <?php if(isset($_POST['btn-alta']) && $_POST['tipo']=="animal")echo "selected";?>>Animal</option>
                <option value="planta" <?php if(isset($_POST['btn-alta']) && $_POST['tipo']=="planta") echo "selected";?>>Planta</option>
                <option value="elemental" <?php if(isset($_POST['btn-alta']) && $_POST['tipo']=="elemental")echo "selected";?>>Elemental</option>
            </select> <br>
        <p class="d-inline"> Nivel:</p><select name="level" id="level" class="form-control  d-inline w-50 m-2" >
        <option value="1" <?php if(isset($_POST['btn-alta']) && $_POST['level']=="1")echo "selected";?> >1</option>
        <option value="2" <?php if(isset($_POST['btn-alta']) && $_POST['level']=="2")echo "selected";?>>2</option>
        <option value="3" <?php if(isset($_POST['btn-alta']) && $_POST['level']=="3") echo "selected";?>>3</option>
        </select><br>
        </div>
        <?= $registro_confirmado ?>
        <div class="text-center">
          <input type="submit" name="btn-alta" class="btn btn-primary w-50 m-3" value="Enviar">
        </div>
     
      </form>
 
     
    </div>
  </div>
</div>



	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>