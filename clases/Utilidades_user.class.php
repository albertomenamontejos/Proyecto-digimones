<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Usuario.class.php";
require_once "clases/Utilidades_dig.class.php";
require_once "clases/Utilidades.class.php";


class Utilidades_user
{

//----------- PARA PAGINAS DE ADMIN -----------

//Busca en el fichero el usuario que se identifica con el nombre,
//devuelve el objeto usuario.
	public static function existe($nick)
	{

		$fichero = fopen(USUARIOS, "r");

		if ($fichero) {
			$encontrado = false;
			while ($usuarioinfo = fscanf($fichero, "%s\n")) {
				$usuario = Utilidades::cadenaurl_a_obj($usuarioinfo[0]);

				if ($usuario->getNick() == $nick) {
					$encontrado = $usuario;
				}
			}
			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\n';
			}
			fclose($fichero);
		}

		return $encontrado;
	}

//Guarda un usuario en el fichero usuarios.txt
//Devuelve false si hay algun problema.
	public static function guardar($new_user)
	{
		$fichero = fopen(USUARIOS, "a");
		$cadena_obj = Utilidades::obj_a_cadenaurl($new_user);
		if (is_writeable(USUARIOS)) {
			fwrite($fichero, $cadena_obj . "\n");
			$escrito = true;
		} else {
			$escrito = false;
		}
		if ($escrito) @mkdir(USERDIR . $new_user->getNick());
		fclose($fichero);
		return $escrito;
	}

//Comprueba que la contraseña del usuario
	public static function comprobar_pass($password)
	{

		$fichero = fopen(USUARIOS, "r");

		if ($fichero) {
			$encontrado = false;
			while ($usuarioinfo = fscanf($fichero, "%s\n")) {
				$usuario = Utilidades::cadenaurl_a_obj($usuarioinfo[0]);

				if ($usuario->getPassword() == $password) {
					$encontrado = true;
				}
			}
			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\n';
			}
			fclose($fichero);
		}

		return $encontrado;
	}

//Metodo para asignar digimones a un usuario concreto
//Devuelve true si se ha podido asignar el numero indicadicado por parametro de digimones
//Devuelve false si no ha podido asignar el numero indicado de digimones porque no hay suficientes.
//Se usa al dar de alta a un usuario (3 digimones) y cada vez que juega 10 partidas.
	public static function asignarDigimones($new_user, $numero)
	{
		$nick_user = $new_user->getNick();
		$ruta_directorio = $new_user->getDirectorio_usuario();
		$ruta_txt = $new_user->getTxt_dig_usuario();
		//Mirar los digimones de mi equipo (para que repitan cuando asignamos un digimon)
		$digimones_usuario = array();
		if (file_exists($ruta_txt)) {

			$fichero = fopen($ruta_txt, "r");
			if ($fichero) {
				$asignado = false;
				$contador = 0;
				$digimones_disponibles = array();//Los que tienen nivel 1
				while ($digimoninfo = fscanf($fichero, "%s\n")) {
					$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);
					if ($digimon->getNivel() == 1) {
						$digimones_usuario[] = $digimon;
					}
				}

				if (!feof($fichero)) {
					$asignado = 'Error: fallo inesperado de fscanf()\na';
				}
				fclose($fichero);
			}

		}
	
		$fichero = fopen(DIGIMONES, "r");
		if ($fichero) {
			$asignado = false;
			$contador = 0;
			$digimones_disponibles = array();//Los que tienen nivel 1
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);
				if ($digimon->getNivel() == 1 && !in_array($digimon, $digimones_usuario)) {
					$digimones_disponibles[] = $digimon;
					$contador++; //Saber el numero de digimones que hay
				}
			}

			if (!feof($fichero)) {
				$asignado = 'Error: fallo inesperado de fscanf()\na';
			} else if ($contador >= $numero) {
			//Si se pueden asignar los digimones
			//Asignar digimones aleatoriamente.
				$asignado = true;
				$cont_digimones_asignados = 0;
				$digimones_asignados = array();

				while (count($digimones_asignados) < $numero) {

					$rand = random_int(0, count($digimones_disponibles) - 1);
					if (!in_array($digimones_disponibles[$rand], $digimones_asignados)) {
						$digimones_asignados[] = $digimones_disponibles[$rand];
					}



				}
			//Se crea el usuario y se guardan los datos en la cuenta del usuario
				Utilidades_user::guardar($new_user);
				for ($i = 0; $i < count($digimones_asignados); $i++) {
					Utilidades_dig::guardar($digimones_asignados[$i], $ruta_txt, $ruta_directorio);
				}
			}
		}
		fclose($fichero);




		return $asignado;
	}

//Busca en cada carpeta de los usuarios el fichero equipos-usuario.txt
//Se comprueba que no sea el mismo que el usuario logueado
//Se comprueba que tenga el equipo ORGANIZADO, es decir, en 
//equipos-usuario.txt debe haber 3 objetos, para ello, se usa la funcion contar_dig();
	public static function user_con_equipo($usuario)
	{
		$return = array();
		if (is_dir(USERDIR)) {
			$directorio = opendir(USERDIR);

			while ($directorioUser = readdir($directorio)) {

				if ($directorioUser != "." && $directorioUser != ".." && $directorioUser != $usuario->getNick()) {

					$carpetaUser = opendir(USERDIR . $directorioUser . "/");

					while ($contenido = readdir($carpetaUser)) {

						if ($contenido == "equipo-usuario.txt") {
							$num_dig = Utilidades_dig::contar_dig(USERDIR . $directorioUser . "/" . $contenido);
							if ($num_dig == 3) {
								$return[$directorioUser] = Utilidades_dig::listar(USERDIR . $directorioUser . "/" . $contenido);
							}
						}
					}
				}
			}
		} else {
			echo "no encuentra directorio";
		}
		return $return;
	}

	
//Leer partidas del usuario
	public static function leerPartidas($usuario)
	{
		$ganadas = 0;
		$perdidas = 0;
		$empatadas = 0;
		$digievoluciones = 0;
		if (file_exists($usuario->getTxt_registro())) {
			$fichero = fopen($usuario->getTxt_registro(), "r");
			$tamaño = filesize($usuario->getTxt_registro());
			if ($tamaño != 0) {
				$registro = explode("\n", fread($fichero, $tamaño));
				$ganadas = $registro[0];
				$perdidas = $registro[1];
				$empatadas = $registro[2];
				$digievoluciones = $registro[3];
			}
		}
		return $array = [$ganadas, $perdidas, $empatadas, $digievoluciones];

	}

//Listar usuarios para admin.php devuelve un array de objetos usuario.
	public static function listarUsuarios(){
		$usuarios=array();
		
		if(file_exists(USUARIOS)){
			$fichero=fopen(USUARIOS,"r");
			while(!feof($fichero)){
				$handler=fgets($fichero);
				$separador=strpos($handler,"\n",0);
				$cadena=substr($handler,0,$separador);
				$usuarios[]=Utilidades::cadenaurl_a_obj($cadena);
			}
			fclose($fichero);
		}
		return $usuarios;
	} 
}