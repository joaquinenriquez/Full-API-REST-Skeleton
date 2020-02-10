<?php

require_once '../src/app/modelAPI/IApiControler.php';
require_once '../src/app/ModelDAO/ArticuloDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class ArticuloAPI
{
    public function TraerTodos(Request $request, Response $response, $args) 
    {
        $auxResponse = ArticuloDAO::TraerTodos();
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function TraerUno(Request $request, Response $response, $args) 
    {
        $idArticulo = $request->getAttribute('id');
        $auxResponse = ArticuloDAO::TraerUno($idArticulo);
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