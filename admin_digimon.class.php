<?php
class Digimon
{

	private $nombre;
	private $ataque;
	private $defensa;
	private $tipo;
	private $nivel;
	private $evolucion = null;
	private $imagen="img/porDefecto.png";
	private $imagenVictoria;
	private $imagenDerrota;

	public function __construct($nombre, $ataque, $defensa, $tipo, $nivel)
	{
		$this->nombre = $nombre;
		$this->ataque = $ataque;
		$this->defensa = $defensa;
		$this->tipo = $tipo;
		$this->nivel = $nivel;
	}


	public function setEvolucion($evolucion)
	{
		$this->evolucion = $evolucion;

		return $this;
	}

	public function getNombre()
	{
		return $this->nombre;
	}


	public function getAtaque()
	{
		return $this->ataque;
	}

	public function getDefensa()
	{
		return $this->defensa;
	}

	public function getTipo()
	{
		return $this->tipo;
	}


	public function getNivel()
	{
		return $this->nivel;
	}


	public function getImagen()
	{
		return $this->imagen;
	}

	public function getEvolucion()
	{
		return $this->evolucion;
	}

	public function setImagen($imagen)
	{
		$this->imagen = $imagen;

		return $this;
	}


	public function getImagenVictoria()
	{
		return $this->imagenVictoria;
	}


	public function setImagenVictoria($imagenVictoria)
	{
		$this->imagenVictoria = $imagenVictoria;

		return $this;
	}


	public function getImagenDerrota()
	{
		return $this->imagenDerrota;
	}


	public function setImagenDerrota($imagenDerrota)
	{
		$this->imagenDerrota = $imagenDerrota;

		return $this;
	}
	
	public function buscar_digimon($nom_digimon)
	{

		$fichero = fopen(DIGIMONES, "r");

		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->nombre == $nom_digimon) {
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

		$cadena_obj = obj_a_cadenaurl($new_digimon);
		if (is_writeable(DIGIMONES)) {
			fwrite($fichero, $cadena_obj . "\n");
			$escrito = true;
		} else {
			$escrito = false;
		}

		if ($escrito) mkdir(DIGIMONESDIR . $new_digimon->nombre);

		fclose($fichero);
		return $escrito;
	}


	public function erroresDatos($nom_digimon, $ataque, $defensa)
	{
		$errores = array();
		if (strlen($nom_digimon) <= 1) {
			$errorNombre = '<span style="color:red;">El nombre del digimon tiene que tener mas de 1 caracteres.</span>';
			$errores['errorNombre'] = $errorNombre;
		} else if (Digimon::buscar_digimon($nom_digimon)) {
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


	public static function digimones_evolucionables($nivel)
	{

		$fichero = fopen(DIGIMONES, "r");
		$digimones = array();
		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->nivel == $nivel && $digimon->evolucion == null) {
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
				$digimon = cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->nombre == $digimon_original->nombre) {
					$aux[] = obj_a_cadenaurl($digimon_original) . "\n";
				} else {
					$aux[] = obj_a_cadenaurl($digimon) . "\n";
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

	public function listar_digimones($nivel){
		
		$fichero = fopen(DIGIMONES, "r");
		$digimones = array();
		if ($fichero) {
			$encontrado = false;
			while ($digimoninfo = fscanf($fichero, "%s\n")) {
				$digimon = cadenaurl_a_obj($digimoninfo[0]);

				if ($digimon->nivel == $nivel) {
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
?>