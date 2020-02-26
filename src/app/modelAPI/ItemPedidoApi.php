<?php

require_once '../src/app/model/ItemPedido.php';
require_once '../src/app/ModelDAO/ItemPedidoDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class ItemPedidoApi
{
    public static function CargarUno(Request $request, Response $response)
    {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoApi->CargarUno";
        $nroMesa = $request->getAttribute('nroMesa');
        $parametros = $request->getParsedBody();
        $id_Articulo = $parametros["articulo"];
        $cantidad = $parametros["cantidad"];
        $unItem = new ItemPedido();
        $idPedido;

        // Verificamos el estado de la mesa
        $auxReturn = MesaDAO::TraerUno($nroMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $mesaSeleccionada = $auxReturn->getMensaje();

            // Verificamos que no se encuentre cerrada
            if ($mesaSeleccionada->getEstado() == 1) {
                $auxReturn = new Resultado(true, "La mesa se encuentra cerrada", EstadosError::ERROR_OPERACION_INVALIDA);

                // SI la mesa tiene un estado distinto a cerrada
            } else {
                // Verificamos que la mesa tenga un pedido vigente
                $auxReturn = CabeceraPedidoDAO::TraerPedidoPorMesa($mesaSeleccionada->getIdMesa());
                if ($auxReturn->getStatus() == EstadosError::OK) {
                    $idPedido = $auxReturn->getMensaje();
                    $auxReturn = ArticuloDAO::VerificarEstado($id_Articulo);
                    if ($auxReturn->getStatus() == EstadosError::OK) {

                        $unItem->setidArticulo($id_Articulo);
                        $unItem->setIdPedido($idPedido);
                        $unItem->setCantidad($cantidad);

                        $unItem->setIdUsuarioOwner($request->getHeader("datosUsuario")[0]->id_usuario);

                        $auxReturn = ItemPedidoDAO::CargarUno($unItem);
                    }
                } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                    $mensaje = "La mesa seleccionada (Nro: " . $mesaSeleccionada->getNumeroMesa() . ") no tiene pedidos abiertos actualmente";
                    $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);
                }
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function CargarVarios(Request $request, Response $response)
    {
        $parametros = $request->getParsedBody();
        $idMesa = $request->getAttribute('nroMesa');
        $auxReturn = new Resultado(false, null, null);
        $articulos = $parametros["articulos"];
        $unNuevoItem;
        $idPedido;

        // Verificamos el estado de la mesa
        $auxReturn = self::VerificarEstadoMesa($idMesa);
        if ($auxReturn->getIsError() == false && $auxReturn->getStatus() == EstadosError::OK) {

            // Nos traemos el numero de pedidio de la mesa
            $auxReturn = CabeceraPedidoDAO::TraerPedidoPorMesa($idMesa);
            if ($auxReturn->getStatus() == EstadosError::OK) {
                $idPedido = $auxReturn->getMensaje();

                //Verificamos el estado de los articulos que nos cargaron como items del pedido
                $auxReturn = self::VerificarEstadoArticulos($articulos);
                if ($auxReturn->getIsError() == false && $auxReturn->getStatus() == EstadosError::OK) {

                    //Si llegamos hasta aca, entonces guardamos en la base los items del pedido
                    foreach ($articulos as $unArticulo) {
                        $unNuevoItem = new ItemPedido();
                        $unNuevoItem->setidArticulo($unArticulo["id"]);
                        $unNuevoItem->setIdPedido($idPedido);
                        $unNuevoItem->setCantidad($unArticulo["cantidad"]);

                        $unNuevoItem->setIdUsuarioOwner($request->getHeader("datosUsuario")[0]->id_usuario);

                        $auxReturn = ItemPedidoDAO::CargarUno($unNuevoItem);
                    }
                }
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

    public static function TraerTodosLosPendientes(Request $request, Response $response)
    {
        $auxReturn = ItemPedidoDAO::TraerTodosLosPendientes(99);

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

    public static function TraerPendientesPorSector(Request $request, Response $response)
    {
        $idSector = $request->getAttribute('idSector');

        // Verificamos si existe el sector
        $auxReturn = SectorDAO::TraerUno($idSector);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $sectorSeleccionado = $auxReturn->getMensaje();
            $auxReturn = ItemPedidoDAO::TraerTodosLosPendientes($idSector);

            // Si no tiene resultados modificamos el mensaje
            if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                $mensaje = "No hay pedidos pendientes para el sector seleccionado: " . strtoupper($sectorSeleccionado->getDescripcionSector());
                $auxReturn = new Resultado(true, $mensaje, EstadosError::SIN_RESULTADOS);
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function TraerPedidosPendientesRolActual(Request $request, Response $response)
    {

        $idRol = $request->getHeader("datosUsuario")[0]->id_rol;
        $idUsuario = $request->getHeader("datosUsuario")[0]->id_usuario;
        
        $auxReturn = ItemPedidoDAO::TraerTodosLosPendientes($idRol);
        // Si no tiene resultados modificamos el mensaje
        if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
            $auxReturn = UsuarioDAO::TraerUno($idUsuario);
            $usuarioActual = $auxReturn->getMensaje();
            $nombreUsuarioActual = sprintf("%s (%s)", $usuarioActual->getNombreUsuario(), strtoupper(Roles::TraerRolPorId($idRol)));
            $mensaje = "No hay pedidos pendientes para el usuario actual: $nombreUsuarioActual";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::SIN_RESULTADOS);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function TomarItemPedido(Request $request, Response $response)
    {

        $parametros = $request->getParsedBody();
        $tiempoEstimado = $parametros["tiempo_estimado"];
        $idItemPedido = $request->getAttribute('idItemPedido');
        $idUsuario = $request->getHeader("datosUsuario")[0]->id_usuario;
        $idRolUsuario = $request->getHeader("datosUsuario")[0]->id_rol;


        $auxReturn = self::VerificarSector($idItemPedido, $idRolUsuario);
        if ($auxReturn->getStatus() == EstadosError::OK) {

            // Verificamos si el item se encuentra con estado Pendiente
            $auxReturn = ItemPedidoDAO::VerificarEstado($idItemPedido);
            if ($auxReturn->getMensaje() == 1) {
                $auxReturn = ItemPedidoDAO::TomarPedido($idItemPedido, $idUsuario, $tiempoEstimado);

            } else if ($auxReturn->getStatus() == EstadosError::OK && $auxReturn->getMensaje() != 1) { // Si el item se encontro pero el estado es distinto de 1 (pendiente)
                $strEstadoActual = strtoupper(EstadosItemPedido::TraerEstadoPorId($auxReturn->getMensaje()));
                $mensaje = "El item del pedido no puede ser tomado dado que su estado es: $strEstadoActual (solo es posible tomar pedidos con estado PENDIENTE)";
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

    private static function VerificarSector($idItemPedido, $idRolUsuario)
    {
        // Verificamos si el rol del usuario corresponde al del articulo
        $auxReturn = ItemPedidoDAO::TraerArticuloByIdItemPedido($idItemPedido);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $articuloSeleccionado = $auxReturn->getMensaje();
            $sectorArticuloSeleccionado = $articuloSeleccionado->getIdSector();

            if ($sectorArticuloSeleccionado != $idRolUsuario && $idRolUsuario != Roles::SOCIO[0]) {
                $mensaje = "El item del pedido corresponde al rol de: " . strtoupper(Roles::TraerRolPorId($sectorArticuloSeleccionado)) . " y el rol del usuario actual es: " . strtoupper(Roles::TraerRolPorId($idRolUsuario));
                $auxReturn = new Resultado(false, $mensaje, EstadosError::ERROR_SIN_PERMISOS);
            } else {
                $auxReturn = new Resultado(false, "El usuario puede tomar el item del pedidio", EstadosError::OK);
            }
        }

        return $auxReturn;
    }

    public static function TraerUno(Request $request, Response $response)
    {
        $idItemPedido = $request->getAttribute('idItemPedido');
        $auxReturn = ItemPedidoDAO::TraerUno($idItemPedido);
        $itemPedidoSeleccionado = new ItemPedido();


        // Formateamos la salida
        if ($auxReturn->getStatus() == EstadosError::OK)
        {
            $itemPedidoSeleccionado = $auxReturn->getMensaje();
            $unItemPedido = new stdClass();

            $unItemPedido->id_Pedido = $itemPedidoSeleccionado->getIdPedido();
            $unItemPedido->id_Item = $itemPedidoSeleccionado->getIdItemPedido();
            $unItemPedido->nombre_cliente = $itemPedidoSeleccionado->getNombreCliente();
            $unItemPedido->codigo_amigable = $itemPedidoSeleccionado->getCodigoAmigable();
            $unItemPedido->hora_Inicio = $itemPedidoSeleccionado->getFechaHoraInicio();
            $unItemPedido->hora_Fin = $itemPedidoSeleccionado->getFechaHoraFin();
            $unItemPedido->articulo = $itemPedidoSeleccionado->getDescripcionArticulo();
            $unItemPedido->sector = $itemPedidoSeleccionado->getDescripcionSector();
            $unItemPedido->cantidad = $itemPedidoSeleccionado->getCantidad();
            $unItemPedido->tiempo_estimado = $itemPedidoSeleccionado->getTiempoEstimado();
            $unItemPedido->usuario_creador = $itemPedidoSeleccionado->getUsuarioCreador();
            $unItemPedido->usuario_asignado = $itemPedidoSeleccionado->getUsuarioAsignado();
            $unItemPedido->estado = EstadosItemPedido::TraerEstadoPorId($itemPedidoSeleccionado->getEstado());
        
            $auxReturn->setMensaje($unItemPedido);
        }


        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

    private static function VerificarEstadoMesa($idMesa)
    {
        // Verificamos el estado de la mesa
        $auxReturn = MesaDAO::VerificarEstado($idMesa);

        if ($auxReturn->getStatus() == EstadosError::OK) {
            // Verificamos que no se encuentre cerrada
            if ($auxReturn->getMensaje() == 1) {
                $auxReturn = new Resultado(true, "La mesa se encuentra cerrada. Antes de cargarle un pedido, debe abrirla.", EstadosError::ERROR_PARAMETROS_INVALIDOS);
            } else if ($auxReturn->getMensaje() == 0) {
                $auxReturn = new Resultado(true, "La mesa se encuentra deshabilitada. No se puede continuar con la operacion", EstadosError::ERROR_PARAMETROS_INVALIDOS);
            }
        } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
            $auxReturn = new Resultado(true, "No se encontro ninguna mesa con los datos proporcionados", EstadosError::ERROR_PARAMETROS_INVALIDOS);
        }

        return $auxReturn;
    }

    private static function VerificarEstadoArticulos($articulos)
    {
        $arrayArticulosNoDisponibles = [];
        $auxReturn = new Resultado(false, "Sin Errores", EstadosError::OK);

        // Verificamos el estado de los articulos que nos cargaron como items del pedido
        foreach ($articulos as $unArticulo) {
            $auxReturn = ArticuloDAO::VerificarEstado($unArticulo["id"]);
            if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                array_push($arrayArticulosNoDisponibles, $unArticulo["id"]);
            }
        }

        if (count($arrayArticulosNoDisponibles) > 0) {
            $mensaje = "Los siguientes articulos no existen o se encuentran deshabilitado: " . implode(', ', $arrayArticulosNoDisponibles);
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
        }

        return $auxReturn;
    }

}
