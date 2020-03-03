<?php


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

    public static function TraerMesaMasUsada($fechaHoraDesde, $fechaHoraHasta)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->TraerMesaMasUsada";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Mesas::TraerMesaMasUsada;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            
            $querySQL->bindValue(":fecha_hora_desde", $fechaHoraDesde);
            $querySQL->bindValue(":fecha_hora_hasta", $fechaHoraHasta);
        
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::OK);
            } else {
                if ($querySQL->rowCount() <= 0) {
                    $mensaje = "No se encontro ningun registro.";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::SIN_RESULTADOS);
                } else {

                    $rows[] = $querySQL->fetchAll();
                    $rows = $rows[0];

                    $listaMesasMasUsadas = [];
                    for ($i = 0; $i < count($rows); $i++)
                    {                    
                        $mesaMasUsada = new stdClass();
                        $mesaMasUsada->id_mesa = $rows[$i]["id_mesa"];
                        $mesaMasUsada->codigo_amigable = $rows[$i]["codigo_amigable"];
                        $mesaMasUsada->cantidad_operaciones = $rows[$i]["cantidad_operaciones"];

                        // Hacemos esta desprolijidad por si son varias
                        if (count($listaMesasMasUsadas) > 0)
                        {
                            $cantidadOperacionesAnterior = $rows[$i - 1]["cantidad_operaciones"];
                            if ($cantidadOperacionesAnterior != $rows[$i]["cantidad_operaciones"])
                            {
                                break;
                            }
                        }

                        array_push($listaMesasMasUsadas, $mesaMasUsada);
                    }

                    $auxReturn = new Resultado(false, $listaMesasMasUsadas, EstadosError::OK);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerMesaMenosUsada($fechaHoraDesde, $fechaHoraHasta)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->TraerMesaMenosUsada";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Mesas::TraerMesasMenosUsadas;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            
            $querySQL->bindValue(":fecha_hora_desde", $fechaHoraDesde);
            $querySQL->bindValue(":fecha_hora_hasta", $fechaHoraHasta);
        
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::OK);
            } else {
                if ($querySQL->rowCount() <= 0) {
                    $mensaje = "No se encontro ningun registro.";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::SIN_RESULTADOS);
                } else {

                    $rows[] = ($querySQL->fetchAll());
                    $rows = $rows[0];
                    $listaMesasMenosUsadas = [];

                    for($i = 0; $i< count($rows); $i++)
                    {
                        $unaMesaMenosUsada = new stdClass();
                        $unaMesaMenosUsada->id_mesa = $rows[$i]["id_mesa"];
                        $unaMesaMenosUsada->codigo_amigable = $rows[$i]["codigo_amigable"];
                        $unaMesaMenosUsada->cantidad_operaciones = $rows[$i]["cantidad_operaciones"];
                        
                        // Hacemos esta desprolijidad por si son varias
                        if (count($listaMesasMenosUsadas) > 0)
                        {
                            $cantidadOperacionesAnterior = $rows[$i - 1]["cantidad_operaciones"];
                            if ($cantidadOperacionesAnterior != $rows[$i]["cantidad_operaciones"])
                            {
                                break;
                            }
                        }

                        array_push($listaMesasMenosUsadas, $unaMesaMenosUsada);
                    }
     
                   $auxReturn = new Resultado(false, $listaMesasMenosUsadas, EstadosError::OK);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerMesaMasFacturo($fechaHoraDesde, $fechaHoraHasta)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->TraerMesaMasFacturo";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Mesas::TraerMesasQueMasFacturaron;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":fecha_hora_desde", $fechaHoraDesde);
            $querySQL->bindValue(":fecha_hora_hasta", $fechaHoraHasta);

        
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::OK);
            } else {
                if ($querySQL->rowCount() <= 0) {
                    $mensaje = "No se encontro ningun registro.";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::SIN_RESULTADOS);
                } else {

                    $rows[] = $querySQL->fetchAll();
                    $rows = $rows[0];
                    $listaMesasMasFacturaron = [];

                    for($i = 0; $i< count($rows); $i++)
                    {
                        $unaMesaQueMasFacturo = new stdClass();
                        $unaMesaQueMasFacturo->id_mesa = $rows[$i]["id_mesa"];
                        $unaMesaQueMasFacturo->codigo_amigable = $rows[$i]["codigo_amigable"];
                        $unaMesaQueMasFacturo->cantidad_operaciones = $rows[$i]["importe_total"];
                        
                        // Hacemos esta desprolijidad por si son varias
                        if (count($listaMesasMasFacturaron) > 0)
                        {
                            $cantidadOperacionesAnterior = $rows[$i - 1]["importe_total"];
                            if ($cantidadOperacionesAnterior != $rows[$i]["importe_total"])
                            {
                                break;
                            }
                        }

                        array_push($listaMesasMasFacturaron, $unaMesaQueMasFacturo);
                    }
     
                   $auxReturn = new Resultado(false, $listaMesasMasFacturaron, EstadosError::OK);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerMesaMenosFacturo($fechaHoraDesde, $fechaHoraHasta)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->TraerMesaMenosFacturo";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Mesas::TraerMesasQueMenosFacturaron;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            
            $querySQL->bindValue(":fecha_hora_desde", $fechaHoraDesde);
            $querySQL->bindValue(":fecha_hora_hasta", $fechaHoraHasta);
        
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::OK);
            } else {
                if ($querySQL->rowCount() <= 0) {
                    $mensaje = "No se encontro ningun registro.";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::SIN_RESULTADOS);
                } else {

                    $rows[] = ($querySQL->fetchAll());
                    $rows = $rows[0];
                    $listaMesasMenosFacturaron = [];

                    for($i = 0; $i< count($rows); $i++)
                    {
                        $unaMesaMenosFacturo = new stdClass();
                        $unaMesaMenosFacturo->id_mesa = $rows[$i]["id_mesa"];
                        $unaMesaMenosFacturo->codigo_amigable = $rows[$i]["codigo_amigable"];
                        $unaMesaMenosFacturo->cantidad_operaciones = $rows[$i]["importe_total"];
                        
                        // Hacemos esta desprolijidad por si son varias
                        if (count($listaMesasMenosFacturaron) > 0)
                        {
                            $cantidadOperacionesAnterior = $rows[$i - 1]["importe_total"];
                            if ($cantidadOperacionesAnterior != $rows[$i]["importe_total"])
                            {
                                break;
                            }
                        }

                        array_push($listaMesasMenosFacturaron, $unaMesaMenosFacturo);
                    }
     
                   $auxReturn = new Resultado(false, $listaMesasMenosFacturaron, EstadosError::OK);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerMesasMayorImporte($fechaHoraDesde, $fechaHoraHasta)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->TraerMesasMayorImporte";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Mesas::TraerMesasConMayorImporte;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            
            $querySQL->bindValue(":fecha_hora_desde", $fechaHoraDesde);
            $querySQL->bindValue(":fecha_hora_hasta", $fechaHoraHasta);
        
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::OK);
            } else {
                if ($querySQL->rowCount() <= 0) {
                    $mensaje = "No se encontro ningun registro.";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::SIN_RESULTADOS);
                } else {

                    $rows[] = ($querySQL->fetchAll());
                    $rows = $rows[0];
                    $listaMesasMayorImporte = [];

                    for($i = 0; $i< count($rows); $i++)
                    {
                        $unaMesaMayorImporte = new stdClass();
                        $unaMesaMayorImporte->id_mesa = $rows[$i]["id_mesa"];
                        $unaMesaMayorImporte->codigo_amigable = $rows[$i]["codigo_amigable"];
                        $unaMesaMayorImporte->cantidad_operaciones = $rows[$i]["importe"];
                        
                        // Hacemos esta desprolijidad por si son varias
                        if (count($listaMesasMayorImporte) > 0)
                        {
                            $cantidadOperacionesAnterior = $rows[$i - 1]["importe"];
                            if ($cantidadOperacionesAnterior != $rows[$i]["importe"])
                            {
                                break;
                            }
                        }

                        array_push($listaMesasMayorImporte, $unaMesaMayorImporte);
                    }
     
                   $auxReturn = new Resultado(false, $listaMesasMayorImporte, EstadosError::OK);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerMesasMenorImporte($fechaHoraDesde, $fechaHoraHasta)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "MesaDAO->TraerMesasMenorImporte";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Mesas::TraerMesasConMenorImporte;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            
            $querySQL->bindValue(":fecha_hora_desde", $fechaHoraDesde);
            $querySQL->bindValue(":fecha_hora_hasta", $fechaHoraHasta);
        
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::OK);
            } else {
                if ($querySQL->rowCount() <= 0) {
                    $mensaje = "No se encontro ningun registro.";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::SIN_RESULTADOS);
                } else {

                    $rows[] = ($querySQL->fetchAll());
                    $rows = $rows[0];
                    $listaMesasMenorImporte = [];

                    for($i = 0; $i< count($rows); $i++)
                    {
                        $unaMesaMenorImporte = new stdClass();
                        $unaMesaMenorImporte->id_mesa = $rows[$i]["id_mesa"];
                        $unaMesaMenorImporte->codigo_amigable = $rows[$i]["codigo_amigable"];
                        $unaMesaMenorImporte->cantidad_operaciones = $rows[$i]["importe"];
                        
                        // Hacemos esta desprolijidad por si son varias
                        if (count($listaMesasMenorImporte) > 0)
                        {
                            $cantidadOperacionesAnterior = $rows[$i - 1]["importe"];
                            if ($cantidadOperacionesAnterior != $rows[$i]["importe"])
                            {
                                break;
                            }
                        }

                        array_push($listaMesasMenorImporte, $unaMesaMenorImporte);
                    }
     
                   $auxReturn = new Resultado(false, $listaMesasMenorImporte, EstadosError::OK);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }


/* #endregion */

}
