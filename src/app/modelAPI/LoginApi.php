<?php

require_once '../src/app/ModelDAO/UsuarioDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class LoginApi {

    public static function Login(Request $request, Response $response, $args) {

        $ubicacionParaMensaje = "LoginApi->Login";
        $parametros = $request->getParsedBody();
        $auxReturn = false;

        $nombreUsuario = $parametros["nombre_usuario"];
        $password = $parametros["password"];

        

        $auxReturn = UsuarioDAO::Login($nombreUsuario, $password);
        
        return json_encode($auxReturn);

    }   

}


?>