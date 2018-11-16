<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Digimon.class.php";

$nom_digimon = "";
$ataque = "";
$defensa = "";
$errorNombre = "";
$errorAtaque = "";
$errorDefensa = "";
$registro_confirmado = "";
$mensajeConfirmacion = "";
$array_digimones_nivel1 = Utilidades_dig::listar(DIGIMONES, 1);
$array_digimones_nivel2 = Utilidades_dig::listar(DIGIMONES, 2);
$view_caja_evolucion = false;
if (isset($_POST['btn-evolucionado'])) { //VIENE DE CREAR UN NUEVO DIGIMON
    $digimon_creado = Utilidades::cadenaurl_a_obj($_POST['digimon_original_cad']);
    $digimon_original = Utilidades::cadenaurl_a_obj($_POST['digimon_original_cad']);
    $new_digimon = new Digimon($_POST['nombreEvolucion'], $_POST['ataqueEvolucion'], $_POST['defensaEvolucion'], $digimon_original->getTipo(), $digimon_original->getNivel() + 1);
    $errores = Utilidades_dig::erroresDatos($new_digimon);
    if (empty($errores)) {
        $digimon_original->setEvolucion($_POST['nombreEvolucion']);
        Utilidades_dig::sobreescribir($digimon_original, DIGIMONES);
        Utilidades_dig::guardar($new_digimon, DIGIMONES, DIGIMONESDIR);
        $registro_confirmado = '<span style="color:green;">Digimon añadido correctamente</span>';
        $mensajeConfirmacion = "<p class='text-success text-center'>DIGIEVOLUCION ASIGNADA CORRECTAMENTE (".$digimon_original->getNombre()." evolucionará a ".$digimon_original->getEvolucion().")</p>";
        $array_digimones_nivel1 = Utilidades_dig::listar(DIGIMONES, 1);
        $array_digimones_nivel2 = Utilidades_dig::listar(DIGIMONES, 2);
        $view_caja_evolucion = false;
    } else {
        $view_caja_evolucion = true;
        $nom_digimon = $_POST['nombreEvolucion'];
        $ataque = $_POST['ataqueEvolucion'];
        $defensa = $_POST['defensaEvolucion'];
        foreach ($errores as $key => $error) {
            $$key = $error;
        }
    }
} else if (isset($_POST['elegir_digimon'])) {//VIENE DE ELEGIR UN DIGIMON EXISTENTE
    $digimon_elegido = Utilidades::cadenaurl_a_obj($_POST['elegido_cad']);
    $digimon_original = Utilidades::cadenaurl_a_obj($_POST['original_cad']);
    $digimon_original->setEvolucion($digimon_elegido->getNombre());
    Utilidades_dig::sobreescribir($digimon_original, DIGIMONES);
    $mensajeConfirmacion = "<p class='text-success text-center'>DIGIEVOLUCION ASIGNADA CORRECTAMENTE (".$digimon_original->getNombre()." evolucionará a ".$digimon_original->getEvolucion().")</p>";
    $array_digimones_nivel1 = Utilidades_dig::listar(DIGIMONES, 1);
    $array_digimones_nivel2 = Utilidades_dig::listar(DIGIMONES, 2);

} else if (isset($_POST['btn-nivel1'])) {
    $digimon_original = Utilidades::cadenaurl_a_obj($_POST['digimon_original_cad']);
    $view_caja_evolucion = true;
} else if (isset($_POST['btn-nivel2'])) {
    $digimon_original = Utilidades::cadenaurl_a_obj($_POST['digimon_original_cad']);
    $view_caja_evolucion = true;
} else if (isset($_POST['eliminar'])) {
    $digimon_original = Utilidades::cadenaurl_a_obj($_POST['dig_eliminar']);
    $digimon_original->setEvolucion(null);
    Utilidades_dig::sobreescribir($digimon_original, DIGIMONES);
    $array_digimones_nivel1 = Utilidades_dig::listar(DIGIMONES, 1);
    $array_digimones_nivel2 = Utilidades_dig::listar(DIGIMONES, 2);
    $view_caja_evolucion = true;
} else {
    $view_caja_evolucion = false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Definir evolución</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="css/admin-style.css">

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
      <li class="nav-item text-center active">
        <a class="nav-link" href="admin_definir_evolucion.php"> <i class="fas fa-long-arrow-alt-up nav-link d-block mb-1"></i>Definir evoluciones</a>
	  </li>
	  <li class="nav-item text-center">
        <a class="nav-link" href="admin_ver_digimon.php"> <i class="far fa-eye nav-link  d-block mb-1"></i>Ver digimones</a>
      </li>
    </ul>

  </div>
</nav>

<section class="row">
    <div class="col border border-secondary m-4 rounded">
    <h2 class="text-center m-1 text-secondary p-3">Definir evoluciones</h2>
    <div class="row d-flex justify-content-around">
    <div class="col pl-5 ml-5">
<?php 
// SELECT DIGIMONES NIVEL 1
if (empty($array_digimones_nivel1)) {
    echo "<p class='text-danger text-center'> No hay digimones disponibles del nivel 1.</span><br>";
} else {
    ?>
            <form action="admin_definir_evolucion.php" method="post" class="form-group">
               <p>Digimones del nivel 1:</p>    <select name="digimon_original_cad" id="digimones" class="form-control w-50 d-inline">
                <?php
                for ($i = 0; $i < count($array_digimones_nivel1); $i++) {
                    if (isset($_POST['btn-nivel1'])) {
                        if ($digimon_original->getNombre() == $array_digimones_nivel1[$i]->getNombre()) {
                            echo "<option value='" . Utilidades::obj_a_cadenaurl($array_digimones_nivel1[$i]) . "' selected>" . $array_digimones_nivel1[$i]->getNombre() . "</option>";
                        } else {
                            echo "<option value='" . Utilidades::obj_a_cadenaurl($array_digimones_nivel1[$i]) . "'>" . $array_digimones_nivel1[$i]->getNombre() . "</option>";
                        }
                    } else {
                        echo "<option value='" . Utilidades::obj_a_cadenaurl($array_digimones_nivel1[$i]) . "'>" . $array_digimones_nivel1[$i]->getNombre() . "</option>";
                    }
                }
                ?>
                </select>
                <div class="d-inline ml-3 "><input type="submit" name="btn-nivel1" class="btn btn-primary " value="Definir evolución"></div>
            </form>
<?php

}
echo "</div>";
echo " '<div class='col p-2'>";
// SELECT DIGIMONES NIVEL 2
if (empty($array_digimones_nivel2)) {
    echo "<p class='text-danger p-3'> No hay digimones disponibles del nivel 2.</p><br>";
} else {
    ?>
     <form action="admin_definir_evolucion.php" method="post" class="form-group">
           <p > Digimones del nivel 2: </p>  <select name="digimon_original_cad" id="digimones" class="form-control w-50 d-inline">
            <?php
            for ($i = 0; $i < count($array_digimones_nivel2); $i++) {
                if (isset($_POST['btn-nivel2'])) {
                    if ($digimon_original->getNombre() == $array_digimones_nivel2[$i]->getNombre()) {
                        echo "<option value='" . Utilidades::obj_a_cadenaurl($array_digimones_nivel2[$i]) . "' selected>" . $array_digimones_nivel2[$i]->getNombre() . "</option>";
                    } else {
                        echo "<option value='" . Utilidades::obj_a_cadenaurl($array_digimones_nivel2[$i]) . "'>" . $array_digimones_nivel2[$i]->getNombre() . "</option>";
                    }
                } else {
                    echo "<option value='" . Utilidades::obj_a_cadenaurl($array_digimones_nivel2[$i]) . "'>" . $array_digimones_nivel2[$i]->getNombre() . "</option>";
                }
            }
            ?>
        </select>
        <div class="d-inline ml-3"> <input type="submit" name="btn-nivel2" class="btn btn-primary" value="Definir evolución"></div>
       
    </form>
    </div>
<?php 
}
?>
    </div>
</section>
<?= $mensajeConfirmacion ?>
<?php
// CONTENIDO PRINCIPAL - DIGIMON ELEGIDO PARA EVOLUCIONAR
if ($view_caja_evolucion) {


    ?>

    <div class="row d-flex justify-content-around p-3 align-items-end">
        <div class="col-4 border border-secondary p-2 rounded ">
            <h4 class="text-center bg-primary p-2 text-white w-100">Digimon elegido: </h4>
            <div class="text-center"> <img class='imagen-info ' src="<?= $digimon_original->getImagen() ?>" alt=""></div>
          <div class="pl-4 pr-4 pt-4">
              <table class="table">
                  <?php
                    if ($digimon_original->getEvolucion() == null) {
                        $evolucion = "<span style='color:red;'> No definida</span>";
                    } else {
                        $evolucion = $digimon_original->getEvolucion();
                    }
                    $digimon_original_cad = Utilidades::obj_a_cadenaurl($digimon_original);
                    ?>
                  <tr><th>Nombre</th><td><?= $digimon_original->getNombre() ?></td></tr>
                  <tr><th>Ataque</th><td><?= $digimon_original->getAtaque() ?></td></tr>
                  <tr><th>Defensa</th><td> <?= $digimon_original->getDefensa() ?></td></tr>
                  <tr><th>Tipo</th><td><?= $digimon_original->getTipo() ?></td></tr>
                  <tr><th>Nivel</th><td> <?= $digimon_original->getNivel() ?></td></tr>
                  
                  <tr><th>Evoluciona a</th><td> <?= $evolucion ?></td><td>
                      <?php
                       if ($digimon_original->getEvolucion() != null) {
                      ?>
                      <form action="admin_definir_evolucion.php" method="POST">
                          <input type="hidden" name="dig_eliminar" value="<?=$digimon_original_cad?>">
                             <blockquote><input type="submit" name="eliminar" value="Eliminar" class="btn bg-transparent border border-0 text-danger " ></blockquote>
                      </form>
                      <?php
                       }
                      ?>
                </td></tr>
              </table>
          </div>  
</div>
<!-- FORMULARIO CREA UN DIGIMON NUEVO AL QUE VA A EVOLUCIONAR EL DIGIMON ELEGIDO -->
        <div class="col-6">

            <form action="admin_definir_evolucion.php" method="POST" class="form-group" >
         
            <h4 class="pb-5">Crear nuevo digimon:</h4>
            Nombre: <input type="text" class="form-control" name="nombreEvolucion" id="nombreEvolucion" value="<?= $nom_digimon ?>"><?= $errorNombre ?><br>
            Ataque: <input type="text" class="form-control"  name="ataqueEvolucion" id="ataqueEvolucion" value="<?= $ataque ?>"><?= $errorAtaque ?><br>
            Defensa: <input type="text"  class="form-control" name="defensaEvolucion" id="defensaEvolucion" value="<?= $defensa ?>"><?= $errorDefensa ?><br>
            Tipo: <cite class="text-danger ml-1">  No se puede modificar</cite><select name="tipo" class="form-control" id="tipo" disabled>
                <option value="<?= $digimon_original->getTipo() ?>"><?= $digimon_original->getTipo() ?></option>
            </select><br>
            Nivel:  <cite class="text-danger ml-1">  No se puede modificar</cite><input type="text" class="form-control"  name="nivelEvolucionado" id="nivelEvolucionado" value="<?= $digimon_original->getNivel() + 1 ?>" disabled>
            <br>
            <input type="submit" class="btn btn-primary w-100" value="Crear digimon" name="btn-evolucionado" name="btn-evolucionado">
            <?php
            $digimon_original_cad = Utilidades::obj_a_cadenaurl($digimon_original);
            ?>
            <input type="hidden" name="digimon_original_cad" value="<?= $digimon_original_cad ?>">
       
            </form>
        </div>

    </div>
<!-- SECCION PARA ELEGIR UN DIGIEVOLUCIONAR A UN DIGIMON QUE YA EXISTE -->
    <div class="row p-3">
    <div class="col">
        <h3>ELEGIR UN DIGIMON EXISTENTE</h3>
        <div class="container-fluid p-0">
	<div class="d-flex flex-nowrap m-3">
        
        <?php
        $nivel = $digimon_original->getNivel();
        $array_digimones = Utilidades_dig::listar(DIGIMONES, $nivel + 1);
        $imprimido = false;
        for ($i = 0; $i < count($array_digimones); $i++) {

            if ($array_digimones[$i]->getTipo() == $digimon_original->getTipo()) {

                $imprimido = true;
                if ($array_digimones[$i]->getEvolucion() == null) {
                    $evolucion = "<span style='color:red;'> No definida</span>";
                } else {
                    $evolucion = $array_digimones[$i]->getEvolucion();
                }
                echo "<div class='m-2 rounded  border border-dark bg-dark '>";
                echo "<form action='admin_definir_evolucion.php' method='POST'>";
                echo "<table class='table table-light mt-2 mb-2'>";
                echo "<tr>
		<td class='text-center bg-white ' colspan='2'><img class='imagen-info img-tabla m-3' src='" . $array_digimones[$i]->getImagen() . "' alt=''></td></tr>
		<tr><th>Nombre</th><td>" . $array_digimones[$i]->getNombre() . "</td></tr>
		<tr><th>Ataque</th><td>" . $array_digimones[$i]->getAtaque() . "</td></tr>
		<tr><th>Defensa</th><td>" . $array_digimones[$i]->getDefensa() . "</td></tr>
		<tr><th>Tipo</th><td>" . $array_digimones[$i]->getTipo() . "</td></tr>
        <tr><th>Nivel</th><td>" . $array_digimones[$i]->getNivel() . "</td></tr>
        <tr><th>Evoluciona a</th><td>" . $evolucion . "</td></tr>
        </tr>";
                echo "</table>";
                $elegido_cad = Utilidades::obj_a_cadenaurl($array_digimones[$i]);
                $original_cad = Utilidades::obj_a_cadenaurl($digimon_original);
                ?>
          <input type="hidden" name="original_cad" value=<?= $original_cad ?>>
        <input type="hidden" name="elegido_cad" value=<?= $elegido_cad ?>>
        <input type='submit' name='elegir_digimon' value='Elegir digimon' class='btn btn-success w-100 rounded-0'>
    </div>
     </form>
     <?php

}
}
if (!$imprimido) {
    echo "<div class='m-2 rounded  border border-dark bg-light text-center p-3 text-info'>";
    echo "<h5>NO HAY DIGIMONES DISPONIBLES PARA ESTE NIVEL Y TIPO DE DIGIMON, PUEDES CREAR UNO NUEVO DESDE AQUÍ, AL DIGIMON ELEGIDO SE LE ASIGNARÁ LA DIGIEVOLUCION DEL NUEVO DIGIMON CREADO.</h5>";
    echo "</div>";
}
?>
    </div>
    </div>
<?php
echo $registro_confirmado;
}
?>
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>