<?php

//VARIABLES
define('USUARIOS' , "usuarios.txt");
define('DIGIMONES' , "digimones.txt");
define('USERDIR' , "usuarios/");
define('DIGIMONESDIR' , "digimones/");


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

?>