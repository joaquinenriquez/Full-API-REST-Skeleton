<?php

use Slim\Http\Request;
use Slim\Http\Response;

class MesaAPI
{
    public function TraerTodos(Request $request, Response $response, $args)
    {
        $auxReturn = MesaDAO::TraerTodos();

        if ($auxReturn->getStatus() == EstadosError::OK) {
            $listadoMesas = $auxReturn->getMensaje();
            $listadoMesasFormateadas = [];

            foreach ($listadoMesas as $unaMesa) {
                $unaMesaFormateada = Self::FormatearMesa($unaMesa);

                array_push($listadoMesasFormateadas, $unaMesaFormateada);
            }

            $auxReturn = new Resultado(false, $listadoMesasFormateadas, EstadosError::OK);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function TraerUno(Request $request, Response $response, $args)
    {
        $identificadorMesa = $request->getAttribute('identificadorMesa');
        $auxReturn = MesaDAO::TraerUno($identificadorMesa);

        // Formamos la salida
        if ($auxReturn->getStatus() == EstadosError::OK) {

            $unaMesaFormateada = Self::FormatearMesa($auxReturn->getMensaje());
            $auxReturn = new Resultado(false, $unaMesaFormateada, EstadosError::OK);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function CargarUno(Request $request, Response $response, $args)
    {
        $auxReturn = MesaDAO::CargarUno();
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function BorrarUno(Request $request, Response $response, $args)
    {
        $identificadorMesa = $request->getAttribute('identificadorMesa');
        // Verificamos el estado de la mesa
        $auxReturn = MesaDAO::TraerUno($identificadorMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $mesaSeleccionada = $auxReturn->getMensaje();

            // Verificamos si la mesa esta cerrada
            if ($mesaSeleccionada->getEstado() != EstadosMesas::CERRADA[0]) {
                $mensaje = sprintf("No es posible eliminar la mesa en este momento. Su estado actual es: %s (Debe estar con estado CERRADA)", EstadosMesas::TraerEstadoPorId($mesaSeleccionada->getEstado()));
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);
            } else {
                // Si la mesa esta cerrada entonces le cambiamos el estado a deshabilitada
                $auxReturn = MesaDAO::CambiarEstado($mesaSeleccionada->getIdMesa(), EstadosMesas::DESHABILITADA);
                if ($auxReturn->getStatus() == EstadosError::OK) {
                    $auxReturn = new Resultado(false, "Se elimino correctamente la mesa $identificadorMesa", EstadosError::OK);
                }
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
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
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $identificacionMesa = $request->getAttribute('identificadorMesa');
        $parametros = $request->getParsedBody();
        $idUsuario = $request->getHeader("datosUsuario")[0]->id_usuario;

        // Nos traemos la mesa seleccionada
        $auxReturn = MesaDAO::TraerUno($identificacionMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $mesaSeleccionada = $auxReturn->getMensaje();

            // Si la mesa tiene un estado distinta a cerrada
            if ($mesaSeleccionada->getEstado() != EstadosMesas::CERRADA[0]) {
                $mensaje = "La mesa ya se encuentra abierta! El estado actual es: " . EstadosMesas::TraerEstadoPorId($mesaSeleccionada->getEstado());
                $auxReturn = new Resultado(false, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);

                // Si la mesa se encuentra cerrada, la abrimos y creamos la cabecera de un pedido vacio
            } else if ($mesaSeleccionada->getEstado() == EstadosMesas::CERRADA[0]) {
                $auxReturn = MesaDAO::CambiarEstado($mesaSeleccionada->getIdMesa(), EstadosMesas::CON_CLIENTES_ELIGIENDO);

                if ($auxReturn->getStatus() == EstadosError::OK) {

                    date_default_timezone_set('America/Argentina/Buenos_Aires');

                    $nuevoPedido = new CabeceraPedido();
                    $nuevoPedido->setIdMesa($mesaSeleccionada->getIdMesa());
                    $nuevoPedido->setNombreCliente($parametros["nombre_cliente"]);
                    $nuevoPedido->setIdUsuario($idUsuario);
                    $nuevoPedido->setCodigoAmigable(GenerarCodigoAmigable());
                    $nuevoPedido->setFechaInicio(date('Y-m-d H:i:s'));
                    $nuevoPedido->setEstado(EstadosCabeceraPedido::ACTIVO[0]);

                    // Creamos un nuevo pedido vacio
                    $auxReturn = CabeceraPedidoDAO::CargarUno($nuevoPedido);

                    // Si el pedido se creo correctamente entonces informamos el codigo amigale
                    if ($auxReturn->getIsError() == false && $auxReturn->getStatus() == EstadosError::RECURSO_CREADO) {
                        $mensaje = "La mesa se abrio correctamente. El codigo del pedido para la identificacion por el cliente es: " . $nuevoPedido->getCodigoAmigable();
                        $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
                    }

                    // Copiamos la foto (ya esta validada en el middleware)
                    $archivos = $request->getUploadedFiles(); // Nos traemos las fotos
                    if (isset($archivos["foto"])) {
                        $pathFotos = 'assets/img/' . $nuevoPedido->getCodigoAmigable() . '.jpg';
                        $archivos["foto"]->moveTo($pathFotos);
                    }
                }
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function CerrarMesa(Request $request, Response $response)
    {
        $identificacionMesa = $request->getAttribute('identificadorMesa');
        $idUsuarioActual = $request->getHeader("datosUsuario")[0]->id_usuario;
        $idRolUsuarioActual = $request->getHeader("datosUsuario")[0]->id_rol;
        $listadoItemPedidosSinFinalizar = [];

        // Verificamos que el usuario tenga el rol de socio
        if ($idRolUsuarioActual != Roles::SOCIO[0]) 
        {
            $nombreUsuarioActual = UsuarioDAO::TraerUno($idUsuarioActual)->getMensaje()->getNombreUsuario();
            $mensaje = sprintf("La mesa solo puede ser cerrada por un usuario con rol de Socio. El usuario actual es: %s (Rol %s)", $nombreUsuarioActual, Roles::TraerRolPorId($idRolUsuarioActual));
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);

        } else {
            $auxReturn = MesaDAO::TraerUno($identificacionMesa);
            if ($auxReturn->getStatus() == EstadosError::OK) 
            {
                $mesaSeleccionada = $auxReturn->getMensaje();
                if ($mesaSeleccionada->getEstado() == EstadosMesas::CERRADA[0])
                {
                    $auxReturn = new Resultado(true, "La mesa $identificacionMesa se encuentra cerrada", EstadosError::ERROR_OPERACION_INVALIDA);

                } else 
                {
                    // Nos traemos todos los items de pedido de esa mesa
                    $auxReturn = ItemPedidoDAO::TraerPedidos(null, null, $mesaSeleccionada->getIdMesa(), null);
                    if ($auxReturn->getStatus() == EstadosError::OK) 
                    {
                        $listadoDeItemsPedido = $auxReturn->getMensaje();
                        // Verificamos que no se encuentren pedidos con pendientes

                        foreach ($listadoDeItemsPedido as $unItemPedido) 
                        {
                            $estadoItemPedido = $unItemPedido->getEstado();
    
                            if ($estadoItemPedido != EstadosItemPedido::ENTREGADO[0] && $estadoItemPedido != EstadosItemPedido::CANCELADO[0] && $estadoItemPedido != EstadosItemPedido::CERRADO[0]) 
                            {
                                $unItemPedidoFormateado = ItemPedidoApi::FormatearItemPedido($unItemPedido, null);
                                array_push($listadoItemPedidosSinFinalizar, $unItemPedidoFormateado);
                            }
                        }
                        
                    }

                    if ((count($listadoItemPedidosSinFinalizar) > 0))
                    {
                        $mensaje = new stdClass();
                        $mensaje->mensaje = "Los siguientes items de pedidos que todavia no fueron entregados (para poder cerrar la mesa deben estar con estado Entregado o Cancelado)";
                        $mensaje->detalles = $listadoItemPedidosSinFinalizar;
                        $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);
                    } else 
                    {
                        $auxReturn = CabeceraPedidoDAO::TraerPedidoPorMesa($mesaSeleccionada->getIdMesa());
                        if ($auxReturn->getStatus() == EstadosError::OK)
                        {
                            // Cerramos el encabezado
                            $idPedido = $auxReturn->getMensaje();
                            $auxReturn = CabeceraPedidoDAO::CerrarPedido($idPedido);
                            if ($auxReturn->getStatus() == EstadosError::OK)
                            {
                                // Cerramos todos los items del pedido
                                if (isset($listadoDeItemsPedido)){
                                    foreach ($listadoDeItemsPedido as $unItemPedido)
                                    {
                                      ItemPedidoDAO::CerrarItemPedido($unItemPedido->getIdItemPedido());
                                    }
                                }


                            
                                // Cerramos la mesa
                                $auxReturn = MesaDAO::CambiarEstado($mesaSeleccionada->getIdMesa(), EstadosMesas::CERRADA);

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

    public static function PagarMesa(Request $request, Response $response)
    {
        $identificacionMesa = $request->getAttribute('identificadorMesa');
        $idUsuarioActual = $request->getHeader("datosUsuario")[0]->id_usuario;
        $idRolUsuarioActual = $request->getHeader("datosUsuario")[0]->id_rol;
        $importeMesa = 0;
        $listadoItemsCobrados = [];

        // Verificamos que el usuario tenga el rol de socio
        if ($idRolUsuarioActual != Roles::SOCIO[0] && $idRolUsuarioActual != Roles::MOZO[0]) 
        {
            $nombreUsuarioActual = UsuarioDAO::TraerUno($idUsuarioActual)->getMensaje()->getNombreUsuario();
            $mensaje = sprintf("La accion solo esta permitida para usuarios con rol de Mozo o Socio. El usuario actual es: %s (Rol %s)", $nombreUsuarioActual, Roles::TraerRolPorId($idRolUsuarioActual));
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);

        } else 
        {
            $auxReturn = MesaDAO::TraerUno($identificacionMesa);
            if ($auxReturn->getStatus() == EstadosError::OK) 
            {
                $mesaSeleccionada = $auxReturn->getMensaje();
                if ($mesaSeleccionada->getEstado() == EstadosMesas::CERRADA[0])
                {
                    $auxReturn = new Resultado(true, "La mesa $identificacionMesa se encuentra cerrada", EstadosError::ERROR_OPERACION_INVALIDA);

                } else if ($mesaSeleccionada->getEstado() == EstadosMesas::CON_CLIENTES_PAGANDO[0])
                {
                    $auxReturn = new Resultado(true, "La mesa $identificacionMesa ya se pago", EstadosError::ERROR_OPERACION_INVALIDA);
                } else
                {
                    // Nos traemos todos los items de pedido de esa mesa
                    $auxReturn = ItemPedidoDAO::TraerPedidos(null, null, $mesaSeleccionada->getIdMesa(), null);
                    if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS)
                    {
                        $auxReturn = new Resultado(false, "La mesa seleccionada no tiene ningun item de pedido realizado", EstadosError::ERROR_OPERACION_INVALIDA);
                    } else if ($auxReturn->getStatus() == EstadosError::OK) 
                    {
                        $listadoDeItemsPedido = $auxReturn->getMensaje();
                        // Verificamos que no se encuentren pedidos con pendientes

                        foreach ($listadoDeItemsPedido as $unItemPedido) 
                        {
                            $estadoItemPedido = $unItemPedido->getEstado();
    
                            if ($estadoItemPedido != EstadosItemPedido::CANCELADO[0] && $estadoItemPedido != EstadosItemPedido::CERRADO[0]) 
                            {
                                $importeArticulo = $unItemPedido->getImporteArticulo();
                                $cantidad = $unItemPedido->getCantidad();
                                
                                $unItemCobrado = new stdClass();
                                $unItemCobrado->descripcion_articulo = $unItemPedido->getDescripcionArticulo();
                                $unItemCobrado->importe_unitario = $importeArticulo;
                                $unItemCobrado->cantidad = $cantidad;
                                $unItemCobrado->total = $importeArticulo * $cantidad;

                                $importeMesa = $importeMesa + ($importeArticulo * $cantidad);
                                
                                //Nos quedamos con el id pedido para luego actualizar el importe
                                $idPedido = $unItemPedido->getIdPedido();

                                array_push($listadoItemsCobrados, $unItemCobrado);
                            }
                        }

                        if (count($listadoItemsCobrados) > 0) 
                        {
                            $mensaje = new stdClass();
                            $mensaje->mensaje = "El importe total de la mesa es: " . $importeMesa;
                            $mensaje->detalles = $listadoItemsCobrados;
    
                            $auxReturn = MesaDAO::CambiarEstado($mesaSeleccionada->getIdMesa(), EstadosMesas::CON_CLIENTES_PAGANDO);
                            if ($auxReturn->getStatus() == EstadosError::OK)
                            {
                                // Actualizamos el importe
                                CabeceraPedidoDAO::ActualizarImporte($idPedido, $importeMesa);
                                $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
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

    private static function FormatearMesa($unaMesa)
    {
        $unaMesaFormateada = new stdClass();
        $unaMesaFormateada->id_mesa = $unaMesa->getIdMesa();
        $unaMesaFormateada->codigo_amigable = $unaMesa->getCodigoAmigable();
        $unaMesaFormateada->estado = EstadosMesas::TraerEstadoPorId($unaMesa->getEstado());

        return $unaMesaFormateada;
    }

    public static function TraerMesaMasUsada(Request $request, Response $response)
    {
        $parametros = $request->getParsedBody();
        $fechaHoraDesde = FormatearFechaParaWhere($parametros["desde"]);
        $fechaHoraHasta = FormatearFechaParaWhere($parametros["hasta"]);

        $auxReturn = MesaDAO::TraerMesaMasUsada($fechaHoraDesde, $fechaHoraHasta);
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function TraerMesaMenosUsada(Request $request, Response $response)
    {
        $parametros = $request->getParsedBody();
        $fechaHoraDesde = FormatearFechaParaWhere($parametros["desde"]);
        $fechaHoraHasta = FormatearFechaParaWhere($parametros["hasta"]);

        $auxReturn = MesaDAO::TraerMesaMenosUsada($fechaHoraDesde, $fechaHoraHasta);
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function TraerMesaMasFacturo(Request $request, Response $response)
    {
        $parametros = $request->getParsedBody();
        $fechaHoraDesde = FormatearFechaParaWhere($parametros["desde"]);
        $fechaHoraHasta = FormatearFechaParaWhere($parametros["hasta"]);

        $auxReturn = MesaDAO::TraerMesaMasFacturo($fechaHoraDesde, $fechaHoraHasta);
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function TraerMesaMenosFacturo(Request $request, Response $response)
    {
        $parametros = $request->getParsedBody();
        $fechaHoraDesde = FormatearFechaParaWhere($parametros["desde"]);
        $fechaHoraHasta = FormatearFechaParaWhere($parametros["hasta"]);

        $auxReturn = MesaDAO::TraerMesaMenosFacturo($fechaHoraDesde, $fechaHoraHasta);
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function TraerMesasMayorImporte(Request $request, Response $response)
    {
        $parametros = $request->getParsedBody();
        $fechaHoraDesde = FormatearFechaParaWhere($parametros["desde"]);
        $fechaHoraHasta = FormatearFechaParaWhere($parametros["hasta"]);

        $auxReturn = MesaDAO::TraerMesasMayorImporte($fechaHoraDesde, $fechaHoraHasta);
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function TraerMesasMenorImporte(Request $request, Response $response)
    {
        $parametros = $request->getParsedBody();
        $fechaHoraDesde = FormatearFechaParaWhere($parametros["desde"]);
        $fechaHoraHasta = FormatearFechaParaWhere($parametros["hasta"]);

        $auxReturn = MesaDAO::TraerMesasMenorImporte($fechaHoraDesde, $fechaHoraHasta);
        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    

}
