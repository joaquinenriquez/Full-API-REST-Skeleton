<?php

require_once '../src/app/model/Resultado.php';
require_once '../src/app/model/Validacion.php';
require_once '../src/app/ModelDAO/SectorDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class SectorMiddleware {

    public function VerificarSiExisteSector(Request $request, Response $response, $next) 
    {
        $parametros = $request->getParsedBody();
        $id_sector = $parametros["id_sector"];
        $auxReturn = SectorDAO::TraerUno($id_sector);
        
        if ($auxReturn->getIsError() == false) {
            if ($auxReturn->getStatus() == EstadosError::OK) {
                $auxReturn = new Resultado(false, "El sector existe", EstadosError::OK);
            } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                $auxReturn = new Resultado(true, "El sector no existe o se encuentra deshabilitado", EstadosError::SIN_RESULTADOS);
            }
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

    public function VerificarParametrosModificarSector(Request $request, Response $response, $next) 
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $idSector = $request->getAttribute('routeInfo')[2]['id'];
        $parametros = $request->getParsedBody();

        // Validamos que se encuentre definido el id
        if (isset($idSector) == false) {

            $mensaje = "Debe incluir el id del sector como atributo de la URI ($ubicacionParaMensaje)";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

            // Validamos que el id sea solo numerico
        } else if (Validacion::SoloNumeros($idSector) == false) {

            $mensaje = "El id del sector debe ser numerico";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else {
            $auxReturn = self::VerificarParametrosAltaSectorEstanDefinidos($parametros);
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

    private static function VerificarParametrosAltaSectorEstanDefinidos($parametros)
    {

        $parametrosSinDefinir = [];

        if (empty($parametros["descripcion"])) {
            array_push($parametrosSinDefinir, "descripcion");
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