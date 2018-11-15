<?php

require_once "clases/Usuario.class.php";
require_once "clases/Utilidades_user.class.php";

$nick = "";
$errorNick = "";
$errorPass = "";
$registro_confirmado = "";

if (isset($_POST['btn_alta'])) {
  $nick = $_POST['nick'];
  $password = $_POST['password'];
  $password_rep = $_POST['password_rep'];

  if ($password != $password_rep) {
    $errorPass = '<span style="color:red;">Las contraseñas no coinciden</span>';
  } else if (strlen($nick) <= 3 || trim($nick) == "") {
    $errorNick = '<span style="color:red;">El nick tiene que tener mas de 3 caracteres.</span>';
  } else {

        //Comprobar si el nick ya existe
    if (Utilidades_user::existe($nick)) {
      $errorNick = '<span style="color:red;">El nick  ' . $nick . ' ya existe.</span>';
    } else {
      $new_user = new Usuario($nick, $password);
           
            //Comprobar que existan suficientes digimones para asignar al usuario
      if (Utilidades_user::asignarDigimones($new_user, 3)) {
        $registro_confirmado = '<p style="color:green;" class="text-center">Usuario ' . $nick . ' registrado correctamente.</p>';
      } else {
        $registro_confirmado = '<p style="color:red;"  class="text-center">No se ha podido crear el usuario ' . $nick . '<br> se necesitan mínimo 3 Digimones de nivel 1.</p>';

      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dar de alta a un usuario.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


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
        <a class="nav-link" href="admin_alta_usuario.php"><i class="nav-link fas fa-home  d-block mb-1"></i>Inicio<span class="sr-only">(current)</span></a>
      </li>
    <ul class="navbar-nav mr-auto ">
      <li class="nav-item text-center  active">
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

<div class="container">
  <div class="row d-flex justify-content-center pt-5">
 
    <div class="col-9 p-3 border border-primary rounded p-5">
      <h2 class="text-center">Dar de alta usuario</h2>


<form action="admin_alta_usuario.php" method="POST" class="form-group">
<div class="p-5">
<p class="d-inline mr-5 ml-1" >Nick:</p><input type="text"  class=" ml-5 form-control d-inline w-50 m-2" name="nick" id="nick" value="<?= $nick ?>"><br><?= $errorNick ?><br>
<p class="d-inline mr-5" >Contraseña:</p><input type="password"  class=" ml-3 form-control d-inline w-50 m-3" name="password" id="password"><br><?= $errorPass ?><br>
<p class="d-inline" >Repite la contraseña:</p><input type="password"  class=" form-control d-inline w-50 m-2" name="password_rep" id="password_rep"><br>
<div class="text-center">

<?= $registro_confirmado ?>
<input type="submit"  class="btn btn-primary w-50 m-3 mt-5" name="btn_alta" id="btn_alta" value="Enviar">
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