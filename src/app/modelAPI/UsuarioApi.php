<?php

require_once "../src/app/ModelDAO/UsuarioDAO.php";
require_once "../src/app/model/Usuario.php";

use Slim\Http\Request;
use Slim\Http\Response;

class UsuarioApi {

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
            $auxReturn = new Resultado(false, $usuarioSeleccionado, EstadosError::OK);
        }

        $response->getBody()->write(json_encode($auxReturn));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($auxReturn->getStatus());

        return $response;

    }

}


?>