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

    public static function FechaHora ($fecha)
    {
        //$matches = array();
        // //$pattern = '/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$/';
        // $pattern = new RegExp("^(3[01]|[12][0-9]|0[1-9])/(1[0-2]|0[1-9])/[0-9]{4} (2[0-3]|[01]?[0-9]):([0-5]?[0-9]):([0-5]?[0-9])$");

        // if (!preg_match($pattern, $fecha, $matches)) return false;
        // if (!checkdate($matches[2], $matches[1], $matches[3])) return false;
        //return true;

        $auxFecha = strtotime($fecha);
        return ($auxFecha);

    }


}

?>