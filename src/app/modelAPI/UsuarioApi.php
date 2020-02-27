<?php

require_once "../src/app/ModelDAO/UsuarioDAO.php";
require_once "../src/app/model/Usuario.php";
require_once "../src/app/enum/EstadosUsuarios.php";

use Slim\Http\Request;
use Slim\Http\Response;

class UsuarioApi
{

    public static function CargarUno(Request $request, Response $response, $args)
    {

        $ubicacionParaMensaje = "UsuarioApi->CargarUno";
        $nuevoUsuario = new Usuario();

        $parametros = $request->getParsedBody();
        $nuevoUsuario->setNombreUsuario($parametros["nombre_usuario"]);
        $nuevoUsuario->setPassword($parametros["password"]);
        $nuevoUsuario->setSector($parametros["id_sector"]);
        $nuevoUsuario->setNombre($parametros["nombre"]);
        $nuevoUsuario->setApellido($parametros["apellido"]);
        $nuevoUsuario->setEstado(1);

        $auxReturn = UsuarioDAO::CargarUno($nuevoUsuario);

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return ($response);
    }

    public function TraerUno(Request $request, Response $response, $args)
    {
        $idUsuario = $request->getAttribute('idUsuario');
        $auxReturn = UsuarioDAO::TraerUno($idUsuario);
        $usuarioSeleccionado = new Usuario();

        // Formamos la salida
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $usuarioSeleccionado = $auxReturn->getMensaje();
            $usuarioSeleccionado->setRol(Roles::TraerRolPorId($usuarioSeleccionado->getRol()));
            $usuarioSeleccionado->setEstado(EstadosUsuarios::TraerEstadoPorId($usuarioSeleccionado->getEstado()));
            $auxReturn = new Resultado(false, $usuarioSeleccionado, EstadosError::OK);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function TraerUsuarioActual(Request $request, Response $response, $args)
    {
        $idUsuarioActual = $request->getHeader("datosUsuario")[0]->id_usuario;

        $auxReturn = UsuarioDAO::TraerUno($idUsuarioActual);
        $usuarioSeleccionado = new Usuario();

        // Formamos la salida
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $usuarioSeleccionado = $auxReturn->getMensaje();
            $usuarioSeleccionado->setRol(Roles::TraerRolPorId($usuarioSeleccionado->getRol()));
            $usuarioSeleccionado->setEstado(EstadosUsuarios::TraerEstadoPorId($usuarioSeleccionado->getEstado()));
            $auxReturn = new Resultado(false, $usuarioSeleccionado, EstadosError::OK);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

    public static function TraerTodos(Request $request, Response $response, $args)
    {
        $auxReturn = UsuarioDAO::TraerTodos();

        // Tuniamos la salida
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $listadoDeUsuarios = $auxReturn->getMensaje();
            foreach ($listadoDeUsuarios as $unUsuario) {
                $unUsuario->setRol(Roles::TraerRolPorId($unUsuario->getRol()));
                $unUsuario->setEstado(EstadosUsuarios::TraerEstadoPorId($unUsuario->getEstado()));
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function BorrarUno(Request $request, Response $response, $args)
    {
        $idUsuario = $request->getAttribute('idUsuario');

        // Verificamos si existe el usuario
        $auxReturn = UsuarioDAO::TraerUno($idUsuario);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            // Verificamos que el usuario no tenga ningun item pedido con estado EN PREPARACION
            $auxReturn = ItemPedidoDAO::TraerPedidosTomadosPorUsuario($idUsuario);
            if ($auxReturn->getIsError() == true) {
                $mensaje = "Ocurrio un error al intentar verificar si el usuario tiene pedidos vigentes en preparacion";
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);

            } else if ($auxReturn->getStatus() == EstadosError::OK) {

                $pedidosActivos = $auxReturn->getMensaje();
                $pedidosFormateados = [];

                // Formateamos la salida
                foreach ($pedidosActivos as $unPedido) {
                    $unPedidoFormateado = new stdClass();
                    $unPedidoFormateado->id_item_pedido = $unPedido->getIdItemPedido();
                    $unPedidoFormateado->codigo_amigable = $unPedido->getCodigoAmigable();
                    $unPedidoFormateado->nombre_cliente = $unPedido->getNombreCliente();
                    $unPedidoFormateado->descripcion_articulo = $unPedido->getDescripcionArticulo();
                    $unPedidoFormateado->usuario_asignado = $unPedido->getUsuarioAsignado();

                    array_push($pedidosFormateados, $unPedidoFormateado);
                }

                $mensaje = new stdClass();
                $mensaje->descripcion = "No es posible borrar el usuario dado que tiene los siguientes pedidos activos";
                $mensaje->detalles = $pedidosFormateados;
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);

            } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                $auxReturn = UsuarioDAO::CambiarEstado($idUsuario, EstadosUsuarios::DESHABILITADO[0]);
                if ($auxReturn->getStatus() == EstadosError::OK) {
                    $auxReturn->setMensaje("Se elimino correctamente el usuario!");
                }
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function SuspenderUsuario(Request $request, Response $response, $args)
    {
        $idUsuario = $request->getAttribute('idUsuario');

        // Verificamos si existe el usuario
        $auxReturn = UsuarioDAO::TraerUno($idUsuario);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            // Verificamos que el usuario no tenga ningun item pedido con estado EN PREPARACION
            $usuarioSeleccionado = $auxReturn->getMensaje();
            if ($usuarioSeleccionado->getEstado() == EstadosUsuarios::SUSPENDIDO[0]) {
                $auxReturn = new Resultado(false, "El usuario ya se encuentra con estado SUSPENDIDO", EstadosError::ERROR_OPERACION_INVALIDA);
            } else {

                $auxReturn = ItemPedidoDAO::TraerPedidosTomadosPorUsuario($idUsuario);
                if ($auxReturn->getIsError() == true) {
                    $mensaje = "Ocurrio un error al intentar verificar si el usuario tiene pedidos vigentes en preparacion";
                    $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);

                } else if ($auxReturn->getStatus() == EstadosError::OK) {

                    $pedidosActivos = $auxReturn->getMensaje();
                    $pedidosFormateados = [];

                    // Formateamos la salida
                    foreach ($pedidosActivos as $unPedido) {
                        $unPedidoFormateado = new stdClass();
                        $unPedidoFormateado->id_item_pedido = $unPedido->getIdItemPedido();
                        $unPedidoFormateado->codigo_amigable = $unPedido->getCodigoAmigable();
                        $unPedidoFormateado->nombre_cliente = $unPedido->getNombreCliente();
                        $unPedidoFormateado->descripcion_articulo = $unPedido->getDescripcionArticulo();
                        $unPedidoFormateado->usuario_asignado = $unPedido->getUsuarioAsignado();

                        array_push($pedidosFormateados, $unPedidoFormateado);
                    }

                    $mensaje = new stdClass();
                    $mensaje->descripcion = "No es posible suspender al usuario dado que tiene los siguientes pedidos activos";
                    $mensaje->detalles = $pedidosFormateados;
                    $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);

                } else if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                    $auxReturn = UsuarioDAO::CambiarEstado($idUsuario, EstadosUsuarios::SUSPENDIDO[0]);
                    if ($auxReturn->getStatus() == EstadosError::OK) {
                        $auxReturn->setMensaje("La operacion se realizo correctamente! Se cambio el estado del usuario a SUSPENDIDO.");
                    }
                }
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public static function ActivarUsuario(Request $request, Response $response, $args)
    {
        $idUsuario = $request->getAttribute('idUsuario');

        // Verificamos si existe el usuario
        $auxReturn = UsuarioDAO::TraerUno($idUsuario);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $usuarioSeleccionado = $auxReturn->getMensaje();

            // Verificamos si el usuario ya se encuentra activo
            if ($usuarioSeleccionado->getEstado() == EstadosUsuarios::ACTIVO[0]) {
                $auxReturn = new Resultado(false, "El usuario ya se encuentra con estado ACTIVO", EstadosError::ERROR_OPERACION_INVALIDA);
            } else if ($usuarioSeleccionado->getEstado() == EstadosUsuarios::SUSPENDIDO[0]) {
                $auxReturn = UsuarioDAO::CambiarEstado($idUsuario, EstadosUsuarios::ACTIVO[0]);
                if ($auxReturn->getStatus() == EstadosError::OK) {
                    $auxReturn->setMensaje("La operacion se realizo correctamente! Se cambio el estado del usuario a ACTIVO.");
                }
            }
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

}
