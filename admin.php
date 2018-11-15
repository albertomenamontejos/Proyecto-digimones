<?php
require_once "clases/Utilidades_user.class.php";
require_once "clases/Usuario.class.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <title>Administracion de la pagina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">   
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
      <li class="nav-item text-center active ml-5">
        <a class="nav-link" href="admin_alta_usuario.php"><i class="nav-link fas fa-home  d-block mb-1"></i>Inicio<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item text-center">
        <a class="nav-link" href="admin_alta_usuario.php"><i class="nav-link far fa-user  d-block mb-1"></i>Alta a usuario<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item text-center">
        <a class="nav-link" href="admin_alta_digimon.php"> <i class="nav-link fas fa-paw  d-block mb-1"></i>Alta a digimon</a>
      </li>
      <li class="nav-item text-center ">
        <a class="nav-link" href="admin_definir_evolucion.php"> <i class="fas fa-long-arrow-alt-up nav-link d-block mb-1"></i>Definir evoluciones</a>
	  </li>
	  <li class="nav-item text-center ">
        <a class="nav-link" href="admin_ver_digimon.php"> <i class="far fa-eye nav-link  d-block mb-1"></i>Ver digimones</a>
      </li>
    </ul>

  </div>
</nav>
<?php

$usuarios=Utilidades_user::listarUsuarios();
$num_usuarios=0;
?>
<section class="container p-5">
<h3 class="text-center pb-4">INFORMACIÓN DE USUARIOS</h3>
  <div class="row   d-flex flex-row justify-content-around">
      <div class="col-7 ">
            <table class="table table-light">
            <tr>
            <th>
            Nombre Usuario</th>
            <th>Contraseña</th>
            </tr>
            <?php 
            for($i=0;$i<count($usuarios);$i++){
              if($usuarios[$i]instanceof Usuario){
                echo "<tr><td>".$usuarios[$i]->getNick()."</td><td>".$usuarios[$i]->getPassword()."</td></tr>";
                $num_usuarios++;
              }
            }
            ?>
            </table>
      </div>

      <div class="col-4">
      <div class="card card-stats">
				<div class="card-header bg-success">

				<h3 class="card-title text-center text-white"><?=$num_usuarios?></h3>
				</div>
				<div class="card-footer">
				<div class="stats">
				<h4 class="card-category">NUMERO DE USUARIOS</h4>
				</div>
				</div>
			</div>
      </div>
  </div>
</section>
<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>