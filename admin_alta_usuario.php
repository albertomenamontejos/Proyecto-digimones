<?php
include_once "funciones.php";
include_once "admin_usuario.class.php";

$nick = "";
$errorNick="";
$errorPass="";
$registro_confirmado="";

if (isset($_POST['btn_alta'])) {
    $nick = $_POST['nick'];
    $password = $_POST['password'];
    $password_rep = $_POST['password_rep'];

    if ($password != $password_rep) {
        $errorPass= '<span style="color:red;">Las contraseñas no coinciden</span>';
    } else if (strlen($nick) <= 3) {
        $errorNick= '<span style="color:red;">El nick tiene que tener mas de 3 caracteres.</span>';
    } else {
        $new_user= new Usuario($nick,$password);
        //Comprobar si el nick ya existe
   
        if($new_user->existe_user($new_user)){
            $errorNick='<span style="color:red;">Este nick ya existe.</span>';
        }else{ 
            $new_user->guardar_user($new_user);   
           $registro_confirmado='<span style="color:green;">Usuario registrado correctamente.</span>';
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
</head>
<body>
<nav id=menu>
<a href="admin_alta_usuario.php">Alta a usuario.</a><br>
<a href="admin_alta_digimon.php">Alta a digimon.</a><br>
<a href="admin_definir_evolucion.php">Definir evoluciones.</a><br>
<a href="admin_ver_digimon.php">Ver digimones</a>
</nav>
<h2>Dar de alta usuario</h2>

<form action="admin_alta_usuario.php" method="POST">
Nick:<input type="text" name="nick" id="nick" value="<?= $nick ?>"><?=$errorNick?><br>
Contraseña:<input type="password" name="password" id="password"><?=$errorPass?><br>
Repite la contraseña:<input type="password" name="password_rep" id="password_rep"><br>
<input type="submit" name="btn_alta" id="btn_alta" value="Enviar">
</form>
<a href="admin.php"><button>Volver</button></a>

<?=$registro_confirmado?>



</body>
</html>