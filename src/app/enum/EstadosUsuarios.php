<?php

class EstadosUsuarios 
{
    public const DESHABILITADO = [0, "DESHABILITADO"];
    public const ACTIVO = [1, "ACTIVO"];
    public const SUSPENDIDO = [2, "SUSPENDIDO"];

    public static function TraerEstadoPorId($idEstado)
    {
        $auxReturn = false;
        $ARRAY_ESTADOS = [
                            EstadosUsuarios::DESHABILITADO,
                            EstadosUsuarios::ACTIVO, 
                            EstadosUsuarios::SUSPENDIDO
                        ];
                        
        foreach ($ARRAY_ESTADOS as $unEstado) {
            if ($unEstado[0] == $idEstado) {
                $auxReturn = $unEstado[1];
            }
        }

        if ($auxReturn == false) {
            $auxReturn = "DESCONOCIDO";
        }

        return $auxReturn;

    }

}

?>