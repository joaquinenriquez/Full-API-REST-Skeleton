<?php

require_once '../src/app/modelAPI/AutentificadorJWT.php';
require_once '../src/app/modelAPI/TokenSeguridad.php';
require_once '../src/app/enum/Roles.php';
require_once '../src/app/Querys/QuerysSQL_Logs.php';
require_once '../src/app/ModelDAO/LogDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware
{
    public function VerificarToken(Request $request, Response $response, $next)
    {
        $auxReturn = new Resultado(false, null, null);

        // Verificamos que el header de authorization este definido
        if (isset($request->getHeader("Authorization")[0])) {

            $token = $request->getHeader("Authorization")[0];
            $auxReturn = TokenSeguridad::VerificarToken($token);

            // Si no hay error entonces definimos en el header los datos del usuario y 
            // seteamos el response para que continue al proximo middleware
            if ($auxReturn->getIsError() == false) {
                $datosUsuario = $auxReturn->getMensaje();
                $request = $request->withAddedHeader("datosUsuario", $datosUsuario);
                $response = $next($request, $response);

                $verbo = $request->getAttribute('routeInfo')['request'][0];
                $ruta =  $request->getAttribute('routeInfo')['request'][1];
                $accion = $verbo . " " . $ruta;
            
                LogDAO::GuardarRegistro($datosUsuario->id_usuario, $datosUsuario->id_rol, $accion);
            }
            
        } else {
            $mensaje = "No esta definido el header 'Authorization'";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DE_AUTORIZACION);
        }

        if ($auxReturn->getIsError()) {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        }

        return $response;
    }

    public static function VerificarSiPuedeCrearPedidos(Request $request, Response $response, $next) {
        
        $auxReturn = new Resultado(false, null, 200);

        // Verificamos si podemos acceder al header del rol (lo crea el metodo verificar token)
        if (!isset($request->getHeader("datosUsuario")[0]->id_rol)) {

            $mensaje = "No se pudo validar el usuario actual";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DE_AUTORIZACION);


        } else {

            // Verificamos si el rol es de mozo o de socio
            $rolUsuarioActual = $request->getHeader("datosUsuario")[0]->id_rol;
            if ($rolUsuarioActual != Roles::MOZO[0] && $rolUsuarioActual != Roles::SOCIO[0]) {
                $mensaje = "Para poder agregar un pedido es necesario contar con el rol de MOZO, su rol actual es: " . strtoupper(Roles::TraerRolPorId($rolUsuarioActual)); 
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_SIN_PERMISOS);
            } else {
                $response = $next($request, $response);
            }
        }

        if ($auxReturn->getIsError() == true) {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        }

        return $response;

    }

    // public static function VerificarSiPuedeTomarPedidos(Request $request, Response $response, $next) {
        
    //     $auxReturn = new Resultado(false, null, 200);

    //     // Verificamos si podemos acceder al header del rol (lo crea el metodo verificar token)
    //     if (!isset($request->getHeader("datosUsuario")[0]->id_rol)) {

    //         $mensaje = "No se pudo validar el usuario actual";
    //         $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DE_AUTORIZACION);


    //     } else {

    //         // Verificamos si el rol es de mozo o de socio
    //         $rolUsuarioActual = $request->getHeader("datosUsuario")[0]->id_rol;
    //         if ($rolUsuarioActual != Roles::COCINERO[0] && $rolUsuarioActual != Roles::SOCIO[0]) {
    //             $mensaje = "Para poder agregar un pedido es necesario contar con el rol de MOZO, su rol actual es: " . strtoupper(Roles::TraerRolPorId($rolUsuarioActual)); 
    //             $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_SIN_PERMISOS);
    //         } else {
    //             $response = $next($request, $response);
    //         }
    //     }

    //     if ($auxReturn->getIsError() == true) {
    //         $response->getBody()->write(json_encode($auxReturn));
    //         $response = $response->withHeader('Content-Type', 'application/json');
    //         $response = $response->withStatus($auxReturn->getStatus());
    //     }

    //     return $response;

    // }

    public static function VerificarSiEsAdmin(Request $request, Response $response, $next) 
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);

        // Verificamos si podemos acceder al header del rol (lo crea el metodo verificar token)
        if (!isset($request->getHeader("datosUsuario")[0]->id_rol)) {

            $mensaje = "No se pudo validar el usuario actual";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DE_AUTORIZACION);


        } else {

            // Verificamos si el rol es de socio
            $rolUsuarioActual = $request->getHeader("datosUsuario")[0]->id_rol;
            if ($rolUsuarioActual != Roles::SOCIO[0]) {
                $mensaje = "Para poder realizar la accion que intenta debe contar con el rol de SOCIO. Su rol actual es: " . strtoupper(Roles::TraerRolPorId($rolUsuarioActual)); 
                $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_SIN_PERMISOS);
            } else {
                $response = $next($request, $response);
            }
        }

        if ($auxReturn->getIsError() == true) {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        }

        return $response;

    }

}
