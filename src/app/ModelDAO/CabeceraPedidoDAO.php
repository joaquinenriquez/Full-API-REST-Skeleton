<?php

require_once '../src/app/model/CabeceraPedido.php';
require_once '../src/app/model/CabeceraPedido.php';
require_once '../src/app/enum/EstadosError.php';
require_once '../src/app/model/Resultado.php';
require_once '../src/app/ModelDAO/CabeceraPedidoDAO.php';
require_once '../src/app/Querys/QuerysSQL_CabecerasPedidos.php';

class CabeceraPedidoDAO extends CabeceraPedido
{
/* #region  Métodos */
    public static function TraerTodos()
    {
        $auxReturn = false;
        $rows = [];
        $comandas = [];
        $ubicacionParaMensaje = "CaberecaComandaDAO->TraerTodos";

        try {
            // Definimos el texto de  la query
            $auxQuerySQL = "SELECT idComanda, nroMesa, estado FROM cabeceraComanda WHERE estado != 0";

            // Pedimos la instancia de acceso a datos
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            // Este metodo nos devuelve una instancia de PDOStatement Object
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Como en este caso no necesitamos ingrear valores externos a la consulta ("bindiar..") la ejecutmos directamente
            // Si devuelve false ocurrio un error al ejecutar la query
            if (!$querySQL->execute()) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "Ocurrio un error al intentar traer todos los articulos ($ubicacionParaMensaje).");
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

                        array_push($comandas, $unaMesa);
                    }

                    $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, $comandas);

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

    public static function CargarUno(CabeceraPedido $nuevoPedido)
    {
        $auxReturn;
        $ubicacionParaMensaje = "CabeceraPedidoDAO->CargarUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            $auxQuerySQL = QuerysSQL_CabecerasPedidos::CargarUno;

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_usuario", $nuevoPedido->getIdUsuario());
            $querySQL->bindValue(":nombre_cliente", $nuevoPedido->getNombreCliente());
            $querySQL->bindValue(":estado", $nuevoPedido->getEstado());
            $querySQL->bindValue(":codigo_amigable", $nuevoPedido->getCodigoAmigable());
            $querySQL->bindValue(":id_mesa", $nuevoPedido->getIdMesa());
            $querySQL->bindValue(":foto", $nuevoPedido->getFoto());
            $querySQL->bindValue(":fecha_inicio", $nuevoPedido->getFechaInicio());
            $querySQL->bindValue(":fecha_fin", $nuevoPedido->getFechaFin());

            if ($querySQL->execute()) {
                $auxReturn = new Resultado(false, "Se guardaron los datos correctamente", EstadosError::RECURSO_CREADO);
            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)", EstadosError::ERROR_GUARDAR);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;
    }

    public static function BorrarUno($idMesa)
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "Mesa->BorrarUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE mesas SET estado = 0 WHERE idMesa = :idMesa AND estado != 0";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":idMesa", $idMesa);

            if (!$querySQL->execute()) {
                $auxReturn = new responseJSON(responseJSONEstados::ERROR_BORRAR, "Ocurrio un error al intentar eliminar ($ubicacionParaMensaje)." . $unError->getMessage());
            } else {
                if ($querySQL->rowCount() > 0) {
                    $auxReturn = new responseJSON(responseJSONEstados::OK, "El sector se elimino correctamente");
                } else {
                    $auxReturn = new responseJSON(responseJSONEstados::SIN_RESULTADOS, "El sector no existe o no se encuentra habilitado");
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new responseJSON(responseJSONEstados::ERROR_DB, "Ocurrio un error al intentar eliminar un sector ($ubicacionParaMensaje)." . $unErrorDB->getMessage());
        } catch (Exception $unError) {
            $auxReturn = new responseJSON(responseJSONEstados::ERROR_BORRAR, "Ocurrio un error al intentar eliminar un sector ($ubicacionParaMensaje)" . $unError->getMessage());
        }

        return $auxReturn;
    }

    public static function ModificarUno($idMesa, $parametros)
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "Articulo->ModificarUno";

        try {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE articulos SET descripcion = :descripcion, idSector = :idSector, importe = :importe  WHERE idArticulo = :idArticulo AND estado = 1";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":idArticulo", $idMesa);
            $querySQL->bindValue(":descripcion", $parametros["descripcion"]);
            $querySQL->bindValue(":idSector", $parametros["idSector"]);
            $querySQL->bindValue(":importe", $parametros["importe"]);

            if (!$querySQL->execute()) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "Ocurrio un error al intentar actualizar el sector (Sector->ModificarUno)");
            } else {
                if ($querySQL->rowCount() > 0) {
                    $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, "Se modifico correctamente el sector!");
                } else {
                    $auxReturn = new ResponseJSON(ResponseJSONEstados::SIN_RESULTADOS, "No existe un sector con ese id");
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error al intentar actualizar el sector (Sector->ModificarUno)");
        } catch (Exception $unError) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "Ocurrio un error al intentar actualizar el sector (Sector->ModificarUno)");
        }

        return $auxReturn;

    }

    public static function TraerPedidoPorMesa($idMesa)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "CabeceraPedidosDAO->TraerPedidoPorMesa";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_CabecerasPedidos::TraerPedidoPorMesa;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":id_mesa", $idMesa);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) 
            {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la consulta. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0) {
                $auxReturn = new Resultado(false, "La mesa ($idMesa) no tiene pedidos en este momento", EstadosError::SIN_RESULTADOS);
            } else 
            {
                $rows = $querySQL->fetch();
                $auxReturn = new Resultado(false, $rows["id_pedido"], EstadosError::OK);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de de datos. ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato. ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;

    }

    public static function CambiarEstado($idPedido, $nuevoEstado)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "CambeceraPedido->CambiarEstado";

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_CabecerasPedidos::ActualizarEstado;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_pedido", $idPedido);
            $querySQL->bindValue(":estado", $nuevoEstado[0]);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0) 
            {
                $auxReturn = new Resultado(true, "No existen una mesa con ese id ($idPedido) o se encuentra deshabilitada", EstadosError::SIN_RESULTADOS);

            } else 
            {
                $mensaje = "Se actualizo correctamente el estado! El estado actual es: " . EstadosMesas::TraerEstadoPorId($nuevoEstado[0]);
                $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

/* #endregion */

}
