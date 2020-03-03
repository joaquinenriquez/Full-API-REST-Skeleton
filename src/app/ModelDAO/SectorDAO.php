<?php

class SectorDAO
{
    /* #region  Métodos */
    public static function TraerTodos()
    {
        $ubicacionParaMensaje = "Sector->TraerTodos";
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $rowsSectores = [];
        $sectores = [];

        try {
            // Definimos el texto de  la query
            $auxQuerySQL = "SELECT id_sector, descripcion FROM sectores WHERE estado != 0";

            // Pedimos la instancia de acceso a datos
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            // Este metodo nos devuelve una instancia de PDOStatement Object
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Como en este caso no necesitamos ingrear valores externos a la consulta ("bindiar..") la ejecutmos directamente
            // Si devuelve false ocurrio un error al ejecutar la query
            $estadoQuery = $querySQL->execute();
            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer todos los sectores ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    // Para leer todos los registros que devuelve la consulta
                    $rowsSectores[] = ($querySQL->fetchAll());
                    // Recorremos cada row que nos devolvio la consulta y se lo asignamos a nuestro objeto
                    foreach ($rowsSectores[0] as $unaRowSector) {
                        $unSector = new Sector();
                        $unSector->setIdSector($unaRowSector["id_sector"]);
                        $unSector->setDescripcionSector($unaRowSector["descripcion"]);
                        array_push($sectores, $unSector);
                    }

                    $auxReturn = new Resultado(false, $sectores, EstadosError::OK);

                } else {
                    $auxReturn = new Resultado(false, "No hay sectores creados", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos (Sector->TraerTodos)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos (Sector->TraerTodos)" . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerUno($idSector)
    {
        $ubicacionParaMensaje = "SectorDAO->TraerUno";
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $unSector = new Sector();

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_sector, descripcion FROM sectores WHERE estado = 1 AND id_sector = :id_sector LIMIT 1";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':id_sector', $idSector, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();
            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar realizar la consulta ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $row = $querySQL->fetch();
                    $unSector->setIdSector($row["id_sector"]);
                    $unSector->setDescripcionSector($row["descripcion"]);
                    $auxReturn = new Resultado(false, $unSector, EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "No existe ningun sector con ese id", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos (Sector->TraerUno)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato (Sector->TraerUno)" . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function CargarUno(Sector $nuevoSector)
    {
        $auxReturn = false;

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "INSERT INTO sectores (descripcion, estado) VALUES (:descripcion, 1)";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":descripcion", $nuevoSector->getDescripcionSector());

            if ($querySQL->execute()) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, "Sector guardado correctamente");
            } else {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "No se pudo guardar el sector");
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error con la conexion con la base de datos (Sector->CargarUno)." . $unErrorDB->getMessage());
        } catch (Exception $unError) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error al intentar guardar un nuevo sector (Sector->CargarUno)." . $unError->getMessage());
        }

        return $auxReturn;
    }

    public static function BorrarUno($idSector)
    {
        $auxReturn = false;

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE sectores SET estado = 0 WHERE idSector = :idSector AND estado = 1";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":idSector", $idSector);

            if (!$querySQL->execute()) {
                $auxReturn = new responseJSON(responseJSONEstados::ERROR_BORRAR, "Ocurrio un error al intentar eliminar un sector (Sector->BorrarUno)");
            } else {
                if ($querySQL->rowCount() > 0) {
                    $auxReturn = new responseJSON(responseJSONEstados::OK, "El sector se elimino correctamente");
                } else {
                    $auxReturn = new responseJSON(responseJSONEstados::SIN_RESULTADOS, "El sector no existe o no se encuentra habilitado");
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new responseJSON(responseJSONEstados::ERROR_DB, "Ocurrio un error al intentar eliminar un sector (Sector->BorrarUno)." . $unErrorDB->getMessage());
        } catch (Exception $unError) {
            $auxReturn = new responseJSON(responseJSONEstados::ERROR_BORRAR, "Ocurrio un error al intentar eliminar un sector (Sector->BorrarUno)" . $unError->getMessage());
        }

        return $auxReturn;
    }

    public static function ModificarUno($idSector, $parametros)
    {
        $ubicacionParaMensaje = "SectorDAO->ModificarUno";
        $auxReturn = new Resultado(false, null, EstadosError::OK);

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE sectores SET descripcion = :descripcion WHERE id_sector = :id_sector AND estado = 1";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":descripcion", $parametros["descripcion"]);
            $querySQL->bindValue(":id_sector", $idSector);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar realizar la query ($ubicacionParaMensaje).", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $auxReturn = new Resultado(false, "Se modifico correctamente el sector!", EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "No se encontro ningun sector con ese ID", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar el sector ($ubicacionParaMensaje).", EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar el sector ($ubicacionParaMensaje).", EstadosError::ERROR_DB);
        }

        return $auxReturn;

    }

    public static function VerificarSiExisteSectorPorDescripcion($descripcionSector)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "SectorDAO->VerificarSiExisteSectorPorDescripcion";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_sector FROM sectores WHERE estado != 0 AND descripcion = :descripcion";

            $querySQLPreparada = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQLPreparada->bindValue(':descripcion', $descripcionSector, PDO::PARAM_STR);

            $estadoQuery = $querySQLPreparada->execute();

            if ($estadoQuery == true) {

                if ($querySQLPreparada->rowCount() > 0) {
                    $rows = $querySQLPreparada->fetch();
                    $auxReturn = new Resultado(false, $rows["id_sector"], EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "No existe un sector con esa descripcion", EstadosError::SIN_RESULTADOS);
                }
            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la query. ($ubicacionParaMensaje)", EstadosError::ERROR_GENERAL);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;

    }

    /* #endregion */
}
