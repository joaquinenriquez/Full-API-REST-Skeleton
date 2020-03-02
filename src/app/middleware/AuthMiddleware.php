<?php

require_once '../src/app/modelAPI/AutentificadorJWT.php';
require_once '../src/app/modelAPI/TokenSeguridad.php';
require_once '../src/app/enum/Roles.php';
require_once '../src/app/Querys/QuerysSQL_Logs.php';

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
            
                Self::GuardarRegistro($datosUsuario->id_usuario, $datosUsuario->id_rol, "asd");
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

    public static function GuardarRegistro($idUsuario, $idSector, $accion) 
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "AuthMiddleware->GuardarRegistro";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Logs::CargarUna;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            date_default_timezone_set('America/Argentina/Buenos_Aires');
                        
            $querySQL->bindValue(":id_usuario", $idUsuario);
            $querySQL->bindValue(":id_sector", $idSector);
            $querySQL->bindValue(":accion", $accion);
            $querySQL->bindValue(":fecha_hora", date('Y-m-d H:i:s'));

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la consulta ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else if ($querySQL->rowCount() <= 0) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la consulta ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                $mensaje = sprintf("Se guardo correctamente el registro (ID: %s)", $objetoAccesoDatos->RetornarUltimoIdInsertado());
                $auxReturn = new Resultado(false, $mensaje, EstadosError::RECURSO_CREADO);
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)." . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }
    
}
