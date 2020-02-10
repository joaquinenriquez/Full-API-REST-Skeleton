<?php

require_once '../src/app/model/sector.php';
require_once '../src/app/modelAPI/IApiControler.php';
require_once '../src/app/api/responseJSON.php';
require_once '../src/app/enum/responseJSONEstados.php';

use Slim\Http\Request;
use Slim\Http\Response;

class SectorApi extends Sector implements IApiController
{
    public function TraerTodos(Request $request, Response $response, $args) 
    {
        $auxResponse = SectorDAO::TraerTodos();
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function TraerUno(Request $request, Response $response, $args) 
    {
        $idSector = $request->getAttribute('id');
        $auxResponse = SectorDAO::TraerUno($idSector);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
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
        $parametros = $request->getParsedBody();
        $auxResponse = SectorDAO::ModificarUno($idSector, $parametros);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

}

?>