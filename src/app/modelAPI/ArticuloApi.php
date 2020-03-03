<?php

use Slim\Http\Request;
use Slim\Http\Response;

class ArticuloAPI
{
    public function TraerTodos(Request $request, Response $response, $args)
    {
        $auxReturn = ArticuloDAO::TraerTodos();
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function TraerUno(Request $request, Response $response, $args)
    {
        $idArticulo = $request->getAttribute('id');

        $auxReturn = ArticuloDAO::TraerUno($idArticulo);
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function CargarUno(Request $request, Response $response, $args)
    {
        $parametrosPOST = $request->getParsedBody();

        // Verificamos que no exista otro articulo con esa descripcion
        $auxReturn = ArticuloDAO::VerificarSiExisteArticuloPorDescripcion($parametrosPOST["descripcion"]);
        if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
            $auxReturn = ArticuloDAO::CargarUno($parametrosPOST);
        } else if ($auxReturn->getStatus() == EstadosError::OK && $auxReturn->getIsError() == false) {
            $auxReturn->setIsError(true);
            $auxReturn->setStatus(EstadosError::ERROR_RECURSO_REPETIDO);
            $mensaje = "Existe un articulo con esa descripcion con ID: " . $auxReturn->getMensaje();
            $auxReturn->setMensaje($mensaje);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function BorrarUno(Request $request, Response $response, $args)
    {
        $idArticulo = $request->getAttribute('id');

        // Verificamos que exista el articulo
        $auxReturn = ArticuloDAO::VerificarEstado($idArticulo);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            // Verificamos que no se encuentre en algun pedido activo
            $auxReturn = ItemPedidoDAO::TraerItemPedidoPorIdArticulo($idArticulo);
            if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                $auxReturn = ArticuloDAO::BorrarUno($idArticulo);
            } else if ($auxReturn->getStatus() == EstadosError::OK && $auxReturn->getIsError() == false) {
                $unItemPedido = $auxReturn->getMensaje();
                $mensaje = "No se puede borrar el articulo ya que se encuentra en al menos un pedido activo: " . $unItemPedido->getIdPedido();
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function ModificarUno(Request $request, Response $response, $args)
    {
        $idArticulo = $request->getAttribute('id');
        $parametrosPOST = $request->getParsedBody();

        // Verificamos si existe el sector
        $auxReturn = SectorDAO::TraerUno($parametrosPOST["id_sector"]);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            // Verificamos que no exista otro articulo con esa descripcion pero que no sea el mismo que estamos editando
            $auxReturn = ArticuloDAO::VerificarSiExisteArticuloPorDescripcion($parametrosPOST["descripcion"]);

            // No existe ninguno con esa descripcion o si el que existe es el mismo que estamos editando
            if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS ||
                ($auxReturn->getStatus() == EstadosError::OK && $auxReturn->getMensaje() == $idArticulo)) {

                $auxReturn = ArticuloDAO::ModificarUno($idArticulo, $parametrosPOST);

                // Si hay otro articulo con la misma descripcion (y que no sea el mismo que estamos editando)
            } else if ($auxReturn->getStatus() == EstadosError::OK && $auxReturn->getIsError() == false && $auxReturn->getMensaje() != $idArticulo) {
                $auxReturn->setIsError(true);
                $auxReturn->setStatus(EstadosError::ERROR_RECURSO_REPETIDO);
                $mensaje = "Existe un articulo con esa descripcion con ID: " . $auxReturn->getMensaje();
                $auxReturn->setMensaje($mensaje);
            }

        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

}
