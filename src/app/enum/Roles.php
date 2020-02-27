<?php

class Roles
{

    public const COCINERO = [1, "Cocinero"];
    public const BARTENDER = [2, "Bartender"];
    public const CERVECERO = [3, "Cervecero"];
    public const MOZO = [4, "Mozo"];
    public const SOCIO = [5, "Socio"];

    public static function TraerRolPorId($idEstado)
    {
        $auxReturn = false;
        $ARRAY_ROLES = [
            Roles::MOZO,
            Roles::BARTENDER,
            Roles::CERVECERO,
            Roles::COCINERO,
            Roles::SOCIO,
        ];

        foreach ($ARRAY_ROLES as $unRol) {
            if ($unRol[0] == $idEstado) {
                $auxReturn = $unRol[1];
                break;
            }
        }

        if ($auxReturn == false) {
            $auxReturn = "DESCONOCIDO";
        }

        return $auxReturn;
    }
}
