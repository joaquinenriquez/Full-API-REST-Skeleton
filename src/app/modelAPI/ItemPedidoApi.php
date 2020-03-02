<?php

require_once '../src/app/model/ItemPedido.php';
require_once '../src/app/ModelDAO/ItemPedidoDAO.php';
require_once '../src/app/enum/Roles.php';
require_once '../src/app/ModelDAO/UsuarioDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Translation\Interval;

class ItemPedidoApi
{
    public static function CargarUno(Request $request, Response $response)
    {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoApi->CargarUno";
        $identificacionMesa = $request->getAttribute('identificadorMesa');
        $parametros = $request->getParsedBody();
        $id_Articulo = $parametros["articulo"];
        $cantidad = $parametros["cantidad"];
        

        // Verificamos el estado de la mesa
        $auxReturn = MesaDAO::TraerUno($identificacionMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $mesaSeleccionada = $auxReturn->getMensaje();

            // Verificamos que no se encuentre cerrada
            if ($mesaSeleccionada->getEstado() == EstadosMesas::CERRADA[0]) {
                $auxReturn = new Resultado(true, "La mesa $identificacionMesa se encuentra cerrada. Primero abrala e intente nuevamente.", EstadosError::ERROR_OPERACION_INVALIDA);

                // SI la mesa tiene un estado distinto a cerrada
            } else  if ($mesaSeleccionada->getEstado() == EstadosMesas::CON_CLIENTES_PAGANDO[0]) {
                $auxReturn = new Resultado(true, "No se pueden agregar mas items al pedido dado que la mesa se encuentra en estado CON CLIENTES PAGANDO", EstadosError::ERROR_OPERACION_INVALIDA);
            } else 
            {
                // Verificamos que la mesa tenga un pedido vigente
                $auxReturn = CabeceraPedidoDAO::TraerPedidoPorMesa($mesaSeleccionada->getIdMesa());
                if ($auxReturn->getStatus() == EstadosError::OK) {
                    $idPedido = $auxReturn->getMensaje();
                    $auxReturn = ArticuloDAO::VerificarEstado($id_Articulo);
                    if ($auxReturn->getStatus() == EstadosError::OK) {

                        date_default_timezone_set('America/Argentina/Buenos_Aires');
                        $unItem = new ItemPedido();
                        $unItem->setidArticulo($id_Articulo);
                        $unItem->setIdPedido($idPedido);
                        $unItem->setCantidad($cantidad);
                        $unItem->setEstado(EstadosItemPedido::PENDIENTE[0]);
                        $unItem->setFechaHoraCreacion(date('Y-m-d H:i:s'));
                        $unItem->setIdUsuarioOwner($request->getHeader("datosUsuario")[0]->id_usuario);

                        MesaDAO::CambiarEstado($mesaSeleccionada->getIdMesa(), EstadosMesas::CON_CLIENTES_ESPERANDO_PEDIDO);
                        $auxReturn = ItemPedidoDAO::CargarUno($unItem);
                    }
                } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                    $mensaje = "La mesa seleccionada (Nro: " . $mesaSeleccionada->getNumeroMesa() . ") no tiene pedidos abiertos actualmente. Abra la mesa primero e intente nuevamente.";
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
        $identificacionMesa = $request->getAttribute('identificadorMesa');
        $auxReturn = new Resultado(false, null, null);
        $articulos = $parametros["articulos"];

        // Verificamos el estado de la mesa
        $auxReturn = MesaDAO::TraerUno($identificacionMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) 
        {
            if ($auxReturn->getStatus() == EstadosError::OK) 
            {
                $mesaSeleccionada = $auxReturn->getMensaje();
                if ($mesaSeleccionada->getEstado() == EstadosMesas::CON_CLIENTES_PAGANDO[0])
                {
                    $auxReturn = new Resultado(true, "No se pueden agregar mas items al pedido dado que la mesa se encuentra en estado CON CLIENTES PAGANDO", EstadosError::ERROR_OPERACION_INVALIDA);

                } else 
                {
                    // Nos traemos el numero de pedidio de la mesa
                    $auxReturn = CabeceraPedidoDAO::TraerPedidoPorMesa($mesaSeleccionada->getIdMesa());
                    if ($auxReturn->getStatus() == EstadosError::OK) 
                    {
                        $idPedido = $auxReturn->getMensaje();
                        //Verificamos el estado de los articulos que nos cargaron como items del pedido
                        $auxReturn = self::VerificarEstadoArticulos($articulos);
                        if ($auxReturn->getIsError() == false && $auxReturn->getStatus() == EstadosError::OK) 
                        {
                            //Si llegamos hasta aca, entonces guardamos en la base los items del pedido
                            foreach ($articulos as $unArticulo) 
                            {
                                date_default_timezone_set('America/Argentina/Buenos_Aires');

                                $unNuevoItem = new ItemPedido();
                                $unNuevoItem->setidArticulo($unArticulo["id"]);
                                $unNuevoItem->setIdPedido($idPedido);
                                $unNuevoItem->setCantidad($unArticulo["cantidad"]);
                                $unNuevoItem->setEstado(EstadosItemPedido::PENDIENTE[0]);
                                $unNuevoItem->setFechaHoraCreacion(date('Y-m-d H:i:s'));
                                $unNuevoItem->setIdUsuarioOwner($request->getHeader("datosUsuario")[0]->id_usuario);

                                $auxReturn = ItemPedidoDAO::CargarUno($unNuevoItem);
                            }
                        }
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
        $auxReturn = ItemPedidoDAO::TraerPedidos(1, null, null, null); // Estado, Sector, idMesa, IdUsuarioAsignado

        if ($auxReturn->getStatus() == EstadosError::OK) {
            // Tuniamos la salida
            $listadoPedidosPendientes = $auxReturn->getMensaje();
            $listadoPedidosPendientesFormateados = [];

            foreach ($listadoPedidosPendientes as $unItemPedido) {
                $unItemPedidoFormateado = self::FormatearItemPedido($unItemPedido, EstadosItemPedido::PENDIENTE);
                array_push($listadoPedidosPendientesFormateados, $unItemPedidoFormateado);
            }
            $auxReturn->setMensaje($listadoPedidosPendientesFormateados);

        } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
            $auxReturn->setMensaje("No hay items de pedidos pendientes");
        }

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

            $auxReturn = ItemPedidoDAO::TraerPedidos(1, $idSector, null, null); // Estado, Sector, idMesa, Usuario asignado

            if ($auxReturn->getStatus() == EstadosError::OK) {
                // Tuniamos la salida
                $listadoPedidosPendientes = $auxReturn->getMensaje();
                
                $listadoPedidosPendientesFormateados = [];

                foreach ($listadoPedidosPendientes as $unItemPedido) {
                    $unItemPedidoFormateado = self::FormatearItemPedido($unItemPedido, EstadosItemPedido::PENDIENTE);
                    array_push($listadoPedidosPendientesFormateados, $unItemPedidoFormateado);
                }

                $auxReturn->setMensaje($listadoPedidosPendientesFormateados);

            } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                $auxReturn->setMensaje("No hay items de pedidos pendientes");
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

        $auxReturn = ItemPedidoDAO::TraerPedidos(EstadosItemPedido::PENDIENTE[0], $idRol, null, null);
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

    public static function FinalizarPreparacionItemPedido(Request $request, Response $response)
    {
        $idItemPedido = $request->getAttribute('idItemPedido');
        $idUsuarioActual = $request->getHeader("datosUsuario")[0]->id_usuario;
        $idRolUsuarioActual = $request->getHeader("datosUsuario")[0]->id_rol;

        //Nos traemos el itemPedido
        $auxReturn = ItemPedidoDAO::TraerUno($idItemPedido);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $itemPedidoSeleccionado = $auxReturn->getMensaje();

            // Primero verificamos que el item del pedido se encuentre con estado EN PREPARACION
            if ($itemPedidoSeleccionado->getEstado() != EstadosItemPedido::EN_PREPARACION[0]) 
            {                    
                $mensaje = sprintf("El item del pedido se encuentra con estado: %s (Para poder finalizarlo se debe encontrar con estado EN PREPARACION).", EstadosItemPedido::TraerEstadoPorId($itemPedidoSeleccionado->getEstado()));
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);

                // Luego si el usuario actual es el asignado o es un socio
            } else if ($idRolUsuarioActual != Roles::SOCIO[0] && $itemPedidoSeleccionado->getIdUsuarioAsignado() != $idUsuarioActual) 
            {
                $nombreUsuarioAsignado = UsuarioDAO::TraerUno($itemPedidoSeleccionado->getIdUsuarioAsignado())->getMensaje()->getNombreUsuario();
                $nombreUsuarioActual = UsuarioDAO::TraerUno($idUsuarioActual)->getMensaje()->getNombreUsuario();
                $mensaje = sprintf("El pedido solo puede ser finalizado por el usuario que lo tomo (%s) o por otro usuario con rol de Socio. Su usuario es: %s (Rol: %s)", $nombreUsuarioAsignado, $nombreUsuarioActual, Roles::TraerRolPorId($idRolUsuarioActual));
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);
            } else 
            {
                // Actualizamos el estado del item pedido
                $auxReturn = ItemPedidoDAO::FinalizarPreparacionItemPedido($idItemPedido);
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

    public static function CancelarItemPedido(Request $request, Response $response)
    {
        $idUsuarioActual = $request->getHeader("datosUsuario")[0]->id_usuario;
        $idItemPedido = $request->getAttribute('idItemPedido');
        $idRolUsuarioActual = $request->getHeader("datosUsuario")[0]->id_rol;


        if ($idRolUsuarioActual != Roles::SOCIO[0])
        {
            $nombreUsuarioActual = UsuarioDAO::TraerUno($idUsuarioActual)->getMensaje()->getNombreUsuario();
            $mensaje = sprintf("Solo pueden cancelar pedidos los usuarios con rol de Socio. El usuario actual es %s (%s)", $nombreUsuarioActual, Roles::TraerRolPorId($idRolUsuarioActual)); 
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_SIN_PERMISOS);
        } else
        {
            $auxReturn = ItemPedidoDAO::TraerUno($idItemPedido);
            if ($auxReturn->getStatus() == EstadosError::OK)
            {
                $itemPedidoSeleccionado = $auxReturn->getMensaje();
                if ($itemPedidoSeleccionado->getEstado() != EstadosItemPedido::CANCELADO[0])
                {
                    $auxReturn = ItemPedidoDAO::CancelarItemPedido($idItemPedido);
                } else 
                {
                    $auxReturn = new Resultado (true, "El item de pedido seleccionado ($idItemPedido) ya se encuentra cancelado.", EstadosError::ERROR_OPERACION_INVALIDA);
                }
            }
            
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

    public static function ServirItemPedido(Request $request, Response $response) 
    {
        $idItemPedido = $request->getAttribute('idItemPedido');
        $idUsuarioActual = $request->getHeader("datosUsuario")[0]->id_usuario;
        $idRolUsuarioActual = $request->getHeader("datosUsuario")[0]->id_rol;

        //Nos traemos el itemPedido
        $auxReturn = ItemPedidoDAO::TraerUno($idItemPedido);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $itemPedidoSeleccionado = $auxReturn->getMensaje();

            // Primero verificamos que el item del pedido se encuentre con estado EN PREPARACION
            if ($itemPedidoSeleccionado->getEstado() != EstadosItemPedido::LISTO_PARA_SERVIR[0]) 
            {                    
                $mensaje = sprintf("El item del pedido se encuentra con estado: %s (Para poder entregarlo se debe encontrar con estado LISTO PARA SERVIR).", EstadosItemPedido::TraerEstadoPorId($itemPedidoSeleccionado->getEstado()));
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);

                // Luego si el usuario actual es el asignado o es un socio
            } else if ($idRolUsuarioActual != Roles::SOCIO[0] && $idRolUsuarioActual != Roles::MOZO[0]) 
            {
                $nombreUsuarioActual = UsuarioDAO::TraerUno($idUsuarioActual)->getMensaje()->getNombreUsuario();
                $mensaje = sprintf("El pedido solo puede ser entregado por un usuario con rol de Mozo (o por un usuario con rol de Socio). Su usuario es: %s (Rol: %s)", $nombreUsuarioActual, Roles::TraerRolPorId($idRolUsuarioActual));
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);
            } else 
            {
                // Actualizamos el estado del item pedido y actualizamos el estado de la mesa
                $auxReturn = ItemPedidoDAO::ServirItemPedido($idItemPedido);
                MesaDAO::CambiarEstado($itemPedidoSeleccionado->getIdMesa(), EstadosMesas::CON_CLIENTES_COMIENDO);
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

        if ($auxReturn->getStatus() == EstadosError::OK) {
            // Tuniamos la salida
            $itemPedido = $auxReturn->getMensaje();
            $unItemPedidoFormateado = self::FormatearItemPedido($itemPedido, null); // null es Cualquier estado
            $auxReturn->setMensaje($unItemPedidoFormateado);

        } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
            $auxReturn->setMensaje("No existe ningun item de pedido con ese id ($idItemPedido)");
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

    public static function TraerTodosLosPedidosTomados(Request $request, Response $response)
    {
        $auxReturn = ItemPedidoDAO::TraerPedidos(2, null, null, null); // Estado, Sector, idPedido, Usuario Asignado

        if ($auxReturn->getStatus() == EstadosError::OK) {
            // Tuniamos la salida
            $listadoPedidosPendientes = $auxReturn->getMensaje();    
            $listadoPedidosPendientesFormateados = [];
            foreach ($listadoPedidosPendientes as $unItemPedido) {
                $unItemPedidoFormateado = self::FormatearItemPedido($unItemPedido, EstadosItemPedido::EN_PREPARACION);
                array_push($listadoPedidosPendientesFormateados, $unItemPedidoFormateado);
            }
            
            $auxReturn->setMensaje($listadoPedidosPendientesFormateados);

        } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
            $auxReturn->setMensaje("No hay items de pedidos en preparacion");
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function TraerPedidosTomadosPorUsuario(Request $request, Response $response)
    {

        $idUsuario = $request->getAttribute('idUsuario');
        $auxReturn = ItemPedidoDAO::TraerPedidosTomadosPorUsuario($idUsuario);

        // Formateamos la salida
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $listadoItemPedidoFormateado = [];

            $listadoDeItemsPedido = $auxReturn->getMensaje();
            foreach ($listadoDeItemsPedido as $unItemPedido) {
                $unItemPedidoFormateado = new stdClass();
                $unItemPedidoFormateado->id_item_pedido = $unItemPedido->getIdItemPedido();
                $unItemPedidoFormateado->fecha_hora_creacion = $unItemPedido->getFechaHoraCreacion();
                $unItemPedidoFormateado->codigo_amigable = $unItemPedido->getCodigoAmigable();
                $unItemPedidoFormateado->nombre_cliente = $unItemPedido->getNombreCliente();
                $unItemPedidoFormateado->descripcion_articulo = $unItemPedido->getDescripcionArticulo();
                $unItemPedidoFormateado->cantidad = $unItemPedido->getCantidad();
                $unItemPedidoFormateado->descripcion_sector = $unItemPedido->getDescripcionSector();
                $unItemPedidoFormateado->usuario_creador = $unItemPedido->getUsuarioCreador();
                $unItemPedidoFormateado->usuario_asignado = replace_null($unItemPedido->getUsuarioAsignado(), '-');
                $unItemPedidoFormateado->fecha_hora_inicio_preparacion = replace_null($unItemPedido->getFechaHoraInicio(), '-');
                $unItemPedidoFormateado->tiempo_estimado = replace_null($unItemPedido->getTiempoEstimado(), '-');
                $unItemPedidoFormateado->fecha_hora_fin_preparacion = replace_null($unItemPedido->getFechaHoraFin(), '-');
                $unItemPedidoFormateado->estado = EstadosItemPedido::TraerEstadoPorId($unItemPedido->getEstado());

                array_push($listadoItemPedidoFormateado, $unItemPedidoFormateado);

            }

            $auxReturn->setMensaje($listadoItemPedidoFormateado);

        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
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

    public static function FormatearItemPedido($unItemPedido, $estadoItemPedido)
    {
        $unItemPedidoFormateado = new stdClass();
        $unItemPedidoFormateado->id_item_pedido = $unItemPedido->getIdItemPedido();
        $unItemPedidoFormateado->fecha_hora_creacion = $unItemPedido->getFechaHoraCreacion();
        $unItemPedidoFormateado->nombre_cliente = $unItemPedido->getNombreCliente();
        $unItemPedidoFormateado->codigo_amigable_pedido = $unItemPedido->getCodigoAmigable();
        $unItemPedidoFormateado->articulo = $unItemPedido->getDescripcionArticulo();
        $unItemPedidoFormateado->cantidad = $unItemPedido->getCantidad();
        $unItemPedidoFormateado->importe_unitario = '$' . $unItemPedido->getImporteArticulo();
        $unItemPedidoFormateado->usuario_creador = $unItemPedido->getUsuarioCreador();
        $unItemPedidoFormateado->id_mesa = $unItemPedido->getIdMesa();
        $unItemPedidoFormateado->codigo_amigable_mesa = $unItemPedido->getCodigoAmigableMesa();
        $unItemPedidoFormateado->estado = EstadosItemPedido::TraerEstadoPorId($unItemPedido->getEstado());


        // $estadoItemPedido = null es cualquier estado
        if ($estadoItemPedido == EstadosItemPedido::EN_PREPARACION || $estadoItemPedido == EstadosItemPedido::LISTO_PARA_SERVIR || $estadoItemPedido == null){
            $unItemPedidoFormateado->usuario_asignado = replace_null($unItemPedido->getIdUsuarioAsignado(), '-');
            $unItemPedidoFormateado->fecha_hora_inicio = replace_null($unItemPedido->getFechaHoraInicio(), '-');
            $unItemPedidoFormateado->tiempo_estimado = replace_null($unItemPedido->getTiempoEstimado(), '-');
            $unItemPedidoFormateado->fecha_hora_fin = replace_null($unItemPedido->getFechaHoraFin(), '-');
        }

        return $unItemPedidoFormateado;
    }

    public static function FormatearItemPedidoParaCliente($unItemPedido)
    {
        $unItemPedidoFormateado = new stdClass();
        $unItemPedidoFormateado->nombre_cliente = $unItemPedido->getNombreCliente();
        $unItemPedidoFormateado->articulo = $unItemPedido->getDescripcionArticulo();
        $unItemPedidoFormateado->cantidad = $unItemPedido->getCantidad();
        $unItemPedidoFormateado->estado = EstadosItemPedido::TraerEstadoPorId($unItemPedido->getEstado());

        if ($unItemPedido->getEstado() == EstadosItemPedido::EN_PREPARACION[0])
        {
            $unItemPedidoFormateado->fecha_hora_inicio = $unItemPedido->getFechaHoraInicio();
            $unItemPedidoFormateado->tiempo_estimado = $unItemPedido->getTiempoEstimado() . " minutos";
            $tiempoEstimado = $unItemPedido->getTiempoEstimado();

            $fechaCreacionPedido= new DateTime($unItemPedido->getFechaHoraInicio(), new DateTimeZone('America/Argentina/Buenos_Aires'));
            $horaEntrega = $fechaCreacionPedido->add(new DateInterval('PT' . $tiempoEstimado . 'M'));
            $horaActual = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));

            if ($horaActual > $horaEntrega)
            {
                $unItemPedidoFormateado->tiempo_restante = "El pedido se encuentra demorado";
            } else 
            {
                $diferencia = $horaActual->diff($fechaCreacionPedido);
                $unItemPedidoFormateado->tiempo_restante = $diferencia->format('%H:%I:%S');   
            }
        }

        return $unItemPedidoFormateado;
    }

    public static function TraerPedidosParaCliente(Request $request, Response $response)
    {
        $identificacionMesa = $request->getAttribute('codigoAmigable');
        $auxReturn = MesaDAO::TraerUno($identificacionMesa);
        if ($auxReturn->getStatus() == EstadosError::OK)
        {
            $mesaSeleccionada = $auxReturn->getMensaje();
            $auxReturn = ItemPedidoDAO::TraerPedidos(null, null, $mesaSeleccionada->getIdMesa(), null); // Estado, Sector, idMesa, IdUsuarioAsignado

            if ($auxReturn->getStatus() == EstadosError::OK) {
                // Tuniamos la salida
                $listadoPedidosPendientes = $auxReturn->getMensaje();
                $listadoPedidosPendientesFormateados = [];
    
                foreach ($listadoPedidosPendientes as $unItemPedido) {
                    $unItemPedidoFormateado = self::FormatearItemPedidoParaCliente($unItemPedido, EstadosItemPedido::PENDIENTE);
                    array_push($listadoPedidosPendientesFormateados, $unItemPedidoFormateado);
                }
                $auxReturn->setMensaje($listadoPedidosPendientesFormateados);
    
            } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                $auxReturn->setMensaje("No hay items de pedidos pendientes");
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    

}
