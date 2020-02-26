<?php

use Slim\Http\Request;
use Slim\Http\Response;

class LoginMiddleWare 
{
    public function VerificarParametrosLogin(Request $request, Response $response, $next)
    {

        $auxReturn = false;
        $parametros = $request->getParsedBody();
        $auxReturn = self::VerificarParametrosLoginEstenDefinidos($parametros);

        if ($auxReturn->getIsError() == true) {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        } else {
            $response = $next($request, $response);
        }

        return $response;
    }

    private function VerificarParametrosLoginEstenDefinidos($parametros)
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

        if ($auxReturn == false) {
            $mensaje = "Existen parametros sin definir o vacios: ";
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

}


?>