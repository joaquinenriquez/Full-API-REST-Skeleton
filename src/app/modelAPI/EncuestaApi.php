<?php

use Slim\Http\Request;
use Slim\Http\Response;

class EncuestaApi 
{
    public static function CargarUno(Request $request, Response $response)
    {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Encuesta->CargarUno";
        $identificacionMesa = $request->getAttribute('identificadorMesa');
        $parametros = $request->getParsedBody();

        $calificacionMesa = $parametros["calificacion_mesa"];

        $calificacionRestaurante = $parametros["calificacion_restaurante"];
        $calificacionMozo = $parametros["calificacion_mozo"];
        $calificacionCocinero = $parametros["calificacion_cocinero"];
        $calificacionMesa = $parametros["calificacion_mesa"];

        $comentarios = $parametros["comentarios"];

        // Verificamos el estado de la mesa
        $auxReturn = MesaDAO::TraerUno($identificacionMesa);
        if ($auxReturn->getStatus() == EstadosError::OK) {
            $mesaSeleccionada = $auxReturn->getMensaje();

            // Verificamos se encuentre cerrada
            if ($mesaSeleccionada->getEstado() != EstadosMesas::CERRADA[0]) {
                $auxReturn = new Resultado(true, "La mesa $identificacionMesa no se encuentra cerrada. Primero cierre la mesa e intente nuevamente.", EstadosError::ERROR_OPERACION_INVALIDA);
            } else
            {
                $unaEncuesta = new Encuesta();
                $unaEncuesta->setCalificacion_restaurante($calificacionRestaurante);
                $unaEncuesta->setCalificacion_mozo($calificacionMozo);
                $unaEncuesta->setCalificacion_cocinero($calificacionCocinero);
                $unaEncuesta->setCalificacion_mozo($calificacionMesa);
                $unaEncuesta->setComentarios($comentarios);

                EncuestaDAO::CargarUno($unaEncuesta);

            }
        
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

}

?>