<?php
abstract class ResponseJSONEstados
{
    public const OK = [200, "OK"];
    public const SIN_RESULTADOS = [204, "Sin resultados"];
    public const ERROR_DB = [500, "Error PDO"];
    public const ERROR_BORRAR = [500, "Error al intentar cambiar de estado a la entidad"];
    public const NO_SE_ENCONTRO_RECURSO = [404, "No se encontro el recurso solicitado"];
    public const ERROR_GENERAL = [500, "Ocurrio un error general al intentar ejecutar la query"];
}

?>