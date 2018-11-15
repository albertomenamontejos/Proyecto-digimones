<?php
require_once "clases/Digimon.class.php";
class Combate
{
	private $equipo_user;
	private $equipo_adversario;
	private static $ronda = 0;
	private static $ganador;
	private $usuario;
	private $adversario;
	private static $us_info = array();
	private static $adv_info = array();

	function __construct($equipo_user, $equipo_adversario, $usuario, $adversario)
	{
		$this->equipo_user = $equipo_user;
		$this->equipo_adversario = $equipo_adversario;
		$this->usuario = $usuario;
		$this->adversario = $adversario;
		$this->digimon_user = $equipo_user[self::$ronda];
		$this->digimon_adversario = $equipo_adversario[self::$ronda];
		self::$ronda++;
	}


	//Devuelve el usuario ganador.
	public function getGanador()
	{
		$us_tipo = $this->digimon_user->getTipo();
		$us_ataque = $this->digimon_user->getAtaque();
		$us_defensa = $this->digimon_user->getDefensa();
		$adv_tipo = $this->digimon_adversario->getTipo();
		$adv_ataque = $this->digimon_adversario->getAtaque();
		$adv_defensa = $this->digimon_adversario->getDefensa();

		$us_puntos_tipo = $this->puntos_tipo($us_tipo, $adv_tipo);
		$adv_puntos_tipo = -($this->puntos_tipo($us_tipo, $adv_tipo));
		$us_rand = rand(1, 20);
		$adv_rand = rand(1, 20);
		$us_resultado = $us_ataque + $us_defensa + $us_puntos_tipo + $us_rand;
		$adv_resultado = $adv_ataque + $adv_defensa + $adv_puntos_tipo + $adv_rand;
		self::$us_info[self::$ronda] = [$us_ataque, $us_defensa, $us_puntos_tipo, $us_rand, $us_resultado];
		self::$adv_info[self::$ronda] = [$adv_ataque, $adv_defensa, $adv_puntos_tipo, $adv_rand, $adv_resultado];

		if ($us_resultado > $adv_resultado) {
			self::$ganador[self::$ronda][] = $this->usuario;
			self::$ganador[self::$ronda][] = $this->equipo_user[self::$ronda - 1];
		} else if ($us_resultado < $adv_resultado) {
			self::$ganador[self::$ronda][] = $this->adversario;
			self::$ganador[self::$ronda][] = $this->equipo_adversario[self::$ronda - 1];
		} else {
			self::$ganador[self::$ronda] = null;
		}
		//EMPATE: devuelve null
		return self::$ganador;
	}

	public function getRonda()
	{
		return self::$ronda;
	}

	public function getUs_info()
	{
		return self::$us_info;
	}

	public function getAdv_info()
	{
		return self::$adv_info;
	}

	function puntos_tipo($us_tipo, $adv_tipo)
	{
		$puntos = array(0, 10, 5, -5, -10);
		$tipos = array(
			"vacuna" => 0,
			"virus" => 1,
			"animal" => 2,
			"planta" => 3,
			"elemental" => 4
		);
		$us_valor = 0;
		$adv_valor = 0;
		foreach ($tipos as $tipo => $valor) {
			if ($tipo == $us_tipo) {
				$us_valor = $valor;
			} else if ($tipo == $adv_tipo) {
				$adv_valor = $valor;
			}
		}
		if ($us_valor == $adv_valor) {
			return 0;
		} else if ($us_valor < $adv_valor) {
			$posicion = $adv_valor - $us_valor;
			$result = $puntos[$posicion];
			return $result;
		} else if ($us_valor > $adv_valor) {
			$posicion = $us_valor - $adv_valor;
			$result = -($puntos[$posicion]);
			return $result;
		}
	}
	public static function registrarPartida($ganador_partida, $usuario, $adversario)
	{

		$fichero = fopen($usuario->getTxt_registro(), "c+");
		$tamaño = filesize($usuario->getTxt_registro());
		$ganadas = 0;
		$perdidas = 0;
		$empatadas = 0;
		$digievoluciones = 0;
		$totalPartidas = 0;
		if ($tamaño != 0) {
			$registro = explode("\n", fread($fichero, $tamaño));
			$ganadas = $registro[0];
			$perdidas = $registro[1];
			$empatadas = $registro[2];
			$digievoluciones = $registro[3];
		}
		fclose($fichero);
		$fichero = fopen($usuario->getTxt_registro(), "c+");
		ftruncate($fichero, $tamaño);
		if ($ganador_partida == $usuario->getNick()) {
			$ganadas += 1;
			if ($ganadas % 10 == 0) $digievoluciones += 1;
			if ($fichero) {
				fwrite($fichero, "$ganadas\n$perdidas\n$empatadas\n$digievoluciones");
			}
		} else if ($ganador_partida == $adversario->getNick()) {
			$perdidas += 1;
			if ($fichero) {
				fwrite($fichero, "$ganadas\n$perdidas\n$empatadas\n$digievoluciones");
			}
		} else {
			$empatadas += 1;

			if ($fichero) {
				fwrite($fichero, "$ganadas\n$perdidas\n$empatadas\n$digievoluciones");
			}
		}
		fclose($fichero);
		$totalPartidas = $ganadas + $perdidas + $empatadas;
		if ($totalPartidas % 10 == 0 && $totalPartidas != 0) {
			Utilidades_user::asignarDigimones($usuario, 1);
		}
	}

	public static function registrarPartidaDigimon($usuario, $adversario, $digimon, $ganador)
	{
		$file_partidas = "partidas-registradas.txt";
		$directorio = $usuario->getDirectorio_usuario();
		if ($carpetaUsuario = opendir($directorio)) {
			while ($carpetaDigimon = readdir($carpetaUsuario)) {
				if ($carpetaDigimon == $digimon->getNombre()) {
					if ($contenido = opendir($directorio . $carpetaDigimon)) {
						while ($fichero = readdir($contenido)) {
							if (!file_exists($directorio . "/" . $carpetaDigimon . $file_partidas) || $fichero == $file_partidas) {
								if ($contenido_fichero = fopen($directorio . "/" . $carpetaDigimon . "/" . $file_partidas, "c+")) {
									$tamaño = filesize($directorio . "/" . $carpetaDigimon . "/" . $file_partidas);
									$ganadas = 0;
									$perdidas = 0;
									$empatadas = 0;
									if ($tamaño != 0) {
										$registro = explode("\n", fread($contenido_fichero, $tamaño));
										$ganadas = $registro[0];
										$perdidas = $registro[1];
										$empatadas = $registro[2];
									}
									fclose($contenido_fichero);
									$contenido_fichero = fopen($directorio . "/" . $carpetaDigimon . "/" . $file_partidas, "c+");
									ftruncate($contenido_fichero, $tamaño);
									if ($ganador == $usuario->getNick()) {
										$ganadas += 1;
										if ($contenido) {
											fwrite($contenido_fichero, "$ganadas\n$perdidas\n$empatadas\n");
										}
									} else if ($ganador == $adversario->getNick()) {
										$perdidas += 1;
										if ($contenido) {
											fwrite($contenido_fichero, "$ganadas\n$perdidas\n$empatadas\n");
										}
									} else {
										$empatadas += 1;
										if ($contenido) {
											fwrite($contenido_fichero, "$ganadas\n$perdidas\n$empatadas\n");
										}
									}
								}
							}
						}
					}
				}
			}
		}


	}
}

