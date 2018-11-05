<?php
include_once "Utilidades.class.php";

class Usuario{
	private $nick;
	private $password;

	function __construct($nick,$password){
		$this->nick=$nick;
		$this->password=$password;
	}

// PARA PAGINAS DE ADMIN 
	public function existe_user($new_user){
		
		$fichero= fopen(USUARIOS,"r");

		if($fichero){
			$encontrado = false;
			while($usuarioinfo=fscanf($fichero,"%s\n")){
				$usuario=Utilidades::cadenaurl_a_obj($usuarioinfo[0]);

				if ($usuario->nick==$new_user->nick) {
					$encontrado = true;
			
				}
			}			
			if(!feof($fichero)){
				echo 'Error: fallo inesperado de fscanf()\n';
			}
			fclose($fichero);
		}

		return $encontrado;
	}

	public function guardar_user($new_user){
		$fichero=fopen(USUARIOS,"a");
		$cadena_obj=Utilidades::obj_a_cadenaurl($new_user);
		if(is_writeable(USUARIOS)){
			fwrite($fichero,$cadena_obj."\n");
			$escrito=true;
		}else{
			$escrito = false;
		}
		if($escrito)mkdir(USERDIR.$new_user->nick);
		fclose($fichero);
		return $escrito;
	}



//PARA PAGINAS DE USUARIO

public function comprobar_pass($new_user){
		
	$fichero= fopen(USUARIOS,"r");

	if($fichero){
		$encontrado = false;
		while($usuarioinfo=fscanf($fichero,"%s\n")){
			$usuario=Utilidades::cadenaurl_a_obj($usuarioinfo[0]);

			if ($usuario->password==$new_user->password) {
				$encontrado = true;
			}
		}			
		if(!feof($fichero)){
			echo 'Error: fallo inesperado de fscanf()\n';
		}
		fclose($fichero);
	}

	return $encontrado;
}
}
?>