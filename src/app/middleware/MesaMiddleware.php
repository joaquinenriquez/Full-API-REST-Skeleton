<?php

use Slim\Http\Request;
use Slim\Http\Response;

class MesaMiddleware
{

    public static function VerificarParametrosAbrirMesa(Request $request, Response $response, $next)
    {

        $auxReturn = new Resultado(false, null, EstadosError::ERROR_GENERAL);
        $parametros = $request->getParsedBody();
        $idUsuario = false;
        $archivos = $request->getUploadedFiles(); // Nos traemos las fotos

        // Verificamos si tenemos el id del usuario
        if (!isset($request->getHeader("datosUsuario")[0]->id_usuario)) {
            $mensaje = "No se pudo validar el usuario actual";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DE_AUTORIZACION);

        } else if (empty($parametros["nombre_cliente"])) {
            $mensaje = "Para abrir la mesa debe especificar el parametro 'nombre_cliente'";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else if (filter_var($parametros["nombre_cliente"], FILTER_SANITIZE_STRING) == false) {

            $mensaje = "nombre_cliente debe contener solo letras y numeros";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else if (isset($archivos["foto"])) {
            $auxReturn = self::VerificarFoto($archivos);
        }

        if ($auxReturn->getIsError() == false) {
            $response = $next($request, $response);
        } else {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        }

        return $response;

    }

    public static function VerificarFoto($archivos)
    {
        $auxReturn = new Resultado(true, null, null);

        // Verificamos si subio correctamente
        $estadoErrorFoto = $archivos["foto"]->getError();
        switch ($estadoErrorFoto) {

            case UPLOAD_ERR_OK:

                $foto = $archivos["foto"];
                $tipo = $foto->getClientMediaType(); // Verificamos el tipo

                if (strpos($tipo, "image") !== false) {
                    $auxReturn = new Resultado(false, "La foto es valida", EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(true, "El formato de la imagen no es valido", EstadosError::ERROR_PARAMETROS_INVALIDOS);
                }

                break;

            case UPLOAD_ERR_NO_FILE:
                $auxReturn = new Resultado(false, "No se subio ningun archivo", EstadosError::SIN_RESULTADOS);
                break;

            default:
                $auxReturn = new Resultado(true, "Ocurrio un error al subir la foto. Nro de Error: " . $archivos["foto"]->getError(), EstadosError::ERROR_GUARDAR);
                break;

        }

        return $auxReturn;
    }

    public function VerificarParametrosAltaMesa(Request $request, Response $response, $next)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $parametros = $request->getParsedBody();

        $auxReturn = self::VerificarParametrosAltaMesaEstanDefinidos($parametros);
        if ($auxReturn->getIsError() == false) {
            $auxReturn = self::ValidarTipoDatosParametros($parametros);
            if ($auxReturn->getIsError() == false) {
                $response = $next($request, $response);
            }
        }

        if ($auxReturn->getIsError() == true) {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        }

        return $response;
    }

    private static function VerificarParametrosAltaMesaEstanDefinidos($parametros)
    {

        $parametrosSinDefinir = [];

        if (empty($parametros["nro_mesa"])) {
            array_push($parametrosSinDefinir, "nro_mesa");
        }

        if (count($parametrosSinDefinir) > 0) {
            $mensaje = "Los siguientes parametros no fueron definidos o estan vacios: " . implode(', ', $parametrosSinDefinir);
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
        } else {
            $auxReturn = new Resultado(false, "Todos los parametros fueron definidos", EstadosError::OK);
        }

        return $auxReturn;
    }

    private static function ValidarTipoDatosParametros($parametros)
    {

        $parametrosConErrores = [];
        $auxReturn = new Resultado(true, null, EstadosError::OK);
        $mensaje = "";

        if (filter_var($parametros["nro_mesa"], FILTER_SANITIZE_NUMBER_INT) == false) {
            array_push($parametrosConErrores, "nro_mesa (debe ser numerico)");
        } else if (Validacion::SoloNumeros($parametros["nro_mesa"]) == false) {
            array_push($parametrosConErrores, "nro_mesa (debe ser numerico)");
        }

        if (count($parametrosConErrores) > 0) {
            $mensaje = "Existen parametros invalidos:" . implode(', ', $parametrosConErrores);
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
        } else {
            $auxReturn = new Resultado(false, "Todos los parametros fueron definidos", EstadosError::OK);
        }

        return $auxReturn;
    }

}
