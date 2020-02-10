<?php

require_once '../src/app/model/Articulo.php';

class ArticuloDAO extends Articulo
{
/* #region  Métodos */
    public static function TraerTodos()
    {
        $auxReturn = false;
        $rowsArticulos = [];
        $articulos = [];

        try {
            // Definimos el texto de  la query
            $auxQuerySQL = "SELECT idArticulo, descripcion, idArticulo, importe FROM articulos WHERE estado = 1";

            // Pedimos la instancia de acceso a datos
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            // Este metodo nos devuelve una instancia de PDOStatement Object
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Como en este caso no necesitamos ingrear valores externos a la consulta ("bindiar..") la ejecutmos directamente
            // Si devuelve false ocurrio un error al ejecutar la query
            if (!$querySQL->execute()) {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_GENERAL, "Ocurrio un error al intentar traer todos los articulos (Articulo->TraerTodos).");
            } else {
                if ($querySQL->rowCount() > 0) {
                    // Para leer todos los registros que devuelve la consulta
                    $rowsArticulos[] = ($querySQL->fetchAll());
                    // Recorremos cada row que nos devolvio la consulta y se lo asignamos a nuestro objeto
                    foreach ($rowsArticulos[0] as $unaRowArticulo) {
                        $unArticulo = new Articulo();
                        $unArticulo->setIdArticulo($unaRowArticulo["idArticulo"]);
                        $unArticulo->setDescripcion($unaRowArticulo["descripcion"]);
                        $unArticulo->setIdSector($unaRowArticulo["idSector"]);
                        $unArticulo->setImporte($unaRowArticulo["importe"]);
                        $unArticulo->setEstado("1");

                        array_push($articulos, $unArticulo);
                    }

                    $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, $articulos);

                } else {
                    $auxReturn = new ResponseJSON(ResponseJSONEstados::SIN_RESULTADOS, "No hay articulos creados");
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = "Ocurrio un error con la conexion con la base de datos (Articulo->TraerTodos)" . $unErrorDB->getMessage();
        } catch (Exception $unError) {
            $auxReturn = "Ocurrio un error al intentar traer los datos (Articulo->TraerTodos)" . $unError->getMessage();
        }

        return $auxReturn;
    }

    public static function TraerUno($idArticulo)
    {
        $auxReturn;
        $unArticulo = new Articulo();

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT idArticulo, descripcion, idSector, importe, estado FROM articulos WHERE estado = 1 AND idArticulo = :idArticulo";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':idArticulo', $idArticulo, PDO::PARAM_INT);

            $querySQL->execute();
            $row = $querySQL->fetch();

            // Si se encontro uno
            if ($row) {
                $unArticulo->setidArticulo($row["idArticulo"]);
                $unArticulo->setDescripcion($row["descripcion"]);
                $unArticulo->setIdSector($row["idSector"]);
                $unArticulo->setImporte($row["importe"]);
                $unArticulo->setEstado($row["estado"]);
                $auxReturn = new ResponseJSON(ResponseJSONEstados::OK, $unArticulo);

            } else {
                $auxReturn = new ResponseJSON(ResponseJSONEstados::SIN_RESULTADOS, "No existe ningun articulo con ese id");
            }
        } catch (PDOException $unErrorDB) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::ERROR_DB, "Ocurrio un error con la conexion con la base de datos (Articulo->TraerUno)" . $unErrorDB->getMessage());
        } catch (Exception $unError) {
            $auxReturn = new ResponseJSON(ResponseJSONEstados::NO_SE_ENCONTRO_RECURSO, "Ocurrio un error al intentar traer el dato (Articulo->TraerUno)" . $unError->getMessage());
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
            $querySQL->bindValue(":descripcion", $nuevoSector->getDescripcionArticulo());

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

    public static function BorrarUno($idArticulo)
    {
        $auxReturn = false;

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE sectores SET estado = 0 WHERE idArticulo = :idArticulo AND estado = 1";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":idArticulo", $idArticulo);

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

    public static function ModificarUno($idArticulo, $parametros)
    {
        $auxReturn = false;

        try {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE sectores SET descripcion = :descripcion WHERE idArticulo = :idArticulo AND estado = 1";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":descripcion", $parametros["descripcion"]);
            $querySQL->bindValue(":idArticulo", $idArticulo);

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
