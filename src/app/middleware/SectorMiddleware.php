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
        } else {
            $response = $next($request, $response);
        }

        return $response;
    }
}


?>