<?php

class EstadosMesas
{
    public const DESHABILITADA = [0, "DESHABILITADA"];
    public const CERRADA = [1, "CERRADA"];
    public const CON_CLIENTES_ELIGIENDO = [2, "CON CLIENTES ELIGIENDO"];
    public const CON_CLIENTES_ESPERANDO_PEDIDO = [3, "CON CLIENTES ESPERANDO PEDIDO"];
    public const CON_CLIENTES_COMIENDO = [4, "CON CLIENTES COMIENDO"];
    public const CON_CLIENTES_PAGANDO = [5, "CON CLIENTES PAGANDO"];

    public static function TraerEstadoPorId($idEstado)
    {
        $auxReturn = false;
        $ARRAY_ESTADOS = [EstadosMesas::DESHABILITADA, EstadosMesas::CERRADA, EstadosMesas::CON_CLIENTES_ELIGIENDO, EstadosMesas::CON_CLIENTES_COMIENDO, EstadosMesas::CON_CLIENTES_ESPERANDO_PEDIDO, EstadosMesas::CON_CLIENTES_PAGANDO];

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
