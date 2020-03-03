<?php

class ItemPedidoDAO extends ItemPedido
{
    #region Métodos estaticos

    public static function CargarUno(ItemPedido $nuevoPedido)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->CargarUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            $auxQuerySQL = "INSERT INTO `itemspedidos` (id_pedido, fecha_hora_creacion, `fecha_hora_inicio_preparacion`, `fecha_hora_fin_preparacion`, `id_articulo`, `cantidad`, `tiempo_estimado`, `id_usuario_creador`, `id_usuario_asignado`, `estado`) VALUES
             (:id_pedido, :fecha_hora_creacion, :fecha_hora_inicio_preparacion, :fecha_hora_fin_preparacion, :id_articulo, :cantidad, :tiempo_estimado, :id_usuario_creador, :id_usuario_asignado, :estado)";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_pedido", $nuevoPedido->getIdPedido());
            $querySQL->bindValue(":fecha_hora_creacion", $nuevoPedido->getFechaHoraCreacion());
            $querySQL->bindValue(":fecha_hora_inicio_preparacion", $nuevoPedido->getFechaHoraInicio());
            $querySQL->bindValue(":fecha_hora_fin_preparacion", $nuevoPedido->getFechaHoraFin());
            $querySQL->bindValue(":id_articulo", $nuevoPedido->getIdArticulo());
            $querySQL->bindValue(":cantidad", $nuevoPedido->getCantidad());
            $querySQL->bindValue(":tiempo_estimado", $nuevoPedido->getTiempoEstimado());
            $querySQL->bindValue(":id_usuario_creador", $nuevoPedido->getIdUsuarioOwner());
            $querySQL->bindValue(":id_usuario_asignado", $nuevoPedido->getIdUsuarioAsignado());
            $querySQL->bindValue(":estado", $nuevoPedido->getEstado());

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                // Nos traemos el id que cargamos
                $idInsertado = $objetoAccesoDatos->RetornarUltimoIdInsertado();

                // Traemos la descripcion del articulo
                $auxReturn = ArticuloDAO::TraerUno($nuevoPedido->getIdArticulo());
                if ($auxReturn->getStatus() == EstadosError::OK) {
                    $descripcionArticulo = $auxReturn->getMensaje()->getDescripcion();
                }

                $auxReturn = new Resultado(false, "Se cargo el pedido correctamente! El id del ultimo item del pedido es: $idInsertado", EstadosError::RECURSO_CREADO);

            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;

    }

    public static function TraerPedidos($estadoItemPedido, $sectorDelArticulo, $idMesa, $usuarioAsignado)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->TraerTodosLosPendientes";
        $listadoItemsPedidosPendidos = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Pedidos::TraerPedidos;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            // Si nos piden por usuario asignado, como originalmente es null tenemos que hacer esta desprolijidad
            if ($usuarioAsignado != null) {
                $auxQuerySQL = $auxQuerySQL . " AND itemspedidos.id_usuario_asignado = IFNULL(:id_usuario_asignado, itemspedidos.id_usuario_asignado)";
                $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
                $querySQL->bindValue(":id_usuario_asignado", $usuarioAsignado);
            }

            $querySQL->bindValue(":estado", $estadoItemPedido);
            $querySQL->bindValue(":id_sector", $sectorDelArticulo);
            $querySQL->bindValue(":id_mesa", $idMesa);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::OK);
            } else {
                if ($querySQL->rowCount() <= 0) {
                    $mensaje = "No hay ningun item de pedido con esas caracteristicas.";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::SIN_RESULTADOS);
                } else {
                    $rows = $querySQL->fetchAll();
                    foreach ($rows as $unaRow) {

                        $unItemPedido = new ItemPedidoRelacionado();

                        $unItemPedido->setIdItemPedido($unaRow["id_item_pedido"]);
                        $unItemPedido->setIdPedido($unaRow["id_pedido"]);
                        $unItemPedido->setFechaHoraCreacion($unaRow["fecha_hora_creacion"]);

                        $unItemPedido->setIdArticulo($unaRow["id_articulo"]);
                        $unItemPedido->setDescripcionArticulo($unaRow["descripcion_articulo"]);
                        $unItemPedido->setCantidad($unaRow["cantidad"]);
                        $unItemPedido->setImporteArticulo($unaRow["importe"]);
                        $unItemPedido->setIdSector($unaRow["id_sector"]);
                        $unItemPedido->setDescripcionSector($unaRow["descripcion_sector"]);

                        $unItemPedido->setIdUsuarioOwner($unaRow["id_usuario_creador"]);
                        $unItemPedido->setUsuarioCreador($unaRow["usuario_creador"]);
                        $unItemPedido->setIdUsuarioAsignado($unaRow["id_usuario_asignado"]);
                        $unItemPedido->setUsuarioAsignado($unaRow["usuario_asignado"]);
                        $unItemPedido->setFechaHoraInicio($unaRow["fecha_hora_inicio"]);
                        $unItemPedido->setFechaHoraFin($unaRow["fecha_hora_fin"]);
                        $unItemPedido->setTiempoEstimado($unaRow["tiempo_estimado"]);
                        $unItemPedido->setEstado($unaRow["estado"]);

                        $unItemPedido->setNombreCliente($unaRow["nombre_cliente"]);
                        $unItemPedido->setIdMesa($unaRow["id_mesa"]);
                        $unItemPedido->setCodigoAmigable($unaRow["codigo_amigable"]);
                        $unItemPedido->setCodigoAmigableMesa($unaRow["codigo_amigable_mesa"]);
                        $unItemPedido->setEstadoMesa($unaRow["estado_mesa"]);

                        array_push($listadoItemsPedidosPendidos, $unItemPedido);
                    }

                    $auxReturn = new Resultado(false, $listadoItemsPedidosPendidos, EstadosError::OK);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TomarPedido($idItemPedido, $idUsuarioActual, $tiempoEstimado)
    {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->TomarPedido";

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        
        $fechaHoraInicio = date('Y/m/d H:i');

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Pedidos::TomarItemPedido;

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_item_pedido", $idItemPedido);
            $querySQL->bindValue(":tiempo_estimado", $tiempoEstimado);
            $querySQL->bindValue(":id_usuario_asignado", $idUsuarioActual);
            $querySQL->bindValue(":fecha_hora_inicio_preparacion", $fechaHoraInicio);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $mensaje = "Se actualizo correctamente! El estado actual es: " . strtoupper(EstadosItemPedido::TraerEstadoPorId(2));
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(true, "No existen datos con ese id con estado PENDIENTE", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function FinalizarPreparacionItemPedido($idItemPedido)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->FinalizarPreparacionItemPedido";

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaHoraFin = date('Y/m/d H:i');

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Pedidos::FinalizarPreparacionItemPedido;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_item_pedido", $idItemPedido);
            $querySQL->bindValue(":fecha_hora_fin_preparacion", $fechaHoraFin);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $mensaje = "Se actualizo correctamente! El estado actual es: " . strtoupper(EstadosItemPedido::TraerEstadoPorId(3));
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(true, "No existen datos con ese id de item de pedido", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function CancelarItemPedido($idItemPedido)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->CancelarItemPedido";

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Pedidos::CancelarItemPedido;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            
            $querySQL->bindValue(":id_item_pedido", $idItemPedido);
    
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) 
            {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else if ($querySQL->rowCount() <= 0) 
            {
                $auxReturn = new Resultado(true, "No existen datos con ese id de item de pedido", EstadosError::SIN_RESULTADOS);
            } else 
            {   
                $mensaje = "Se actualizo correctamente! El estado actual es: " . strtoupper(EstadosItemPedido::TraerEstadoPorId(10));
                $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function ServirItemPedido($idItemPedido)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->ServirItemPedido";

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Pedidos::ServirItemPedido;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_item_pedido", $idItemPedido);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $mensaje = "Se actualizo correctamente! El estado actual es: " . strtoupper(EstadosItemPedido::TraerEstadoPorId(4));
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(true, "No existen datos con ese id de item de pedido", EstadosError::SIN_RESULTADOS);
                }
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function VerificarEstado($idItemPedido)
    {

        $auxReturn = false;
        $ubicacionParaMensaje = "ItemPedidoDAO->VerificarEstado";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            $auxQuerySQL = "SELECT estado FROM ItemsPedidos WHERE estado != 0 AND id_item_pedido = :id_item_pedido";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':id_item_pedido', $idItemPedido, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                if ($querySQL->rowCount() > 0) {
                    $rows = $querySQL->fetch();
                    $auxReturn = new Resultado(false, $rows["estado"], EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "El item ($idItemPedido) no existe o se encuentra deshabilitado", EstadosError::SIN_RESULTADOS);
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

    public static function TraerUno($idItemPedido)
    {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->TraerUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Pedidos::TraerUno;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':id_item_pedido', $idItemPedido, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                if ($querySQL->rowCount() > 0) {
                    $unaRow = $querySQL->fetch();
                    $unItemPedido = new ItemPedidoRelacionado();

                    $unItemPedido = new ItemPedidoRelacionado();

                    $unItemPedido->setIdItemPedido($unaRow["id_item_pedido"]);
                    $unItemPedido->setIdPedido($unaRow["id_pedido"]);
                    $unItemPedido->setFechaHoraCreacion($unaRow["fecha_hora_creacion"]);

                    $unItemPedido->setIdArticulo($unaRow["id_articulo"]);
                    $unItemPedido->setDescripcionArticulo($unaRow["descripcion_articulo"]);
                    $unItemPedido->setCantidad($unaRow["cantidad"]);
                    $unItemPedido->setImporteArticulo($unaRow["importe"]);
                    $unItemPedido->setIdSector($unaRow["id_sector"]);
                    $unItemPedido->setDescripcionSector($unaRow["descripcion_sector"]);

                    $unItemPedido->setIdUsuarioOwner($unaRow["id_usuario_creador"]);
                    $unItemPedido->setUsuarioCreador($unaRow["usuario_creador"]);
                    $unItemPedido->setIdUsuarioAsignado($unaRow["id_usuario_asignado"]);
                    $unItemPedido->setUsuarioAsignado($unaRow["usuario_asignado"]);
                    $unItemPedido->setFechaHoraInicio($unaRow["fecha_hora_inicio"]);
                    $unItemPedido->setFechaHoraFin($unaRow["fecha_hora_fin"]);
                    $unItemPedido->setTiempoEstimado($unaRow["tiempo_estimado"]);
                    $unItemPedido->setEstado($unaRow["estado"]);

                    $unItemPedido->setNombreCliente($unaRow["nombre_cliente"]);
                    $unItemPedido->setIdMesa($unaRow["id_mesa"]);
                    $unItemPedido->setCodigoAmigable($unaRow["codigo_amigable"]);
                    $unItemPedido->setCodigoAmigableMesa($unaRow["codigo_amigable_mesa"]);
                    $unItemPedido->setEstadoMesa($unaRow["estado_mesa"]);

                    $auxReturn = new Resultado(false, $unItemPedido, EstadosError::OK);

                } else {
                    $auxReturn = new Resultado(false, "El item no existe o se encuentra deshabilitado", EstadosError::SIN_RESULTADOS);
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

    public static function TraerArticuloByIdItemPedido($idItemPedido)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->TraerArticuloByIdItemPedido";
        $articuloSeleccionado = new Articulo();

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT
                                    articulos.id_articulo as id_articulo,
                                    articulos.descripcion as descripcion_articulo,
                                    articulos.id_sector as id_sector_articulo,
                                    articulos.importe as importe_articulo,
                                    articulos.estado as estado_articulo
                            FROM itemspedidos
                            LEFT join articulos on articulos.id_articulo = itemspedidos.id_articulo
                            WHERE itemspedidos.id_item_pedido = :id_item_pedido AND itemspedidos.estado != 0 LIMIT 1";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':id_item_pedido', $idItemPedido, PDO::PARAM_INT);
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                if ($querySQL->rowCount() > 0) {
                    $row = $querySQL->fetch();
                    $articuloSeleccionado->setIdArticulo($row["id_articulo"]);
                    $articuloSeleccionado->setDescripcion($row["descripcion_articulo"]);
                    $articuloSeleccionado->setIdSector($row["id_sector_articulo"]);
                    $articuloSeleccionado->setImporte($row["importe_articulo"]);
                    $articuloSeleccionado->setEstado($row["estado_articulo"]);

                    $auxReturn = new Resultado(false, $articuloSeleccionado, EstadosError::OK);

                } else {
                    $auxReturn = new Resultado(false, "El item ($idItemPedido) no existe o se encuentra deshabilitado", EstadosError::SIN_RESULTADOS);
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

    public static function TraerItemPedidoPorIdArticulo($idArticulo)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->BuscarItemPedidoConArticulo";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT
                                id_item_pedido, fecha_hora_creacion, id_pedido, fecha_hora_inicio_preparacion, fecha_hora_fin_preparacion, id_articulo, cantidad, tiempo_estimado, id_usuario_creador, id_usuario_asignado, estado
                            FROM ItemsPedidos WHERE estado != 0 AND id_articulo = :id_articulo";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':id_articulo', $idArticulo, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {

                if ($querySQL->rowCount() > 0) {
                    $rows = $querySQL->fetch();
                    $unItemPedido = new ItemPedido();

                    $unItemPedido->setIdItemPedido($rows["id_item_pedido"]);
                    $unItemPedido->setIdItemPedido($rows["fecha_hora_creacion"]);
                    $unItemPedido->setIdPedido($rows["id_pedido"]);
                    $unItemPedido->setFechaHoraInicio($rows["fecha_hora_inicio_preparacion"]);
                    $unItemPedido->setFechaHoraFin($rows["fecha_hora_fin_preparacion"]);
                    $unItemPedido->setIdArticulo($rows["id_articulo"]);
                    $unItemPedido->setCantidad($rows["cantidad"]);
                    $unItemPedido->setTiempoEstimado($rows["tiempo_estimado"]);
                    $unItemPedido->setIdUsuarioOwner($rows["id_usuario_creador"]);
                    $unItemPedido->setIdUsuarioAsignado($rows["id_usuario_asignado"]);
                    $unItemPedido->setEstado($rows["estado"]);

                    $auxReturn = new Resultado(false, $unItemPedido, EstadosError::OK);

                } else {
                    $auxReturn = new Resultado(false, "El item no existe o se encuentra deshabilitado", EstadosError::SIN_RESULTADOS);
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

    public static function ConvertirIntAMinutos($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function TraerPedidosTomadosPorUsuario($idUsuario)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->TraerPedidosTomadosPorUsuario";
        $listadoItemsPedidos = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Pedidos::TraerPedidosTomadosPorUsuario;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':id_usuario_asignado', $idUsuario, PDO::PARAM_INT);
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {

                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la query. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0) {

                $auxReturn = new Resultado(false, "No existen item de pedidos vigentes asignados para el usuario seleccionado", EstadosError::SIN_RESULTADOS);

            } else {
                $rows = $querySQL->fetchAll();

                foreach ($rows as $unaRow) {

                    $unItemPedido = new ItemPedidoRelacionado();

                    $unItemPedido->setIdItemPedido($unaRow["id_item_pedido"]);
                    $unItemPedido->setIdItemPedido($unaRow["fecha_hora_creacion"]);
                    $unItemPedido->setIdPedido($unaRow["id_pedido"]);
                    $unItemPedido->setCodigoAmigable($unaRow["codigo_amigable"]);
                    $unItemPedido->setFechaHoraInicio($unaRow["fecha_hora_inicio_preparacion"]);
                    $unItemPedido->setFechaHoraFin($unaRow["fecha_hora_fin_preparacion"]);
                    $unItemPedido->setIdArticulo($unaRow["id_articulo"]);
                    $unItemPedido->setCantidad($unaRow["cantidad"]);
                    $unItemPedido->setIdSector($unaRow["id_sector"]);
                    $unItemPedido->setTiempoEstimado($unaRow["tiempo_estimado"]);
                    $unItemPedido->setIdUsuarioOwner($unaRow["id_usuario_creador"]);
                    $unItemPedido->setIdUsuarioAsignado($unaRow["id_usuario_asignado"]);
                    $unItemPedido->setEstado($unaRow["estado"]);

                    $unItemPedido->setDescripcionArticulo($unaRow["descripcion_articulo"]);
                    $unItemPedido->setUsuarioAsignado($unaRow["usuario_asignado"]);
                    $unItemPedido->setUsuarioCreador($unaRow["usuario_creador"]);
                    $unItemPedido->setDescripcionSector($unaRow["descripcion_sector"]);
                    $unItemPedido->setNombreCliente($unaRow["nombre_cliente"]);

                    array_push($listadoItemsPedidos, $unItemPedido);
                }

                $auxReturn = new Resultado(false, $listadoItemsPedidos, EstadosError::OK);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function CerrarItemPedido($idItemPedido)
    {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->CancelarItemPedido";

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Pedidos::CerrarItemPedido;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            
            $querySQL->bindValue(":id_item_pedido", $idItemPedido);
    
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) 
            {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else if ($querySQL->rowCount() <= 0) 
            {
                $auxReturn = new Resultado(true, "No existen datos con ese id de item de pedido", EstadosError::SIN_RESULTADOS);
            } else 
            {   
                $mensaje = "Se actualizo correctamente! El estado actual es: " . strtoupper(EstadosItemPedido::TraerEstadoPorId(10));
                $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar actualizar. ($ubicacionParaMensaje). Detalles: " . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerItemsPedidoDeUnPedido($idPedido)
    {

    }



    #endregion
}
