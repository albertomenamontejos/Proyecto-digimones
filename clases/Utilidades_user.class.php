<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Usuario.class.php";
require_once "clases/Utilidades_dig.class.php";


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
//Devuelve true si se ha podido asignar 3 digimones
//Devuelve false si no ha podido asignar 3 digimones porque no hay suficientes.
	public static function asignarDigimones($new_user)
	{
		$nick_user = $new_user->getNick();
		$ruta_directorio =$new_user->getDirectorio_usuario();
		$ruta_txt = $new_user->getTxt_dig_usuario();
	
		//Para asignar digimones diferentes, contamos los digimones de digimones.txt.
		$fichero = fopen(DIGIMONES, "r");
		if ($fichero) {
			$asignado = false;
			$contador = 0;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$contador++;
			}

			if (!feof($fichero)) {
				$asignado = 'Error: fallo inesperado de fscanf()\na';
			} else if ($contador >= 3) {
			//Si se pueden asignar los digimones
			//Asignar digimones aleatoriamente.
				$asignado = true;
				$cont_digimones_asignados = 0;
				$digimones_asignados = array();

				while ($cont_digimones_asignados < 3) {
					rewind($fichero);
					
					$rand = random_int(0, $contador);
					$linea = 0;
					while ($digimoninfo = fscanf($fichero, "%s\n")) {
						if ($linea == $rand && !in_array($digimoninfo[0], $digimones_asignados)) {
							$digimones_asignados[] = $digimoninfo[0];
							$cont_digimones_asignados++;
						}
						$linea++;
					}

				}
			//Se crea el usuario y se guardan los datos en la cuenta del usuario
				Utilidades_user::guardar($new_user);
				for ($i = 0; $i < count($digimones_asignados); $i++) {
					$digimon = Utilidades::cadenaurl_a_obj($digimones_asignados[$i]);
					Utilidades_dig::guardar($digimon, $ruta_txt, $ruta_directorio);

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
	public static function user_con_equipo($usuario){
		$return=array();
		if(is_dir(USERDIR)){
			$directorio=opendir(USERDIR);
			
			while($directorioUser=readdir($directorio)){
			
				if($directorioUser!="." && $directorioUser!=".." && $directorioUser!=$usuario->getNick()){
				
					$carpetaUser=opendir(USERDIR.$directorioUser."/");
				
					while($contenido=readdir($carpetaUser)){
					
						if($contenido=="equipo-usuario.txt"){					
							$num_dig=Utilidades_dig::contar_dig(USERDIR.$directorioUser."/".$contenido);
							if($num_dig==3){					
								$return[$directorioUser]=Utilidades_dig::listar(USERDIR.$directorioUser."/".$contenido);
							}
						}
					}
				}
			}
		}else{
			echo "no encuentra directorio";
		}
		return $return;
	}

}