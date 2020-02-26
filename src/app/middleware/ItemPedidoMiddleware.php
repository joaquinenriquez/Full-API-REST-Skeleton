<?php

require_once '../src/app/model/Validacion.php';

use Slim\Http\Request;
use Slim\Http\Response;

class ItemPedidoMiddleware
{

    public function VerificarParametrosAltaItemPedido(Request $request, Response $response, $next)
    {

        $auxReturn = false;
        $parametros = $request->getParsedBody();
        $auxReturn = self::VerificarParametrosAltaItemPedidoEstanDefinidos($parametros);
        $nroMesa = $request->getAttribute('routeInfo')[2]['nroMesa'];

        // Validamos que se encuentre definido el nroMesa
        if (isset($nroMesa) == false) {

            $mensaje = "Debe incluir el id de la mesa como atributo de la URI ($ubicacionParaMensaje)";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

            // Validamos que el id sea solo numerico o el codigo amigable
        } else if (Validacion::SoloNumeros($nroMesa) == false && (strlen($nroMesa) > 5)) {

            $mensaje = "El id de la mesa debe ser numerico o bien codigo alfanumerico de hasta 5 caracteres";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else if ($auxReturn->getIsError() == false) {
            $auxReturn = self::ValidarTipoDatosParametros($parametros);
            if ($auxReturn->getIsError() == false) {
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

    public function VerificarParametrosAltaItemPedidoEstanDefinidos($parametros)
    {

        $parametrosSinDefinir = [];
        $auxReturn = true;

        if (empty($parametros["id_articulo"])) {
            array_push($parametrosSinDefinir, "id_articulo");
            $auxReturn = false;
        }

        if (empty($parametros["cantidad"])) {
            array_push($parametrosSinDefinir, "cantidad");
            $auxReturn = false;
        }

        if ($auxReturn == false) {
            $mensaje = "Existen parametros sin definir o vacios:";
            $strParametrosSinDefinir = "";
            foreach ($parametrosSinDefinir as $unParametro) {
                $strParametrosSinDefinir = $strParametrosSinDefinir . " " . $unParametro;
            }

            $mensaje = $mensaje . $strParametrosSinDefinir;
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else {
            $auxReturn = new Resultado(false, "Todos los parametros fueron definidos", EstadosError::OK);
        }

        return $auxReturn;
    }

    public function VerificarParametrosTomarPedido(Request $request, Response $response, $next)
    {
        $ubicacionParaMensaje = "ItemPedidoMiddleware->VerificarParametrosTomarPedido";

        // Para acceder a los atributos de la URI en el middleware hay que hacerlo asi, no me preguntes porque
        $idItemPedido = $request->getAttribute('routeInfo')[2]['idItemPedido'];
        $datosUsuario = $idUsuario = $request->getHeader("datosUsuario");
        $parametros = $request->getParsedBody();

        $auxReturn = new Resultado(false, null, null);

        // Validamos que se encuentre definido el id
        if (isset($idItemPedido) == false) {

            $mensaje = "Debe incluir el id como atributo de la URI ($ubicacionParaMensaje)";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

            // Validamos que el id sea solo numerico
        } else if (Validacion::SoloNumeros($idItemPedido) == false) {

            $mensaje = "El id debe ser numerico";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

            // Validamos los datos del usuario
        } else if (isset($parametros["tiempo_estimado"]) == false) {
            $mensaje = "Falta definir el parametro tiempo_estimado";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
        } else if (Validacion::SoloNumeros($parametros["tiempo_estimado"]) == false) {
            $mensaje = "Tiempo estimado debe ser numerico (minutos)";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
        } else if ($parametros["tiempo_estimado"] <= 0) {
            $mensaje = "El tiempo estimado debe ser mayor a 0";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
        } else if (isset($datosUsuario[0]->id_usuario) == false) {

            $auxReturn = new Resultado(true, "No se pudo validar el usuario ($ubicacionParaMensaje)", EstadosError::ERROR_DE_AUTORIZACION);

        } else if (isset($datosUsuario[0]->id_rol) == false) {

            $auxReturn = new Resultado(true, "No se pudo validar el rol del usuario ($ubicacionParaMensaje)", EstadosError::ERROR_DE_AUTORIZACION);
        }

        if ($auxReturn->getIsError() == false) {
            $response = $next($request, $response);
        } else {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        }

        return $response;

    }

    private function ValidarTipoDatosParametros($parametros)
    {

        $parametrosConErrores = [];
        $auxReturn = true;
        $mensaje = "";

        if (filter_var($parametros["id_articulo"], FILTER_SANITIZE_NUMBER_INT) == false) {
            array_push($parametrosConErrores, "id_articulo (numerico)");
            $auxReturn = false;
        } else {
            if (Validacion::SoloNumeros($parametros["id_articulo"]) == false) {
                array_push($parametrosConErrores, "id_articulo (numerico)");
                $auxReturn = false;
            }
        }

        if (filter_var($parametros["cantidad"], FILTER_SANITIZE_NUMBER_INT) == false) {
            array_push($parametrosConErrores, "cantidad (numerico)");
            $auxReturn = false;
        } else {
            if (Validacion::SoloNumeros($parametros["cantidad"]) == false) {
                array_push($parametrosConErrores, "cantidad (numerico)");
                $auxReturn = false;
            } else if ($parametros["cantidad"] <= 0) {
                array_push($parametrosConErrores, "cantidad (debe ser mayor a cero)");
            }
        }

        if ($auxReturn == false) {
            $mensaje = "Existen parametros invalidos:";
            $strParametrosConErrores = "";
            foreach ($parametrosConErrores as $unParametro) {
                $strParametrosConErrores = $strParametrosConErrores . " " . $unParametro;
            }

            $mensaje = $mensaje . $strParametrosConErrores;
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

        } else {
            $auxReturn = new Resultado(false, "Todos los parametros fueron definidos", EstadosError::OK);
        }

        return $auxReturn;
    }

    public function VerificarParametrosAltaVariosItemPedido(Request $request, Response $response, $next)
    {
        $parametros = $request->getParsedBody();
        $nroMesa = $request->getAttribute('routeInfo')[2]['nroMesa'];
        $auxReturn = new Resultado(false, null, null);

        // Verificamos los datos de la mesa
        $auxReturn = self::VerificarDatosMesa($nroMesa);
        if ($auxReturn->getIsError() == false) {
            if (isset($parametros["articulos"]) == false) {

                $auxReturn = new Resultado(true, "No se definio ningun articulo", EstadosError::ERROR_PARAMETROS_INVALIDOS);

            } else {
                // Verificamos los parametros del articulo
                $articulos = $parametros["articulos"];

                // Verificamos si estan definidos
                foreach ($articulos as $unArticulo) {
                    if (key_exists("id", $unArticulo) == false || key_exists("cantidad", $unArticulo) == false) {
                        $auxReturn = new Resultado(true, "Existen articulos con formato incorrecto de carga (articulo[#][id]=ID articulo[0][cantidad]= CANTIDAD", EstadosError::ERROR_PARAMETROS_INVALIDOS);
                        break;
                    } else if (Validacion::SoloNumeros($unArticulo["id"]) == false || Validacion::SoloNumeros($unArticulo["cantidad"]) == false) {
                        $auxReturn = new Resultado(true, "El id del articulo y la cantidad deben ser numericos", EstadosError::ERROR_PARAMETROS_INVALIDOS);
                        break;
                    } else if ($unArticulo["cantidad"] <= 0) {
                        $auxReturn = new Resultado(true, "La cantidad de los articulos debe ser mayor a cero", EstadosError::ERROR_PARAMETROS_INVALIDOS);
                        break;
                    }
                }
            }

        }

        if ($auxReturn->getIsError() == false) {
            $response = $next($request, $response);
        } else {
            $response->getBody()->write(json_encode($auxReturn));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus($auxReturn->getStatus());
        }

        return $response;
    }

    private function VerificarDatosMesa($nroMesa)
    {
        $auxReturn = new Resultado(false, null, null);
        // Validamos que se encuentre definido el id
        if (isset($nroMesa) == false) {

            $mensaje = "Debe incluir el id de la mesa como atributo de la URI ($ubicacionParaMensaje)";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);

            // Validamos que el id sea solo numerico o el codigo amigable
        } else if (Validacion::SoloNumeros($nroMesa) == false && (strlen($nroMesa) != 5)) {

            $mensaje = "El id de la mesa debe ser numerico o bien codigo alfanumerico de 5 caracteres";
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_PARAMETROS_INVALIDOS);
        }

        return $auxReturn;
    }

}
