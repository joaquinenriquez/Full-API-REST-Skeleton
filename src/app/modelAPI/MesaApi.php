<?php

require_once '../src/app/modelAPI/IApiControler.php';
require_once '../src/app/ModelDAO/MesaDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class MesaAPI
{
    public function TraerTodos(Request $request, Response $response, $args)
    {
        $auxReturn = MesaDAO::TraerTodos();

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function TraerUno(Request $request, Response $response, $args)
    {
        $nroMesa = $request->getAttribute('id');
        $auxReturn = MesaDAO::TraerUno($nroMesa);

        // Formamos la salida
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $unaMesa = new stdClass();
            $unaMesa->nro_mesa = $auxReturn->getMensaje()->getNumeroMesa();
            $unaMesa->estado = EstadosMesas::TraerEstadoPorId($auxReturn->getMensaje()->getEstado());
            $auxReturn = new Resultado(false, $unaMesa, EstadosError::OK);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function CargarUno(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();

        // Verificamos que no exista otra mesa con ese numero
        $auxReturn = MesaDAO::TraerUno($parametros["nro_mesa"]);
        if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
            $auxReturn = MesaDAO::CargarUno($parametros);
        } else if ($auxReturn->getStatus() == EstadosError::OK) {
            $mensaje = "Existe otra mesa con ese nro_mesa";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;
    }

    public function BorrarUno(Request $request, Response $response, $args)
    {
        $nroMesa = $request->getAttribute('nroMesa');
        // Verificamos el estado de la mesa
        $auxReturn = MesaDAO::VerificarEstadoPorNroMesa($nroMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $estadoMesa = $auxReturn->getMensaje();
            // Verificamos si la mesa esta cerrada
            if ($estadoMesa != 1) {
                $mensaje = "No se puede eliminar la mesa, debe estar con estado CERRADA y su estado actual es: " . EstadosMesas::TraerEstadoPorId($estadoMesa);
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);
            } else {
                // Si la mesa esta cerrada entonces le cambiamos el estado a deshabilitada
                $auxReturn = MesaDAO::CambiarEstado($nroMesa, EstadosMesas::DESHABILITADA);
                if ($auxReturn->getStatus = EstadosError::OK) {
                    $auxReturn = new Resultado(false, "Se elimino correctamente la mesa", EstadosError::OK);
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
        $nroMesa = $request->getAttribute('nroMesa');
        $parametros = $request->getParsedBody();
        $idUsuario = $request->getHeader("datosUsuario")[0]->id_usuario;
        $mesaSeleccionada = new Mesa(); // La instancia para poder utilizar el autocompletado del VSC

        // Nos traemos la mesa seleccionada
        $auxReturn = MesaDAO::TraerUno($nroMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $mesaSeleccionada = $auxReturn->getMensaje();

            // Si la mesa tiene un estado distinta a cerrada
            if ($mesaSeleccionada->getEstado() != 1) {
                $mensaje = "No se puede abrir la mesa ya que debe estar con estado CERRADA y su estado actual es: " . EstadosMesas::TraerEstadoPorId($mesaSeleccionada->getEstado());
                $auxReturn = new Resultado(false, $mensaje, EstadosError::ERROR_OPERACION_INVALIDA);

                // Si la mesa se encuentra cerrada, la abrimos y creamos la cabecera de un pedido vacio
            } else if ($mesaSeleccionada->getEstado() == 1) {
                $auxReturn = MesaDAO::CambiarEstado($nroMesa, EstadosMesas::CON_CLIENTES_ELIGIENDO);
                if ($auxReturn->getStatus() == EstadosError::OK) {

                    $nuevoPedido = new CabeceraPedido();
                    $nuevoPedido->setIdMesa($mesaSeleccionada->getIdMesa());
                    $nuevoPedido->setNombreCliente($parametros["nombre_cliente"]);
                    $nuevoPedido->setIdUsuario($idUsuario);

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

}

