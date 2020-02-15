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
            $auxQuerySQL = "SELECT idMesa, nroMesa, estado FROM mesas WHERE estado != 0";

            // Pedimos la instancia de acceso a datos
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            // Este metodo nos devuelve una instancia de PDOStatement Object
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Como en este caso no necesitamos ingrear valores externos a la consulta ("bindiar..") la ejecutmos directamente
            // Si devuelve false ocurrio un error al ejecutar la query
            if (!$querySQL->execute()) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "Ocurrio un error al intentar traer los datos. ($ubicacionParaMensaje).");
            } else {
                if ($querySQL->rowCount() > 0) {
                    // Para leer todos los registros que devuelve la consulta
                    $rows[] = ($querySQL->fetchAll());
                    // Recorremos cada row que nos devolvio la consulta y se lo asignamos a nuestro objeto
                    foreach ($rows[0] as $unaRow) {
                        $unaMesa = new Mesa();
                        $unaMesa->setIdMesa($unaRow["idMesa"]);
                        $unaMesa->setNumeroMesa($unaRow["nroMesa"]);
                        $unaMesa->setEstado($unaRow["estado"]);

                        array_push($mesas, $unaMesa);
                    }

                    $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, $mesas);

                } else {
                    $auxReturn = new ResponseJSON(ResponseJSONEstados::SIN_RESULTADOS, "No hay mesas creadas");
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage();
        } catch (Exception $unError) {
            $auxReturn = "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)" . $unError->getMessage();
        }

        return $auxReturn;
    }

    public static function TraerUno($idMesa)
    {
        $auxReturn;
        $unaMesa = new Mesa();
        $ubicacionParaMensaje = "Mesa->TraerUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT idMesa, nroMesa, estado FROM mesas WHERE estado != 0 AND idMesa = :idMesa";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);

            $querySQL->execute();
            $row = $querySQL->fetch();

            // Si se encontro uno
            if ($row) {
                $unaMesa->setIdMesa($row["idMesa"]);
                $unaMesa->setNumeroMesa($row["nroMesa"]);
                $unaMesa->setEstado($row["estado"]);

                $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, $unaMesa);

            } else {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::SIN_RESULTADOS, "No existe ninguna mesa con ese id");
            }
        } catch (PDOException $unErrorDB) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage());
        } catch (Exception $unError) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::NO_SE_ENCONTRO_RECURSO, "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage());
        }

        return $auxReturn;
    }

    public static function CargarUno($parametros)
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "Mesa->CargarUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "INSERT INTO mesas (nroMesa, estado) VALUES (:nroMesa, 1)";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":nroMesa", $parametros["nroMesa"]);

            if ($querySQL->execute()) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, "Se guardado correctamente");
            } else {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "No se pudo guardar");
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage());
        } catch (Exception $unError) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)." . $unError->getMessage());
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

    public static function AbrirMesa($idMesa, $parametros)
    {

        $auxReturn;
        $ubicacionParaMensaje = "MesaDAO->AbrirMesa";

        // Verificamos si la mesa de encuentra cerrada
        $estadoMesa = MesaDAO::VerificarEstado($idMesa);

        if (MesaDAO::VerificarEstado($idMesa) != 1) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, "No se puede abrir la mesa ya que actualmente su estado es: " . EstadosMesas::TraerEstadoPorId($estadoMesa));
        } else {
            $nuevoPedido = new CabeceraPedido();
            $nuevoPedido->setIdMesa($idMesa);
            $nuevoPedido->setNombreCliente($parametros["nombreCliente"]);
            $nuevoPedido->setFoto($parametros["foto"]);

            $auxReturn = CabeceraPedidoDAO::CargarUno($nuevoPedido);

            if ($auxReturn->getIsError() == false) {
                $auxReturn = MesaDAO::CambiarEstado($idMesa, EstadosMesas::CON_CLIENTES_ELIGIENDO);
            }if ($auxReturn->getIsError() == false) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, "La mesa se abrio correctamente. El codigo de mesa para el clientes es: " . $nuevoPedido->getCodigoAmigable());
            }

        }

        return $auxReturn;

    }

    public static function VerificarEstado($idMesa)
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "Mesa->VerificarEstado";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT estado FROM mesas WHERE estado != 0 AND idMesa = :idMesa";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                if ($querySQL->rowCount() > 0) {
                    $rows = $querySQL->fetch();
                    $auxReturn = new Resultado(false, $rows["estado"], EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "La mesa no existe o se encuentra deshabilitada", EstadosError::SIN_RESULTADOS);
                }
            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la query. ($ubicacionParaMensaje)", EstadosError::ERROR_GENERAL);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;

    }

    public static function CambiarEstado(int $idMesa, $estado)
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "MesaDAO->CambiarEstado";

        try {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE mesas SET estado = :estado WHERE idMesa = :idMesa AND estado != 0";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":idMesa", $idMesa);
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
