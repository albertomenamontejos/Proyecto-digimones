<?php
//VARIABLES
define('USUARIOS', "usuarios.txt");
define('DIGIMONES', "digimones.txt");
define('USERDIR', "usuarios/");
define('DIGIMONESDIR', "digimones/");

class Utilidades
{

//Funcion para transformar un array en cadena.
	function obj_a_cadenaurl($obj)
	{
        //Primero Transformamos el array en una cadena de texto
		$cadenatmp = serialize($obj);
        //Codificamos dicha cadena en formato URL para enviar correctamente
        // los caracteres especiales
		$cadena = urlencode($cadenatmp); 
        //devolvemos la cadena codificada
		return $cadena;
	} 

//Funcion para transformar una cadena en un array.
	function cadenaurl_a_obj($texto)
	{ 
        // Esto lo hacemos por si está vacía la cadena no me cree un array 
        // con una posición vacía
		$obj = new stdClass();
		if ($texto != "") { 
        // Antes de descodificar hay que quitar cualquier \ contrabarra     
			$texto = stripslashes($texto); 
        // Decodifico de formato URL a texto plano
			$texto = urldecode($texto); 
        // Ahora a partir de la cadena genero un array
			$obj = unserialize($texto);
		}
		return $obj;
	}

	public  function buscar_digimon($nom_digimon)
	{

		$fichero = fopen(DIGIMONES, "r");

		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->getNombre() == $nom_digimon) {
					$encontrado = $digimon;
				}
			}

			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\n';
			}
			fclose($fichero);
		}

		return $encontrado;
	}

	public function guardar_digimon($new_digimon)
	{
		$fichero = fopen(DIGIMONES, "a");

		$cadena_obj = Utilidades::obj_a_cadenaurl($new_digimon);
		if (is_writeable(DIGIMONES)) {
			fwrite($fichero, $cadena_obj . "\n");
			$escrito = true;
		} else {
			$escrito = false;
		}

		if ($escrito) mkdir(DIGIMONESDIR . $new_digimon->getNombre());

		fclose($fichero);
		return $escrito;
	}

	public  function erroresDatos($nom_digimon, $ataque, $defensa)
	{
		$errores = array();
		if (strlen($nom_digimon) <= 1) {
			$errorNombre = '<span style="color:red;">El nombre del digimon tiene que tener mas de 1 caracteres.</span>';
			$errores['errorNombre'] = $errorNombre;
		} else if (Utilidades::buscar_digimon($nom_digimon)) {
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

	public  function digimones_evolucionables($nivel)
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
				echo 'Error: fallo inesperado de fscanf()\n';
			}
			fclose($fichero);
		}
		return $digimones;
	}

	public function sobreescribir_digimon($digimon_original)
	{

		$fichero = fopen(DIGIMONES, "r");
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
				echo 'Error: fallo inesperado de fscanf()\n';
			}
			fclose($fichero);
		}

		unlink(DIGIMONES);

		$fichero = fopen(DIGIMONES, "a");
		if ($fichero) {

			for ($i = 0; $i < count($aux); $i++) {
				fwrite($fichero, $aux[$i]);
			}

			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\n';
			}
			fclose($fichero);
		}

	}

	public function listar_digimones($nivel)
	{

		$fichero = fopen(DIGIMONES, "r");
		$digimones = array();
		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = Utilidades::cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->getNivel() == $nivel) {
					$digimones[] = $digimon;
				}
			}

			if (!feof($fichero)) {
				echo 'Error: fallo inesperado de fscanf()\n';
			}
			fclose($fichero);
		}
		return $digimones;

	}
}