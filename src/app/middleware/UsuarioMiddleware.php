<?php

use Slim\Http\Request;
use Slim\Http\Response;

class UsuarioMiddleware
{
    public function VerificarParametrosAltaUsuario(Request $request, Response $response, $next)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $parametros = $request->getParsedBody();
        $auxReturn = self::VerificarParametrosAltaUsuarioEstanDefinidos($parametros);

        if ($auxReturn->getIsError() == false) {
            $auxReturn = self::ValidarTipoDatosParametros($parametros);
        }

        if ($auxReturn->getIsError() == true) {

            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());

        } else {
            $response = $next($request, $response);
        }

        return $response;

    }

    private function VerificarParametrosAltaUsuarioEstanDefinidos($parametros)
    {
        $parametrosSinDefinir = [];
        $auxReturn = true;

        if (empty($parametros["nombre_usuario"])) {
            array_push($parametrosSinDefinir, "nombre_usuario");
            $auxReturn = false;
        }

        if (empty($parametros["password"])) {
            array_push($parametrosSinDefinir, "password");
            $auxReturn = false;
        }

        if (empty($parametros["nombre"])) {
            array_push($parametrosSinDefinir, "nombre");
            $auxReturn = false;
        }

        if (empty($parametros["apellido"])) {
            array_push($parametrosSinDefinir, "apellido");
            $auxReturn = false;
        }

        if (empty($parametros["id_rol"])) {
            array_push($parametrosSinDefinir, "id_rol");
            $auxReturn = false;
        }

        if ($auxReturn == false) {
            $mensaje = "Existen parametros sin definir o vacios:";
            $strParametrosSinDefinir = "";
            foreach ($parametrosSinDefinir as $unParametro) {
                $strParametrosSinDefinir = $strParametrosSinDefinir . " " . $unParametro;
            }

            $mensaje = $mensaje . $strParametrosSinDefinir;
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else {
            $auxReturn = new Resultado(false, "Todos los parametros fueron definidos", EstadosError::OK);
        }

        return $auxReturn;

    }

    private function ValidarTipoDatosParametros($parametros)
    {

        $parametrosConErrores = [];
        $auxReturn = true;
        $mensaje = "";

        if (filter_var($parametros["nombre_usuario"], FILTER_SANITIZE_STRING) == false) {
            array_push($parametrosConErrores, "nombre_usuario (debe contener solo letras y numeros)");
            $auxReturn = false;
        } else {
            if (Validacion::SoloLetrasYNumeros($parametros["nombre_usuario"]) == false) {
                array_push($parametrosConErrores, "nombre_usuario (debe contener solo letras y numeros)");
                $auxReturn = false;
            }
        }

        if (filter_var($parametros["apellido"], FILTER_SANITIZE_STRING) == false) {
            array_push($parametrosConErrores, "apellido (debe contener solo letras)");
            $auxReturn = false;
        }

        if (filter_var($parametros["id_rol"], FILTER_SANITIZE_NUMBER_INT) == false) {
            array_push($parametrosConErrores, "id_rol (numerico)");
            $auxReturn = false;
        } else {
            if (Validacion::SoloNumeros($parametros["id_rol"]) == false) {
                array_push($parametrosConErrores, "id_rol (numerico)");
                $auxReturn = false;
            }
        }

        if ($auxReturn == false) {
            $mensaje = "Existen parametros invalidos:";
            $strParametrosConErrores = "";
            foreach ($parametrosConErrores as $unParametro) {
                $strParametrosConErrores = $strParametrosConErrores . " " . $unParametro;
            }

            $mensaje = $mensaje . $strParametrosConErrores;
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else {
            $auxReturn = new Resultado(false, "Todos los parametros fueron definidos", EstadosError::OK);
        }

        return $auxReturn;
    }

}
