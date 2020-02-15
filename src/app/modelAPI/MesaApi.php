<?php

require_once '../src/app/modelAPI/IApiControler.php';
require_once '../src/app/ModelDAO/MesaDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class MesaAPI
{
    public function TraerTodos(Request $request, Response $response, $args) 
    {
        $auxResponse = MesaDAO::TraerTodos();
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function TraerUno(Request $request, Response $response, $args) 
    {
        $idMesa = $request->getAttribute('id');
        $auxResponse = MesaDAO::TraerUno($idMesa);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function CargarUno(Request $request, Response $response, $args) 
    {
        $parametrosPOST = $request->getParsedBody();
        $auxResponse = MesaDAO::CargarUno($parametrosPOST);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function BorrarUno(Request $request, Response $response, $args)
    {
        $idMesa = $request->getAttribute('id');
        $auxResponse = MesaDAO::BorrarUno($idMesa);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function ModificarUno(Request $request, Response $response, $args)
    {
        $idMesa = $request->getAttribute('id');
        $parametros = $request->getParsedBody();
        $auxResponse = MesaDAO::ModificarUno($idMesa, $parametros);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

    public function AbrirMesa(Request $request, Response $response, $args) 
    {
        $idMesa = $request->getAttribute('id');
        $parametros = $request->getParsedBody();
        $auxResponse = MesaDAO::AbrirMesa($idMesa, $parametros);
        $nuevaResponse = $response->withJson($auxResponse);
        return $nuevaResponse;
    }

}