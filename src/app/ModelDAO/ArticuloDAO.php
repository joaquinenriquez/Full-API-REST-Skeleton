<?php

require_once '../src/app/model/Articulo.php';

class ArticuloDAO extends Articulo
{
/* #region  Métodos */
    public static function TraerTodos()
    {
        $ubicacionParaMensaje = "Articulo->TraerTodos";
        $auxReturn = false;
        $rowsArticulos = [];
        $articulos = [];

        try {
            // Definimos el texto de  la query
            $auxQuerySQL = "SELECT  id_articulo, 
                                    articulos.descripcion as articulo_descripcion, 
                                    articulos.id_sector as articulo_id_sector, 
                                    sectores.descripcion as sector_descripcion, 
                                    importe 
                            FROM articulos 
                            LEFT JOIN sectores ON sectores.id_sector = articulos.id_sector
                            WHERE articulos.estado != 0
                            ORDER BY articulos.id_sector, articulos.id_articulo";

            // Pedimos la instancia de acceso a datos
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            // Este metodo nos devuelve una instancia de PDOStatement Object
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Como en este caso no necesitamos ingrear valores externos a la consulta ("bindiar..") la ejecutmos directamente
            // Si devuelve false ocurrio un error al ejecutar la query
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer todos los articulos.", EstadosError::ERROR_GENERAL);
            } else {
                if ($querySQL->rowCount() > 0) {
                    // Para leer todos los registros que devuelve la consulta
                    $rowsArticulos[] = ($querySQL->fetchAll());
                    // Recorremos cada row que nos devolvio la consulta y se lo asignamos a nuestro objeto
                    foreach ($rowsArticulos[0] as $unaRowArticulo) {
                        $unArticulo = new stdClass;
                        $unArticulo->id_articulo = $unaRowArticulo["id_articulo"];
                        $unArticulo->descripcion = $unaRowArticulo["articulo_descripcion"];
                        $unArticulo->importe = '$' . $unaRowArticulo["importe"];
                        $unArticulo->sector = $unaRowArticulo["sector_descripcion"];

                        array_push($articulos, $unArticulo);
                    }

                    $auxReturn = new Resultado(false, $articulos, EstadosError::OK);

                } else {
                    $auxReturn = new Resultado(false, "No hay articulos creados", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resutlado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;
    }

    public static function TraerUno($idArticulo)
    {
        $ubicacionParaMensaje = "ArticuloDAO->TraerUno";
        $auxReturn = false;
        $unArticulo = new Articulo();
        $rows;

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_articulo, descripcion, id_sector, importe, estado FROM articulos WHERE estado = 1 AND id_articulo = :id_articulo LIMIT 1";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':id_articulo', $idArticulo, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            // Verificamos si la Query se ejecuto correctamente
            if ($estadoQuery == true) {
                // Verificamos si trajo resultados
                if ($querySQL->rowCount() > 0) {

                    $row = $querySQL->fetch();

                    $unArticulo->setidArticulo($row["id_articulo"]);
                    $unArticulo->setDescripcion($row["descripcion"]);
                    $unArticulo->setIdSector($row["id_sector"]);
                    $unArticulo->setImporte($row["importe"]);
                    $unArticulo->setEstado($row["estado"]);

                    $auxReturn = new Resultado(false, $unArticulo, EstadosError::OK);

                } else {
                    $auxReturn = new Resultado(false, "No existen articulos con ese ID o se encuentra deshabilitado", EstadosError::SIN_RESULTADOS);
                }
            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la query", EstadosError::ERROR_GENERAL);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos (Articulo->TraerUno)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato (Articulo->TraerUno)" . $unError->getMessage(), EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;
    }

    public static function CargarUno($parametros)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Articulo->CargarUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "INSERT INTO articulos (descripcion, id_sector, importe, estado) VALUES (:descripcion, :id_sector, :importe, 1)";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":descripcion", $parametros["descripcion"]);
            $querySQL->bindValue(":id_sector", $parametros["id_sector"]);
            $querySQL->bindValue(":importe", $parametros["importe"]);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {
                $auxReturn = new Resultado(false, "El articulo se guardo correctamente", EstadosError::RECURSO_CREADO);
            } else {
                $auxReturn = new Resultado(true, "No se pudo guardar", EstadosError::ERROR_DB);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function BorrarUno($idArticulo)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Articulo->BorrarUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE articulos SET estado = 0 WHERE id_articulo = :id_articulo AND estado != 0";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":id_articulo", $idArticulo);

            $estadoQuery = $querySQL->execute();

            // Verificamos si la query se ejecuto correctamente
            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar eliminar ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
            } else {
                // Verificamos si se obtuvieron resultados
                if ($querySQL->rowCount() > 0) {
                    $auxReturn = new Resultado(false, "El articulo se elimino correctamente", EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "El articulo no existe o no se encuentra habilitado", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar eliminar un articulo ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar eliminar un articulo ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function ModificarUno($idArticulo, $parametros)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Articulo->ModificarUno";

        try {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE articulos SET descripcion = :descripcion, id_sector = :id_sector, importe = :importe  WHERE id_articulo = :id_articulo AND estado = 1";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_articulo", $idArticulo);
            $querySQL->bindValue(":descripcion", $parametros["descripcion"]);
            $querySQL->bindValue(":id_sector", $parametros["id_sector"]);
            $querySQL->bindValue(":importe", $parametros["importe"]);

            $estadoQuery = $querySQL->execute();
            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar el sector ($ubicacionParaMensaje).", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $auxReturn = new Resultado(false, "Se modifico correctamente el articulo!", EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "No existe un articulo con ese id", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error al intentar actualizar el sector ($ubicacionParaMensaje). Detalles:" . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje , EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensajes = "Ocurrio un error al intentar actualizar el sector ($ubicacionParaMensaje). Detalles:" . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensajes, EstadosError::ERROR_DB);
        }

        return $auxReturn;

    }

    public static function VerificarEstado($idArticulo)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ArticuloDAO->VerificarEstado";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT estado FROM Articulos WHERE estado != 0 AND id_articulo = :idArticulo";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':idArticulo', $idArticulo, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                if ($querySQL->rowCount() > 0) {
                    $rows = $querySQL->fetch();
                    $auxReturn = new Resultado(false, $rows["estado"], EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "El articulo no existe o se encuentra deshabilitado", EstadosError::SIN_RESULTADOS);
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

    public static function VerificarSiExisteArticuloPorDescripcion(string $descripcionArticulo)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ArticuloDAO->VerificarSiExisteArticuloPorDescripcion";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_articulo FROM articulos WHERE estado != 0 AND descripcion = :descripcion";
            
            $querySQLPreparada = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQLPreparada->bindValue(':descripcion', $descripcionArticulo, PDO::PARAM_STR);

            $estadoQuery = $querySQLPreparada->execute();

            if ($estadoQuery == true) {

                if ($querySQLPreparada->rowCount() > 0) {
                    $rows = $querySQLPreparada->fetch();
                    $auxReturn = new Resultado(false, $rows["id_articulo"] , EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "No existe un articulo con esa descripcion", EstadosError::SIN_RESULTADOS);
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

/* #endregion */

}
