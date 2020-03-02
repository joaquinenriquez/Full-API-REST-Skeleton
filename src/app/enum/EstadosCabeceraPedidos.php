<?php

class EstadosCabeceraPedido
{
    public const DESHABILITADO = [0, "DESHABILITADA"];
    public const CERRADO = [1, "CERRADO"];
    public const ACTIVO = [2, "ACTIVO"];

    public static function TraerEstadoPorId(int $idEstado)
    {
        $auxReturn = false;
        $ARRAY_ESTADOS = [
                            EstadosCabeceraPedido::DESHABILITADO, 
                            EstadosCabeceraPedido::CERRADO, 
                            EstadosCabeceraPedido::ACTIVO
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