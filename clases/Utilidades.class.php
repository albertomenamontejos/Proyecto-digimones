<?php
//VARIABLES
define('USUARIOS', "usuarios.txt");
define('DIGIMONES', "digimones.txt");
define('USERDIR', "usuarios/");
define('DIGIMONESDIR', "digimones/");

class Utilidades
{

//Funcion para transformar un array en cadena.
	public static function obj_a_cadenaurl($obj)
	{
		$cadenatmp = serialize($obj);
		$cadena = urlencode($cadenatmp);
		return $cadena;
	} 

//Funcion para transformar una cadena en un array.
	public static function cadenaurl_a_obj($texto)
	{
		$obj = new stdClass();//Crea objeto vacio.
		if ($texto != "") {
			$texto = stripslashes($texto);
			$texto = urldecode($texto);
			$obj = unserialize($texto);
		}
		return $obj;
	}

//Imprime el menu de la parte de usuario
	public static function menu($usuario)
	{
		$usuario_cad = Utilidades::obj_a_cadenaurl($usuario);
		return '<div class="row">
			<div class="col-12 ">
				<div class="bg-menu">
					<div class="col-12 text-center">
						<div class="row">
							<div class="col-7 d-flex justify-content-end pr-4">
								<img class="logo mr-5" src="img/logo.png" alt="">
							</div>
							<div class="col-5 mt-5 d-flex justify-content-end">
								<p class="text-white mr-4">Usuario:   ' . $usuario->getNick() . '</p>
								<a href="index.php" class="text-warning">Cerrar sesion</a>					
							</div>
						</div>
					</div>
					<div  class="col-12  text-center">
						<nav class="navbar navbar-dark bg-menu text-white">
						<form class="navbar-brand" method="post" action="inicio_usuario.php">
								<input type="submit" name="otra_pagina" value="Inicio" class="btn border-0 bg-transparent text-white " >
								<input type="hidden" name="usuario_cad" value="' . $usuario_cad . '">	
							</form>	
							<form class="navbar-brand" method="post" action="ver_mis_digimon.php">
								<input type="submit" name="otra_pagina" value="Ver mis digimon" class="btn border-0 bg-transparent text-white" >
								<input type="hidden" name="usuario_cad" value="' . $usuario_cad . '">	
							</form>	
							<form class="navbar-brand" method="post" action="organizar_equipo.php">
								<input type="submit" name="otra_pagina" value="Organizar equipo" class="btn border-0 bg-transparent text-white" >
								<input type="hidden" name="usuario_cad" value="' . $usuario_cad . '">	
							</form>	
							<form class="navbar-brand" method="post" action="jugar_partida.php">
								<input type="submit" name="otra_pagina" value="Jugar partida" class="btn border-0 bg-transparent text-white" >
								<input type="hidden" name="usuario_cad" value="' . $usuario_cad . '">	
							</form>	
							<form class="navbar-brand" method="post" action="digievolucionar.php">
								<input type="submit" name="otra_pagina" value="Digievolucionar" class="btn border-0 bg-transparent text-white" >
								<input type="hidden" name="usuario_cad" value="' . $usuario_cad . '">	
							</form>			
						</nav>
					</div>
				</div>
			</div>
		</div>';
	}
}