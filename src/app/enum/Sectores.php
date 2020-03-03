<?php

class Sectores
{

    // ID. DESCRIPCION. Rples
    public const COCINA = [1, [1, 5], "Cocina", ];
    public const BARRA_VINOS_TRAGOS = [2, [2,5], "Barra de tragos"];
    public const BARRA_CERVEZA = [3, [2, 3],  "Barra Cerveza"];
    public const CANDY_BAR = [4, [1,5], "Candy Bar"];

    public static function TraerRolPorId($idEstado)
    {
        $auxReturn = false;
        $ARRAY_SECTORES = [
            Sectores::COCINA,
            Sectores::BARRA_VINOS_TRAGOS,
            Sectores::BARRA_CERVEZA,
            Sectores::CANDY_BAR
        ];

        foreach ($ARRAY_SECTORES as $unSector) {
            if ($unSector[0] == $idEstado) {
                $auxReturn = $unSector[2];
                break;
            }
        }

        if ($auxReturn == false) {
            $auxReturn = "DESCONOCIDO";
        }

        return $auxReturn;
    }
}
