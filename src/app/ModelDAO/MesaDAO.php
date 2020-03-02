<?php

require_once '../src/app/model/Mesa.php';
require_once '../src/app/modelDAO/UsuarioDAO.php';
require_once '../src/app/api/responseJSON.php';
require_once '../src/app/enum/EstadosMesas.php';
require_once '../src/app/modelDAO/CabeceraPedidoDAO.php';
require_once '../src/app/Querys/QuerysSQL_Mesas.php';
require_once '../src/app/api/Funciones.php';

class MesaDAO extends Mesa
{
/* #region  Métodos */
    public static function TraerTodos()
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "Mesas->TraerTodos";
        $listadoMesas = [];

        try {
            // Definimos el texto de  la query
            $auxQuerySQL = QuerysSQL_Mesas::TraerTodas;

            // Pedimos la instancia de acceso a datos
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            // Este metodo nos devuelve una instancia de PDOStatement Object
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Como en este caso no necesitamos ingrear valores externos a la consulta ("bindiar..") la ejecutmos directamente
            // Si devuelve false ocurrio un error al ejecutar la query
            $estadoQuery = $querySQL->execute();
            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos. ($ubicacionParaMensaje).", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0) {
                $auxReturn = new Resultado(false, "No hay mesas creadas", EstadosError::SIN_RESULTADOS);
            } else {
                // Para leer todos los registros que devuelve la consulta
                $rows[] = ($querySQL->fetchAll());
                foreach ($rows[0] as $unaRow) {
                    $unaMesa = new Mesa();

                    $unaMesa->setIdMesa($unaRow["id_mesa"]);
                    $unaMesa->setCodigoAmigable($unaRow["codigo_amigable"]);
                    $unaMesa->setEstado($unaRow["estado"]);

                    array_push($listadoMesas, $unaMesa);
                }

                $auxReturn = new Resultado(false, $listadoMesas, EstadosError::OK);

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

    public static function TraerUno($identificadorMesa)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Mesa->TraerUnoPorId";

        if (Validacion::SoloNumeros($identificadorMesa) == true )
        {
            $auxQuerySQL = QuerysSQL_Mesas::TraerUnaPorId;
        } 
        else 
        {
            $auxQuerySQL = QuerysSQL_Mesas::TraerUnaPorCodigoAmigable;
        }

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':identificador_mesa', $identificadorMesa, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la consulta. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0) {
                $auxReturn = new Resultado(false, "No existe ninguna mesa con esa identificacion ($identificadorMesa)", EstadosError::SIN_RESULTADOS);
            } else {
                $row = $querySQL->fetch();
                $unaMesa = new Mesa();

                $unaMesa->setIdMesa($row["id_mesa"]);
                $unaMesa->setCodigoAmigable($row["codigo_amigable"]);
                $unaMesa->setEstado($row["estado"]);

                $auxReturn = new Resultado(false, $unaMesa, EstadosError::OK);
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

    public static function CargarUno()
    {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Mesa->CargarUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Mesas::CargarUna;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $codigoAmigable = GenerarCodigoAmigable();
            $querySQL->bindValue(":codigo_amigable", $codigoAmigable);
            $querySQL->bindValue(":estado", EstadosMesas::CERRADA[0]);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la consulta ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else if ($querySQL->rowCount() <= 0) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la consulta ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                $mensaje = sprintf("Se creo correctamente la mesa! El codigo amigable es: %s (ID: %s)", $codigoAmigable, $objetoAccesoDatos->RetornarUltimoIdInsertado());
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

    public static function CambiarEstado($idMesa, $nuevoEstado)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->CambiarEstado";

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Mesas::ActualizarEstado;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_mesa", $idMesa);
            $querySQL->bindValue(":estado", $nuevoEstado[0]);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0) 
            {
                $auxReturn = new Resultado(true, "No existen una mesa con ese id ($idMesa) o se encuentra deshabilitada", EstadosError::SIN_RESULTADOS);

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
