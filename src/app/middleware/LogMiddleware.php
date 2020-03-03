<?php

require_once '../src/app/middleware/LogMiddleware.php';

use Slim\Http\Request;
use Slim\Http\Response;

class LogMiddleware
{
    public function VerificarParametrosFiltroFechas(Request $request, Response $response, $next)
    {
        $ubicacionParaMensaje = "LogMiddleware->VerificarParametrosFiltroFechas";
        $parametros = $request->getParsedBody();

        $auxReturn = self::VerificarParametrosFiltroFechasEstanDefinidos($parametros);

        if ($auxReturn->getIsError() == false) {
            $auxReturn = self::ValidarTipoDatosParametrosFiltroFechas($parametros);
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

    public function VerificarParametrosFiltroFechasEstanDefinidos($parametros)
    {
        $parametrosSinDefinir = [];
        $auxReturn = true;

        if (empty($parametros["desde"])) {
            array_push($parametrosSinDefinir, "desde");
            $auxReturn = false;
        }

        if (empty($parametros["hasta"])) {
            array_push($parametrosSinDefinir, "hasta");
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

    private function ValidarTipoDatosParametrosFiltroFechas($parametros)
    {

        $parametrosConErrores = [];
        $auxReturn = true;
        $mensaje = "";

        if (Validacion::FechaHora($parametros["desde"]) == false) {
            array_push($parametrosConErrores, "desde (fecha)");
            $auxReturn = false;
        }

        if (Validacion::FechaHora($parametros["hasta"]) == false) {
            array_push($parametrosConErrores, "hasta (fecha)");
            $auxReturn = false;
        }

        if ($auxReturn == true)
        {
            if ($parametros["hasta"] < $parametros["desde"])
            {
                array_push($parametrosConErrores, "La fecha Hasta debe ser mayor que Desde");
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
