<?php

require_once "../src/app/ModelDAO/UsuarioDAO.php";
require_once "../src/app/model/Usuario.php";

use Slim\Http\Request;
use Slim\Http\Response;

class UsuarioApi {

    public static function CargarUno(Request $request, Response $response, $args) {

        $ubicacionParaMensaje = "UsuarioApi->CargarUno";
        $nuevoUsuario = new Usuario();

        $parametros = $request->getParsedBody();
        $nuevoUsuario->setNombreUsuario($parametros["nombre_usuario"]);
        $nuevoUsuario->setPassword($parametros["password"]);
        $nuevoUsuario->setSector($parametros["id_sector"]);
        $nuevoUsuario->setNombre($parametros["nombre"]);
        $nuevoUsuario->setApellido($parametros["apellido"]);
        $nuevoUsuario->setEstado(1);

        $resultado = UsuarioDAO::CargarUno($nuevoUsuario);

        //var_dump($nuevoUsuario);
        return (json_encode($resultado));
        

    }
}


?>