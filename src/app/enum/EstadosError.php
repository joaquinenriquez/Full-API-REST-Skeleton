<?php
abstract class EstadosError
{
    public const OK = 200;
    public const RECURSO_CREADO = 201;
    public const SIN_RESULTADOS = 207;
    
    public const ERROR_DE_AUTORIZACION = 401;
    public const ERROR_SIN_PERMISOS = 403;
    public const ERROR_NO_SE_ENCONTRO_RECURSO = 404;
    public const ERROR_OPERACION_INVALIDA = 406;
    public const ERROR_RECURSO_REPETIDO = 409;
    public const ERROR_PARAMETROS_INVALIDOS = 422;

    public const ERROR_DB = 500;
    public const ERROR_BORRAR = 501;
    public const ERROR_GENERAL = 502;
    public const ERROR_GUARDAR = 503;
    public const ERROR_QUERY = 504;

}

?>