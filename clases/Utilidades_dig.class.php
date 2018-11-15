<?php
require_once "clases/Utilidades.class.php";

class Utilidades_dig
{

//Buscar por nombre un digimon en un fichero especifico 
//Se usa en la parte de administrador como en la parte del usuario
//Puede buscar un digimon si pasas un objeto
//Devuelve el digimon encontrado o false
	public static function buscar($fichero, $digimon_original = false, $nom_digimon = false)
	{
		$fichero = fopen($fichero, "r");
		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);
				if (!$nom_digimon) {
					if ($digimon->getNombre() == $digimon_original->getNombre()) {
						$encontrado = $digimon;
					}
				} else {
					if ($digimon->getNombre() == $nom_digimon) {
						$encontrado = $digimon;
					}
				}
			}
			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\na';
			}
			fclose($fichero);
		}
		return $encontrado;
	}


//Se guarda un digimon, se usa en la zona de administracion o en la cuenta del usuario,
//especificandolo por los paramentros
//$ruta_fichero para guardar en un fichero ej: usuario_digimones.txt
//$ruta_directorio para crear la carpeta del digimon en la cuenta del usuario
	public static function guardar($new_digimon, $ruta_fichero, $ruta_directorio)
	{
		$fichero = fopen($ruta_fichero, "a");

		$cadena_obj = Utilidades::obj_a_cadenaurl($new_digimon);
		if (fwrite($fichero, $cadena_obj . "\n")) {
			$escrito = true;
		} else {
			$escrito = false;
		}

		if ($escrito) @mkdir($ruta_directorio . $new_digimon->getNombre());

		fclose($fichero);
		return $escrito;
	}

//Controla errores que ha introducido el usuario o administrador
	public static function erroresDatos($new_digimon)
	{
		$errores = array();
		if (strlen($new_digimon->getNombre()) < 1) {
			$errorNombre = '<p class="  text-danger">El nombre del digimon tiene que tener mas de 1 caracteres.</p>';
			$errores['errorNombre'] = $errorNombre;
		} else if (trim($new_digimon->getNombre()) == "") {
			$errorNombre = '<p class="  text-danger">El nombre del digimon tiene que tener mas de 1 caracteres.</p>';
			$errores['errorNombre'] = $errorNombre;
		} else if (Utilidades_dig::buscar(DIGIMONES, $new_digimon)) {
			$errorNombre = '<p class="   text-danger">El digimon ' . $new_digimon->getNombre() . ' ya exite.</p>';
			$errores['errorNombre'] = $errorNombre;
		}
		if ($new_digimon->getAtaque() <= 0 || $new_digimon->getAtaque() > 100) {
			$errorAtaque = '<p class=" text-danger">El ataque del digimon tiene que ser un valor entre 0 y 100.</p>';
			$errores['errorAtaque'] = $errorAtaque;
		}
		if ($new_digimon->getDefensa() <= 0 || $new_digimon->getDefensa() > 100) {
			$errorDefensa = '<p class=" text-danger">La defensa del digimon tiene que ser un valor entre 0 y 100.</p>';
			$errores['errorDefensa'] = $errorDefensa;
		}
		return $errores;

	}


//Borra el fichero DIGIMONES(parte administracion) y escribe 
//el mismo fichero cambiando solo el digimon pasado por parametro.
//En la parte del usuario, recorre las carpetas del usuario buscando el fichero
//digimones-usuario.txt para sobreescribir tambien ese digimon en caso de obtenerlo.
//Tambien tiene la opcion de eliminar un digimon.
	public static function sobreescribir($digimon_original, $ruta, $eliminar = false)
	{
		$fichero = fopen($ruta, "r");
		$aux = array();
		if ($fichero) {
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->getNombre() == $digimon_original->getNombre()) {
					if (!$eliminar) {
						$aux[] = Utilidades::obj_a_cadenaurl($digimon_original) . "\n";
					}
				} else {
					$aux[] = Utilidades::obj_a_cadenaurl($digimon) . "\n";
				}
			}
			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\nd';
			}
			fclose($fichero);
		}
		unlink($ruta);
		$fichero = fopen($ruta, "a");
		if ($fichero) {
			for ($i = 0; $i < count($aux); $i++) {
				fwrite($fichero, $aux[$i]);
			}
			fclose($fichero);
		}

		//Sobreescribir lo mismo para los digimones de cada usuario
		if (is_dir(USERDIR)) {
			$directorio = opendir(USERDIR);

			while ($directorioUser = readdir($directorio)) {

				if ($directorioUser != "." && $directorioUser != "..") {

					$carpetaUser = opendir(USERDIR . $directorioUser . "/");

					while ($contenido = readdir($carpetaUser)) {

						if ($contenido == "digimones-usuario.txt" || $contenido == "equipo-usuario.txt") {
							$fichero = fopen(USERDIR . $directorioUser . "/" . $contenido, "r");
							$aux = array();
							if ($fichero) {
								while ($digimoninfo = fscanf($fichero, "%s\n")) {
									$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);

									if ($digimon->getNombre() == $digimon_original->getNombre()) {

										$aux[] = Utilidades::obj_a_cadenaurl($digimon_original) . "\n";

									} else {
										$aux[] = Utilidades::obj_a_cadenaurl($digimon) . "\n";
									}
								}

								if (!feof($fichero)) {
									echo 'Error: fallo inesperado de fscanf()\nd';
								}
								fclose($fichero);
							}
							unlink(USERDIR . $directorioUser . "/" . $contenido);
							$fichero = fopen(USERDIR . $directorioUser . "/" . $contenido, "a");
							if ($fichero) {
								for ($i = 0; $i < count($aux); $i++) {
									fwrite($fichero, $aux[$i]);
								}
								fclose($fichero);
							}
						}
					}
				}
			}
		} else {
			echo "no encuentra directorio";
		}

	}

//Digievolucionar reemplaza un digimon por otro en la cuenta del usuario en concreto.
//Reemplaza en digimones-usuario.txt, equipo-usuario.txt, borra la carpeta del 
//digimon antiguo para crear la carpeta del nuevo digimon(al que a evolucionado)
	public static function digievolucionar($digimon_original, $usuario)
	{
		//Se recorren todos los directorios, 
		$directorioUser = $usuario->getDirectorio_usuario();
		if ($carpetaUser = opendir($directorioUser)) {
			while ($contenido = readdir($carpetaUser)) {
				if ($contenido == "digimones-usuario.txt" || $contenido == "equipo-usuario.txt") {
					$fichero = fopen($directorioUser . $contenido, "r");
					$aux = array();
					while ($digimoninfo = fscanf($fichero, "%s\n")) {
						$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);
						if ($digimon->getNombre() == $digimon_original->getNombre()) {
							$reemplazar = Utilidades_dig::buscar(DIGIMONES, false, $digimon_original->getEvolucion());
							$aux[] = Utilidades::obj_a_cadenaurl($reemplazar) . "\n";
						} else {
							$aux[] = Utilidades::obj_a_cadenaurl($digimon) . "\n";
						}
					}
					if (!feof($fichero)) {
						echo 'Error: fallo inesperado de fscanf()\nd';
					}
					fclose($fichero);
					unlink($directorioUser . $contenido); //Borra el fichero digimones-usuario.txt
					$fichero = fopen($directorioUser . $contenido, "a");
					if ($fichero) {
						for ($i = 0; $i < count($aux); $i++) {
							fwrite($fichero, $aux[$i]);
						}
						fclose($fichero);
					}
				}


			}
			$directorioUser = $usuario->getDirectorio_usuario();
			if ($carpetaUser = opendir($directorioUser)) {
				while ($contenido = readdir($carpetaUser)) {
					if ($contenido == $digimon_original->getNombre()) {
				//Si encuentra la carpeta del digimon que tenemos que reemplazar la elimina y crea una nueva 
				//para el nuevo digimon
				//SE BORRA LA CARPETA DEL DIGIMON ANTIGUO Y SE CREA LA NUEVA
						if (is_dir($directorioUser . $contenido)) {
							$carpetaUser = opendir($directorioUser . $contenido);
							while ($handler = readdir($carpetaUser)) {
								if ($handler == "partidas-registradas.txt") {
									unlink($directorioUser . $contenido . "/" . $handler);
								}
							}
							rmdir($directorioUser . $contenido);
							mkdir($directorioUser . $digimon_original->getEvolucion());
						}
					}
				}
			}
		}

	}

//Funcion que devuelve un array de digimones segun el nivel pasado por parametro
//El parametro de el nivel es opcional, si no pasa un nivel devolvera todos los digimones
//Busca los digimones en el fichero pasado por parametro (Funciona para parte administrador y usuario)
	public static function listar($ruta_fichero, $nivel = null)
	{

		$fichero = fopen($ruta_fichero, "r");
		$digimones = array();
		$digimones_nivel = array();
		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->getNivel() == $nivel) {
					$digimones_nivel[] = $digimon;
				}
				$digimones[] = $digimon;
			}

			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\ng';
			}
			fclose($fichero);
		}
		if ($nivel == null) return $digimones;
		else return $digimones_nivel;

	}

//Cuenta los digimones de un fichero especificado por parámetro.
	public static function contar_dig($ruta_fichero)
	{

		@$fichero = fopen($ruta_fichero, "r");
		$contador = 0;
		if ($fichero) {

			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$contador++;
			}

			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\na';
			}
			fclose($fichero);
		}

		return $contador;
	}


	public static function leer_equipo($txt_equipo)
	{
		$array_equipo = array();
		$array_equipo[0] = null;
		$array_equipo[1] = null;
		$array_equipo[2] = null;

		$fichero = fopen($txt_equipo, "r");
		if ($fichero) {
			$i = 0;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);
				$array_equipo[$i] = $digimon;
				$i++;
			}

			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\na';
			}
			fclose($fichero);
		}

		return $array_equipo;
	}


}