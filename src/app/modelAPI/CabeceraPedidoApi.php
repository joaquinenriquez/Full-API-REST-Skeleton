<?php

require_once "../src/app/ModelDAO/CabeceraPedidoDAO.php";


use Slim\Http\Request;
use Slim\Http\Response;

class CabeceraPedidoApi 
{
    public static function TraerPedidoPorMesa(Request $request, Response $response, $args) 
    {   
        $idMesa = $request->getAttribute('id');
        $auxResponse = CabeceraPedidoDAO::TraerPedidoPorMesa($idMesa);
        echo $auxResponse->getMensaje();
    }

    public static function CargarUno (Request $request, Response $response, $args) 
    {
        
    }

}



?>