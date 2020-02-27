<?php

class EstadosItemPedido 
{
    public const CERRADO = [0, "CERRADO"];
    public const PENDIENTE = [1, "PENDIENTE"];
    public const EN_PREPARACION = [2, "EN PREPARACION"];
    public const LISTO_PARA_SERVIR = [3, "LISTO PARA SERVIR"];
    public const CANCELADO = [4, "CANCELADO"];

    public static function TraerEstadoPorId($idEstado)
    {
        $auxReturn = false;
        $ARRAY_ESTADOS = [
                            EstadosItemPedido::CERRADO,
                            EstadosItemPedido::PENDIENTE, 
                            EstadosItemPedido::EN_PREPARACION, 
                            EstadosItemPedido::LISTO_PARA_SERVIR,
                            EstadosItemPedido::CANCELADO
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