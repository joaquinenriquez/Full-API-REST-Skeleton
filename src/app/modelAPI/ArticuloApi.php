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
        $auxResponse = ArticuloDAO::CargarUno($parametrosPOST);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function BorrarUno(Request $request, Response $response, $args)
    {
        $idArticulo = $request->getAttribute('id');
        $auxResponse = ArticuloDAO::BorrarUno($idArticulo);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function ModificarUno(Request $request, Response $response, $args)
    {
        $idArticulo = $request->getAttribute('id');
        $parametros = $request->getParsedBody();
        $auxResponse = ArticuloDAO::ModificarUno($idArticulo, $parametros);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

}


?>