<?php

use Slim\Http\Request;
use Slim\Http\Response;

class SectorApi extends Sector implements IApiController
{
    public function TraerTodos(Request $request, Response $response, $args) 
    {
        $auxReturn = SectorDAO::TraerTodos();

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;   
    }

    public function TraerUno(Request $request, Response $response, $args) 
    {
        $idSector = $request->getAttribute('id');
        $auxReturn = SectorDAO::TraerUno($idSector);

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;   
    }

    public function CargarUno(Request $request, Response $response, $args) 
    {
        $parametrosPOST = $request->getParsedBody();
        $nuevoSector = new Sector();
        $nuevoSector->setDescripcionSector($parametrosPOST["descripcion"]);
        $auxResponse = SectorDAO::CargarUno($nuevoSector);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function BorrarUno(Request $request, Response $response, $args)
    {
        $idSector = $request->getAttribute('id');
        $auxResponse = SectorDAO::BorrarUno($idSector);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function ModificarUno(Request $request, Response $response, $args)
    {
        $idSector = $request->getAttribute('id');
        $parametrosPOST = $request->getParsedBody();

        $auxReturn = SectorDAO::VerificarSiExisteSectorPorDescripcion($parametrosPOST["descripcion"]);

            // No existe ninguno con esa descripcion o si el que existe es el mismo que estamos editando
            if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS ||
                ($auxReturn->getStatus() == EstadosError::OK && $auxReturn->getMensaje() == $idSector)) {

                $auxReturn = SectorDAO::ModificarUno($idSector, $parametrosPOST);

                // Si hay otro articulo con la misma descripcion (y que no sea el mismo que estamos editando)
            } else if ($auxReturn->getStatus() == EstadosError::OK && $auxReturn->getIsError() == false && $auxReturn->getMensaje() != $idSector) {
                $auxReturn->setIsError(true);
                $auxReturn->setStatus(EstadosError::ERROR_RECURSO_REPETIDO);
                $mensaje = "Existe un sector con esa descripcion con ID: " . $auxReturn->getMensaje();
                $auxReturn->setMensaje($mensaje);
            }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

}

?>