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
	private $imagenVictoria="img/porDefectoV.png";
	private $imagenDerrota="img/porDefectoD.png";
	private $txt_partidas;

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

}
?> 