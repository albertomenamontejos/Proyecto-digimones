<?php
require_once "clases/Utilidades.class.php";
require_once "clases/Digimon.class.php";

class Usuario
{
	private $nick;
	private $password;
	private $directorio_usuario;
	private $txt_dig_usuario;
	private $txt_equipo_usuario;
	private $txt_registro;
	
	function __construct($nick, $password)
	{
		$this->nick = $nick;
		$this->password = $password;
		$this->directorio_usuario="usuarios/$nick/";
		$this->txt_dig_usuario = "usuarios/$nick/digimones-usuario.txt";
		$this->txt_equipo_usuario="usuarios/$nick/equipo-usuario.txt";
		$this->txt_registro="usuarios/$nick/registro-partidas.txt";
	}

	public function getNick()
	{
		return $this->nick;
	}


	public function setNick($nick)
	{
		$this->nick = $nick;

		return $this;
	}


	public function getPassword()
	{
		return $this->password;
	}

	
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	public function getTxt_dig_usuario()
	{
		return $this->txt_dig_usuario;
	}

	public function getTxt_equipo_usuario()
	{
		return $this->txt_equipo_usuario;
	}

	public function getDirectorio_usuario()
	{
		return $this->directorio_usuario;
	}


	public function getTxt_registro()
	{
		return $this->txt_registro;
	}
}
?>