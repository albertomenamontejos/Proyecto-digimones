<?php
require_once "clases/Digimon.class.php";
class Combate
{
	private $digimon_user;
	private $digimon_adversario;
	private $ronda;
	private $ganador;
	private $perdedor;
	private $usuario;
	private $adversario;
	//Se pasa el equipo completo al constructor, mediante el contador elegimos los digimones a enfrentarse
	function __construct($equipo_user, $equipo_adversario,$usuario,$adversario, $ronda)
	{
		$this->digimon_user = $equipo_user[$ronda];
		$this->digimon_adversario = $equipo_adversario[$ronda];
		$this->ronda = $ronda;
		$this->usuario=$usuario;
		$this->adversario=$adversario;
	}

	//Devuelve el usuario ganador.
	public function getGanador()
	{
		if ($this->digimon_user->getAtaque() > $this->digimon_adversario->getAtaque()) {
			$this->ganador = $this->usuario;
		} else if($this->digimon_user->getAtaque() < $this->digimon_adversario->getAtaque()){
			$this->ganador =$this->adversario;
		}
		//EMPATE: devuelve null
		return $this->ganador;
	}

	public function getPerdedor()
	{
		return $this->perdedor;
	}

	public function setPerdedor($perdedor)
	{
		$this->perdedor = $perdedor;

		return $this;
	}

	//Contador de rondas, permite saber que digimon esta luchando
	public function getRonda()
	{
		return $this->ronda;
	}


}

