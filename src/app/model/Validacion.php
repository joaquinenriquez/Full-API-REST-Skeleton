<?php

class Validacion 
{
    public static function SoloLetras (string $texto): bool 
    {
        $auxReturn = false;
        
        if (!preg_match("/[^A-Za-z]/", $texto))
        {
            $auxReturn = true;
        }

        return $auxReturn;
    }

    public static function SoloNumeros (string $texto): bool 
    {
        $auxReturn = false;
        
        if (is_numeric($texto))
        {
            $auxReturn = true;
        }

        return $auxReturn;
    }

    public static function SoloLetrasYNumeros($texto): bool {

        $auxReturn = false;

        if (!preg_match('/[^A-Za-z0-9.#\\-$]/', $texto)) {
            $auxReturn = true;
        }

        return $auxReturn;
    }

    public static function Passwords(string $password) {

        $mensaje = false;

        if (strlen($password) <= 8) {
            $mensaje = "La password debe contener al menos 8 caracteres";

        } else if (!preg_match("#[0-9]+#",$password)) {
            $mensaje = "La password debe contener al menos un numero";

        } else if (!preg_match("#[A-Z]+#",$password)) {
            $mensaje = "La password debe contener al menos una mayuscula";
        } else if (!preg_match("#[a-z]+#",$password)) {
            $mensaje = "La password debe contener al menos una minuscula";
        }

        return $mensaje;
    }


}

?>