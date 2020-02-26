<?php

require_once '../src/app/model/Mesa.php';
require_once '../src/app/modelDAO/UsuarioDAO.php';
require_once '../src/app/api/responseJSON.php';
require_once '../src/app/enum/EstadosMesas.php';
require_once '../src/app/modelDAO/CabeceraPedidoDAO.php';

class MesaDAO extends Mesa
{
/* #region  Métodos */
    public static function TraerTodos()
    {
        $auxReturn = false;
        $rows = [];
        $mesas = [];
        $ubicacionParaMensaje = "Mesas->TraerTodos";

        try {
            // Definimos el texto de  la query
            $auxQuerySQL = "SELECT id_mesa, nro_mesa, estado FROM mesas WHERE estado != 0";

            // Pedimos la instancia de acceso a datos
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            // Este metodo nos devuelve una instancia de PDOStatement Object
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Como en este caso no necesitamos ingrear valores externos a la consulta ("bindiar..") la ejecutmos directamente
            // Si devuelve false ocurrio un error al ejecutar la query
            $estadoQuery = $querySQL->execute();
            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos. ($ubicacionParaMensaje).", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    // Para leer todos los registros que devuelve la consulta
                    $rows[] = ($querySQL->fetchAll());
                    // Recorremos cada row que nos devolvio la consulta y se lo asignamos a nuestro objeto
                    foreach ($rows[0] as $unaRow) {
                        $unaMesa = new stdClass();
                        $unaMesa->nro_mesa = $unaRow["nro_mesa"];

                        $estadoMesa = EstadosMesas::TraerEstadoPorId($unaRow["estado"]);
                        $unaMesa->estado = $estadoMesa;

                        array_push($mesas, $unaMesa);
                    }

                    $auxReturn = new Resultado(false, $mesas, EstadosError::OK);

                } else {
                    $auxReturn = new Resultado(false, "No hay mesas creadas", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {

            $mensaje = "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);

        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)" . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerUno($nroMesa)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Mesa->TraerUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_mesa, nro_mesa, estado FROM mesas WHERE estado != 0 AND nro_mesa = :nro_mesa LIMIT 1";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':nro_mesa', $nroMesa, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) 
            {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la consulta. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else 
            {
                if ($querySQL->rowCount() > 0) 
                {
                    $row = $querySQL->fetch();
                    $unaMesa = new Mesa();

                    $unaMesa->setIdMesa($row["id_mesa"]);
                    $unaMesa->setNumeroMesa($row["nro_mesa"]);
                    $unaMesa->setEstado($row["estado"]);

                    $auxReturn = new Resultado(false, $unaMesa, EstadosError::OK);
                } else 
                {
                    $auxReturn = new Resultado(false, "No existe ninguna mesa con ese numero", EstadosError::SIN_RESULTADOS);
                }
                
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function CargarUno($parametros)
    {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Mesa->CargarUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "INSERT INTO mesas (nro_mesa, estado) VALUES (:nro_mesa, 1)";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":nro_mesa", $parametros["nro_mesa"]);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la consulta ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                $auxReturn = new Resultado(false, "Se creo correctamente la mesa!", EstadosError::RECURSO_CREADO);
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

    public static function ModificarUno($idMesa, $parametros)
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "Mesa->ModificarUno";

        try {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE mesas SET nroMesa = :nroMesa WHERE idMesa = :idMesa AND estado = 1";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":idMesa", $idMesa);
            $querySQL->bindValue(":nroMesa", $parametros["nroMesa"]);

            if (!$querySQL->execute()) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "Ocurrio un error al intentar actualizar los datos. ($ubicacionParaMensaje)");
            } else {
                if ($querySQL->rowCount() > 0) {
                    $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, "Se modifico correctamente!");
                } else {
                    $auxReturn = new ResponseJSON(ResponseJSONEstados::SIN_RESULTADOS, "No existen datos con ese id");
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje)");
        } catch (Exception $unError) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje)");
        }

        return $auxReturn;

    }

    public static function VerificarEstado($idMesa)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->VerificarEstado";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT estado FROM mesas WHERE estado != 0 AND id_mesa = :id_mesa";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':id_mesa', $idMesa, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                if ($querySQL->rowCount() > 0) {
                    $rows = $querySQL->fetch();
                    $auxReturn = new Resultado(false, $rows["estado"], EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "La mesa no existe o se encuentra deshabilitada", EstadosError::SIN_RESULTADOS);
                }
            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la query. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;

    }

    public static function VerificarEstadoPorNroMesa($nroMesa)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->VerificarEstado";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT estado FROM mesas WHERE estado != 0 AND nro_mesa = :nro_mesa";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':nro_mesa', $nroMesa, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                if ($querySQL->rowCount() > 0) {
                    $rows = $querySQL->fetch();
                    $auxReturn = new Resultado(false, $rows["estado"], EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "La mesa no existe o se encuentra deshabilitada", EstadosError::SIN_RESULTADOS);
                }
            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la query. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;

    }

    public static function CambiarEstado($nroMesa, $estado)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->CambiarEstado";

        try {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE mesas SET estado = :estado WHERE nro_mesa = :nro_mesa AND estado != 0";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":nro_mesa", $nroMesa);
            $querySQL->bindValue(":estado", $estado[0]);

            if (!$querySQL->execute()) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_GENERAL);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $auxReturn = new Resultado(false, "Se actualizo correctamente el estado!", EstadosError::OK);
                } else {
                    $auxReturn = new Resultados(true, "No existen datos con ese id", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje)", EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;
    }



/* #endregion */

}
