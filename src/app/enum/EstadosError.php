<?php
abstract class EstadosError
{
    public const OK = 200;
    public const SIN_RESULTADOS = 204;
    
    public const NO_SE_ENCONTRO_RECURSO = 404;
    public const ERROR_PARAMETROS_SIN_DEFINIR = 401;
    public const ERROR_PARAMETROS_INVALIDO = 402;
    public const ERROR_VALOR_REPETIDO = 403;
    
    public const ERROR_DB = 500;
    public const ERROR_BORRAR = 501;
    public const ERROR_GENERAL = 502;
    public const ERROR_GUARDAR = 503;
    public const ERROR_QUERY = 504;

}

?>