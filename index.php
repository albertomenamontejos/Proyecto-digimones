<?php
require "admin_usuario.class.php";
$errorUsuario="";
$errorPass="";
if(isset($_POST['btn-enviar'])){
    $usuario=new Usuario($_POST['nick'],$_POST['password']);
    if($usuario->existe_user($usuario)){  
        if($usuario->comprobar_pass($usuario)){
            header("Location: inicio_usuario.php");
        }else{
            $errorPass="<span style='color:red'>Contraseña inválida</span>";
        }
    }else{
        $errorUsuario="<span style='color:red'>No se ha encontrado ningun usuario con ese nick.</span>";
    }



}

?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Document</title>
	<style>
	.row >div{
		background: #ccc;
	
	}
	</style>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-12 ">
                <form action="index.php" method="post">
                    <label for="usuario">Introduzca su usuario: <input type="text" name="nick" id="nick"></label><?=$errorUsuario?>
                    <label for="pass">Introduzca su contraseña: <input type="password" name="password" id="password"></label><?=$errorPass?>
                    <input type="submit" class="btn btn-primary" name="btn-enviar" value="Entrar">
                </form>

			</div>
		</div>
	</div>


	<script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
	
</body>
</html>