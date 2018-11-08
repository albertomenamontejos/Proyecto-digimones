<?php
require_once "clases/Utilidades.class.php";

class Utilidades_dig
{

//Buscar por nombre un digimon en un fichero especifico 
//Se usa en la parte de administrador como en la parte del usuario
	public static function buscar($nom_digimon, $fichero)
	{

		$fichero = fopen($fichero, "r");

		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->getNombre() == $nom_digimon) {
					$encontrado = $digimon;
				}
			}

			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\na';
			}
			fclose($fichero);
		}

		return $encontrado;
	}


//Se guarda un digimon ya sea en la zona de administracion o en la cuenta del usuario,
//especificandolo por los paramentros
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
	public static function erroresDatos($nom_digimon, $ataque, $defensa)
	{
		$errores = array();
		if (strlen($nom_digimon) <= 1) {
			$errorNombre = '<span style="color:red;">El nombre del digimon tiene que tener mas de 1 caracteres.</span>';
			$errores['errorNombre'] = $errorNombre;
		} else if (Utilidades_dig::buscar($nom_digimon, DIGIMONES)) {
			$errorNombre = '<span style="color:red;">El digimon ya exite.</span>';
			$errores['errorNombre'] = $errorNombre;
		}
		if ($ataque <= 0 || $ataque > 100) {
			$errorAtaque = '<span style="color:red;">El ataque del digimon tiene que ser un valor entre 0 y 100.</span>';
			$errores['errorAtaque'] = $errorAtaque;
		}
		if ($defensa <= 0 || $defensa > 100) {
			$errorDefensa = '<span style="color:red;">La defensa del digimon tiene que ser un valor entre 0 y 100.</span>';
			$errores['errorDefensa'] = $errorDefensa;
		}
		return $errores;

	}

//Determinar que digimon puede ser evolucionado segun el nivel pasado por parametro
	public static function digimones_evolucionables($nivel)
	{

		$fichero = fopen(DIGIMONES, "r");
		$digimones = array();
		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->getNivel() == $nivel && $digimon->getEvolucion() == null) {
					$digimones[] = $digimon;
				}
			}

			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\ns';
			}
			fclose($fichero);
		}
		return $digimones;
	}
//Borra el fichero DIGIMONES(parte administracion) y escribe 
//el mismo fichero cambiando solo el digimon pasado por parametro.
	public static function sobreescribir($digimon_original,$ruta,$eliminar=false)
	{

		$fichero = fopen($ruta, "r");
		$aux = array();
		if ($fichero) {

			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->getNombre() == $digimon_original->getNombre()) {
					if(!$eliminar)
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

		unlink($ruta);

		$fichero = fopen($ruta, "a");
		if ($fichero) {

			for ($i = 0; $i < count($aux); $i++) {
				fwrite($fichero, $aux[$i]);
			}


			fclose($fichero);
		}

	}
//Funcion que devuelve un array de digimones segun el nivel pasado por parametro
//El parametro de el nivel es opcional, si no pasa un nivel devolvera todos los digimones
//Busca los digimones en el fichero pasado por parametro (Funciona para parte administrador y usuario)
	public static function listar($ruta_fichero, $nivel = null)
	{

		$fichero = fopen($ruta_fichero, "r");
		$digimones = array();
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
	public static function contar_dig($ruta_fichero){

		@$fichero = fopen($ruta_fichero, "r");
		$contador=0;
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