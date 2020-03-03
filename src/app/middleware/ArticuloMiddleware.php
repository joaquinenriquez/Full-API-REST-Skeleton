<?php

use Slim\Http\Request;
use Slim\Http\Response;

class ArticuloMiddleware 
{
    public function VerificarParametrosAltaArticulo(Request $request, Response $response, $next) 
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $parametros = $request->getParsedBody();

        $auxReturn = self::VerificarParametrosAltaArticuloEstanDefinidos($parametros);
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

    public function VerificarParametrosBorrarArticulo(Request $request, Response $response, $next)
    {
    
        $auxReturn = new Resultado(false, null, EstadosError::OK);

        $ubicacionParaMensaje = "ArticuloMiddleware->VerificarParametrosBorrarArticulo";
        $idArticulo = $request->getAttribute('routeInfo')[2]['id'];

        // Validamos que se encuentre definido el id
        if (isset($idArticulo) == false) {

            $mensaje = "Debe incluir el id del articulo como atributo de la URI ($ubicacionParaMensaje)";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

            // Validamos que el id sea solo numerico
        } else if (Validacion::SoloNumeros($idArticulo) == false) {

            $mensaje = "El id de la mesa debe ser numerico";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
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

    public function VerificarParametrosModificarArticulo(Request $request, Response $response, $next) 
    {
        $ubicacionParaMensaje = "ArticuloMiddleware->VerificarParametrosModificarArticulo";
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $idArticulo = $request->getAttribute('routeInfo')[2]['id'];
        $parametros = $request->getParsedBody();

        // Validamos que se encuentre definido el id
        if (isset($idArticulo) == false) {

            $mensaje = "Debe incluir el id del articulo como atributo de la URI ($ubicacionParaMensaje)";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

            // Validamos que el id sea solo numerico
        } else if (Validacion::SoloNumeros($idArticulo) == false) {

            $mensaje = "El id del articulo debe ser numerico";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else {
            $auxReturn = self::VerificarParametrosAltaArticuloEstanDefinidos($parametros);
            if ($auxReturn->getIsError() == false) {
                $auxReturn = self::ValidarTipoDatosParametros($parametros);
                if ($auxReturn->getIsError() == false) {
                    $response = $next($request, $response);
                }
            }
        }

        if ($auxReturn->getIsError() == true) {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        }

        return $response;
    }
   
    private static function VerificarParametrosAltaArticuloEstanDefinidos($parametros)
    {

        $parametrosSinDefinir = [];

        if (empty($parametros["descripcion"])) {
            array_push($parametrosSinDefinir, "descripcion");
        }

        if (empty($parametros["id_sector"])) {
            array_push($parametrosSinDefinir, "id_sector");
        }

        if (empty($parametros["importe"])) {
            array_push($parametrosSinDefinir, "importe");
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

        if (filter_var($parametros["descripcion"], FILTER_SANITIZE_STRING) == false) {
            array_push($parametrosConErrores, "descripcion (debe contener solo letras y numeros)");
        } 

        if (filter_var($parametros["id_sector"], FILTER_SANITIZE_NUMBER_INT) == false) {
            array_push($parametrosConErrores, "id_sector(numerico)");
        } else {
            if (Validacion::SoloNumeros($parametros["id_sector"]) == false) {
                array_push($parametrosConErrores, "id_sector(numerico)");
            }
        }

        if (filter_var($parametros["importe"], FILTER_SANITIZE_NUMBER_INT) == false) {
            array_push($parametrosConErrores, "importe");
        } else {
            if (Validacion::SoloNumeros($parametros["importe"]) == false) {
                array_push($parametrosConErrores, "importe");
            }
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



?>