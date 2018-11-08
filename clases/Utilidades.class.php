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


	public static function menu($usuario)
	{
		return '<div class="row>
		<div class="col-12 ">
			<div class="bg-dark">
				<div class="col-12 text-center">
					<div class="row">
						<div class="col-7 d-flex justify-content-end">
							<img class="logo" src="img/logo.png" alt="">
						</div>
						<div class="col-5 mt-5 d-flex justify-content-end">
							<p class="text-white mr-4">Usuario:' . $usuario->getNick() . '</p>
							<a href="index.php">Cerrar sesion</a>					
						</div>
					</div>
				</div>
				<div class="col-12  text-center">
					<nav class="navbar navbar-dark bg-dark text-white">
						<a class="navbar-brand" href="ver_mis_digimon.php?nick=' . $usuario->getNick() . '">Ver mis digimon</a>			
						<a class="navbar-brand" href="organizar_equipo.php?nick=' . $usuario->getNick() . '">Organizar equipo</a>			
						<a class="navbar-brand" href="jugar_partida.php?nick=' . $usuario->getNick() . '">Jugar partida</a>		
						<a class="navbar-brand" href="digievolucionar.php?nick=' . $usuario->getNick() . '">Digievolucionar</a>
					</nav>
				</div>
			</div>
		</div>';
	}

}