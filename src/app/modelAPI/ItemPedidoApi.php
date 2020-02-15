<?php

require_once '../src/app/model/ItemPedido.php';
require_once '../src/app/ModelDAO/ItemPedidoDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class ItemPedidoApi
{
    public static function CargarUno(Request $request, Response $response)
    {
        $ubicacionParaMensaje = "ItemPedidoApi->CargarUno";

        $idMesa = $request->getAttribute('id');
        $parametros = $request->getParsedBody();
        $idArticulo = $parametros["idArticulo"];
        $cantidad = $parametros["cantidad"];
        $idPedido;
        $unItem = new ItemPedido();

        $auxReturn = MesaDAO::VerificarEstado($idMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) {

            if ($auxReturn->getMensaje() == 1) {
                $auxReturn->setMensaje("La mesa se encuentra cerrada");
            } else {
                $auxReturn = CabeceraPedidoDAO::TraerPedidoPorMesa($idMesa);
                if ($auxReturn->getStatus() == EstadosError::OK) {
                    $idPedido = $auxReturn->getMensaje();
                    $auxReturn = ArticuloDAO::VerificarEstado($idArticulo);
                    if ($auxReturn->getStatus() == EstadosError::OK) {

                        $unItem->setIdArticulo($idArticulo);
                        $unItem->setIdPedido($idPedido);
                        $unItem->setCantidad($cantidad);
                        $unItem->setIdUsuarioOwner("REMACHADOS");

                        $auxReturn = ItemPedidoDAO::CargarUno($unItem);
                    }
                }
            }
        }
        
        return (json_encode($auxReturn));
    }

    public static function TraerTodosLosPendientes() {
        return json_encode (ItemPedidoDAO::TraerTodosLosPendientes());
        $ubicacionParaMensaje = "ItemPedidoApi->TraerPendientes";
    }

}
