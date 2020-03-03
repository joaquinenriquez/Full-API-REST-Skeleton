<?php

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

    public static function Contestar(Request $request, Response $response, $args) 
    {   
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "CabeceraPedido->Contestar";
        $identificacionMesa = $request->getAttribute('identificadorPedido');
        $parametros = $request->getParsedBody();

        $calificacionRestaurante = $parametros["calificacion_restaurante"];
        $calificacionMozo = $parametros["calificacion_mozo"];
        $calificacionCocinero = $parametros["calificacion_cocinero"];
        $calificacionMesa = $parametros["calificacion_mesa"];
        $comentarios = $parametros["comentarios"];

        // Verificamos el estado de la mesa
        $auxReturn = CabeceraPedidoDAO::TraerUnoPorIdOCodigoAmigable($identificacionMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) 
        {
            $pedidoSeleccionado = $auxReturn->getMensaje();

                $unaCabeceraPedido = new CabeceraPedido();

                $unaCabeceraPedido->setCalificacion_restaurante($calificacionRestaurante);
                $unaCabeceraPedido->setCalificacion_mozo($calificacionMozo);
                $unaCabeceraPedido->setCalificacion_cocinero($calificacionCocinero);
                $unaCabeceraPedido->setCalificacion_mozo($calificacionMesa);
                $unaCabeceraPedido->setComentarios($comentarios);

                CabeceraPedidoDAO::GuardarOpioniones($pedidoSeleccionado->getIdPedido(), $unaCabeceraPedido);
            }
        }



    

    public static function CargarUno (Request $request, Response $response, $args) 
    {
        
    }

    public function TraerUnoPorIdOCodigoAmigable(Request $request, Response $response, $args)
    {
        $identificadorPedido = $request->getAttribute('identificadorPedido');
        $auxReturn = CabeceraPedidoDAO::TraerUnoPorIdOCodigoAmigable($identificadorPedido);

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }



}



?>