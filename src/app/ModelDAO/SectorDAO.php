<?php

require_once '../src/app/model/sector.php';
require_once '../src/app/modelPDO/AccesoDatos.php';
require_once '../src/app/api/responseJSON.php';
require_once '../src/app/enum/responseJSONEstados.php';
require_once '../src/app/api/responseJSON.php';
require_once '../src/app/enum/responseJSONEstados.php';

class SectorDAO extends Sector
{
    /* #region  Métodos */
    public static function TraerTodos()
    {
        $auxReturn = false;
        $rowsSectores = [];
        $sectores = [];

        try {
            // Definimos el texto de  la query
            $auxQuerySQL = "SELECT idSector, descripcion FROM sectores WHERE estado = 1";

            // Pedimos la instancia de acceso a datos
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            // Este metodo nos devuelve una instancia de PDOStatement Object
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Como en este caso no necesitamos ingrear valores externos a la consulta ("bindiar..") la ejecutmos directamente
            // Si devuelve false ocurrio un error al ejecutar la query
            if (!$querySQL->execute()) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "Ocurrio un error al intentar traer todos los sectores (Sector->TraerTodos).");
            } else {
                if ($querySQL->rowCount() > 0) {
                    // Para leer todos los registros que devuelve la consulta
                    $rowsSectores[] = ($querySQL->fetchAll());
                    // Recorremos cada row que nos devolvio la consulta y se lo asignamos a nuestro objeto
                    foreach ($rowsSectores[0] as $unaRowSector) {
                        $unSector = new Sector();
                        $unSector->setIdSector($unaRowSector["idSector"]);
                        $unSector->setDescripcionSector($unaRowSector["descripcion"]);
                        array_push($sectores, $unSector);
                    }

                    $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, $sectores);

                } else {
                    $auxReturn = new ResponseJSON(ResponseJSONEstados::SIN_RESULTADOS, "No hay sectores creados");
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = "Ocurrio un error con la conexion con la base de datos (Sector->TraerTodos)" . $unErrorDB->getMessage();
        } catch (Exception $unError) {
            $auxReturn = "Ocurrio un error al intentar traer los datos (Sector->TraerTodos)" . $unError->getMessage();
        }

        return $auxReturn;
    }

    public static function TraerUno($idSector)
    {
        $auxReturn;
        $unSector = new Sector();

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT idSector, descripcion FROM sectores WHERE estado = 1 AND idSector = :idSector";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':idSector', $idSector, PDO::PARAM_INT);

            $querySQL->execute();
            $rowSector = $querySQL->fetch();

            // Si se encontro uno
            if ($rowSector) {
                $unSector->setIdSector($rowSector["idSector"]);
                $unSector->setDescripcionSector($rowSector["descripcion"]);
                $auxReturn = $unSector;
            } else {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::SIN_RESULTADOS, "No existe ningun sector con ese id");
            }
        } catch (PDOException $unErrorDB) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error con la conexion con la base de datos (Sector->TraerUno)" . $unErrorDB->getMessage());
        } catch (Exception $unError) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::NO_SE_ENCONTRO_RECURSO, "Ocurrio un error al intentar traer el dato (Sector->TraerUno)" . $unError->getMessage());
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
                $auxReturn = new responseJSON(responseJSONEstados::ERROR_BORRAR, "Ocurrio un error al intentar eliminar un sector (Sector->BorrarUno)" . $unError->getMessage());
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
        $auxReturn = false;

        try {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE sectores SET descripcion = :descripcion WHERE idSector = :idSector AND estado = 1";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":descripcion", $parametros["descripcion"]);
            $querySQL->bindValue(":idSector", $idSector);

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

    /* #endregion */
}
