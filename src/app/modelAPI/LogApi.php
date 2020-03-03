<?php

require_once '../src/app/ModelDAO/LogDAO.php';
require_once '../src/app/api/Funciones.php';

use Slim\Http\Request;
use Slim\Http\Response;

class LogApi 
{
    public static function TraerIniciosDeSesion(Request $request, Response $response)
    {
        $parametros = $request->getParsedBody();
        $fechaHoraDesde = FormatearFechaParaWhere($parametros["desde"]);
        $fechaHoraHasta = FormatearFechaParaWhere($parametros["hasta"]);

        $auxReturn = LogDAO::TraerIniciosDeSesion($fechaHoraDesde, $fechaHoraHasta);

        if ($auxReturn->getStatus() == EstadosError::OK)
        {
            $listadoRegistrosLogs = $auxReturn->getMensaje();
            $listadoRegistrosLogsFormateados = [];

            foreach ($listadoRegistrosLogs as $unLogRegistro) {
                $unLogRegistroFormateado = self::FormatearLogRegistro($unLogRegistro, EstadosItemPedido::PENDIENTE);
                array_push($listadoRegistrosLogsFormateados, $unLogRegistroFormateado);
            }
            $auxReturn->setMensaje($listadoRegistrosLogsFormateados);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());
        
        return $response;

    }

    public static function FormatearLogRegistro(Log $unLogRegistro)
    {
        $unLogRegistroFormateado = new stdClass();
        $unLogRegistroFormateado->id_log = $unLogRegistro->getIdLog();
        $unLogRegistroFormateado->fecha = $unLogRegistro->getFecha();
        $unLogRegistroFormateado->hora = $unLogRegistro->getHora();
        $unLogRegistroFormateado->nombre_usuario = $unLogRegistro->getNombreUsuario();
        $unLogRegistroFormateado->rol = $unLogRegistro->getDescripcionRol();
        $unLogRegistroFormateado->accion = $unLogRegistro->getDescripcionAccion();

        return $unLogRegistroFormateado;
    }

}

?>