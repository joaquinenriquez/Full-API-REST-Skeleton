<?php

require_once '../src/app/model/Resultado.php';
require_once '../src/app/model/Validacion.php';
require_once '../src/app/ModelDAO/UsuarioDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class UsuarioMiddleware
{
    public function VerificarParametrosAltaUsuario(Request $request, Response $response, $next)
    {

        $auxReturn = false;
        $parametros = $request->getParsedBody();
        $auxReturn = self::VerificarParametrosAltaUsuarioEstanDefinidos($parametros);

        if ($auxReturn->getIsError() == false) {
            $auxReturn = self::ValidarTipoDatosParametros($parametros);

            if ($auxReturn == true) {
                $auxReturn = self::VerificarSiExisteUsuario($parametros["nombre_usuario"]);

                if ($auxReturn->getIsError() == false) {
                    if ($auxReturn->getStatus() == EstadosError::SIN_RESULTADOS) {
                        $auxReturn = new Resultado(false, "Todos los datos son correctos", EstadosError::OK);

                    } elseif ($auxReturn->getStatus() == EstadosError::OK) {
                        $auxReturn = new Resultado(true, "Ya existe un usuario con ese nombre de usuario", EstadosError::ERROR_VALOR_REPETIDO);
                    }            
                }
                
            } else {
                $response->withStatus(401);
            }
        } else {
            $response->withStatus(401);
        }

        
        if ($auxReturn->getIsError() == true) {
            $response->getBody()->write(json_encode($auxReturn));
        } else {
            $response = $next($request, $response);
        }

        return $response;
    }

    private function VerificarParametrosAltaUsuarioEstanDefinidos($parametros)
    {
        $parametrosSinDefinir = [];
        $auxReturn = true;

        if (empty($parametros["nombre_usuario"])) {
            array_push($parametrosSinDefinir, "nombre_usuario");
            $auxReturn = false;
        }

        if (empty($parametros["password"])) {
            array_push($parametrosSinDefinir, "password");
            $auxReturn = false;
        }

        if (empty($parametros["nombre"])) {
            array_push($parametrosSinDefinir, "nombre");
            $auxReturn = false;
        }

        if (empty($parametros["apellido"])) {
            array_push($parametrosSinDefinir, "apellido");
            $auxReturn = false;
        }

        if (empty($parametros["id_sector"])) {
            array_push($parametrosSinDefinir, "id_sector");
            $auxReturn = false;
        }

        if ($auxReturn == false) {
            $mensaje = "Existen parametros sin definir o vacios: ";
            $strParametrosSinDefinir = "";
            foreach ($parametrosSinDefinir as $unParametro) {
                $strParametrosSinDefinir = $strParametrosSinDefinir . " " . $unParametro;
            }

            $mensaje = $mensaje . $strParametrosSinDefinir;
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_SIN_DEFINIR);

        } else {
            $auxReturn = new Resultado(false, "Todos los parametros fueron definidos", EstadosError::OK);
        }

        return $auxReturn;

    }

    private function ValidarTipoDatosParametros($parametros)
    {

        $parametrosConErrores = [];
        $auxReturn = true;
        $mensaje = "";

        if (filter_var($parametros["nombre_usuario"], FILTER_SANITIZE_STRING) == false) {
            array_push($parametrosConErrores, "nombre_usuario (debe contener solo letras y numeros)");
            $auxReturn = false;
        } else {
            if (Validacion::SoloLetrasYNumeros($parametros["nombre_usuario"]) == false) {
                array_push($parametrosConErrores, "nombre_usuario (debe contener solo letras y numeros)");
                $auxReturn = false;
            }
        }

        if (filter_var($parametros["apellido"], FILTER_SANITIZE_STRING) == false) {
            array_push($parametrosConErrores, "apellido (debe contener solo letras)");
            $auxReturn = false;
        } else {
            if (Validacion::SoloLetras($parametros["apellido"]) == false) {
                array_push($parametrosConErrores, "apellido (debe contener solo letras)");
                $auxReturn = false;
            }
        }

        if (filter_var($parametros["id_sector"], FILTER_SANITIZE_NUMBER_INT) == false) {
            array_push($parametrosConErrores, "id_sector (numerico)");
            $auxReturn = false;
        } else {
            if (Validacion::SoloNumeros($parametros["id_sector"]) == false) {
                array_push($parametrosConErrores, "id_sector (numerico)");
                $auxReturn = false;
            }
        }


        if ($auxReturn == false) {
            $mensaje = "Existen parametros invalidos: ";
            $strParametrosConErrores = "";
            foreach ($parametrosConErrores as $unParametro) {
                $strParametrosConErrores = $strParametrosConErrores . " " . $unParametro;
            }

            $mensaje = $mensaje . $strParametrosConErrores;
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDO);

        } else {
            $auxReturn = new Resultado(false, "Todos los parametros fueron definidos", EstadosError::OK);
        }

        return $auxReturn;
    }

    private function VerificarSiExisteUsuario(string $nombreUsuario) {
        return UsuarioDAO::VerificarSiExisteUsuario($nombreUsuario);
    }

}
