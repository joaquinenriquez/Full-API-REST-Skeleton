<?php

require_once "../src/app/model/ItemPedido.php";
require_once "../src/app/enum/EstadosItemPedidos.php";


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

            $auxQuerySQL = "INSERT INTO `itemspedidos` (id_pedido, `fecha_inicio`, `fecha_fin`, `id_articulo`, `cantidad`, `tiempo_estimado`, `id_usuario_creador`, `id_usuario_asignado`, `estado`) VALUES
             (:id_pedido, :fecha_inicio, :fecha_fin, :id_articulo, :cantidad, :tiempo_estimado, :id_usuario_creador, :id_usuario_asignado, :estado)";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_pedido", $nuevoPedido->getIdPedido());
            $querySQL->bindValue(":fecha_inicio", $nuevoPedido->getFechaHoraInicio());
            $querySQL->bindValue(":fecha_fin", $nuevoPedido->getFechaHoraFin());
            $querySQL->bindValue(":id_articulo", $nuevoPedido->getIdArticulo());
            $querySQL->bindValue(":cantidad", $nuevoPedido->getCantidad());
            $querySQL->bindValue(":tiempo_estimado", $nuevoPedido->getTiempoEstimado());
            $querySQL->bindValue(":id_usuario_creador", $nuevoPedido->getIdUsuarioOwner());
            $querySQL->bindValue(":id_usuario_asignado", $nuevoPedido->getIdUsuarioAsignado());
            $querySQL->bindValue(":estado", $nuevoPedido->getEstado());

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == true) {
                $idInsertado = $objetoAccesoDatos->RetornarUltimoIdInsertado();
                $auxReturn = new Resultado(false, "Se cargo el pedido correctamente! El id del item del pedido es: $idInsertado", EstadosError::RECURSO_CREADO);
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

    public static function TraerTodosLosPendientes($idSector)
    {
        $auxReturn;
        $ubicacionParaMensaje = "ItemPedidoDAO->TraerPendientes";
        $pedidosPendientes = [];
        $filtroPorSector = "AND sectores.id_sector = :id_sector";
        $querySQL;

        $auxQuerySQL = "SELECT id_item_pedido, articulos.descripcion as articulo, itemspedidos.cantidad as cantidad, usuarios.nombre_usuario as mozo, sectores.descripcion as sector, itemspedidos.estado FROM itemspedidos
        LEFT JOIN articulos on articulos.id_articulo = itemspedidos.id_articulo
        LEFT JOIN usuarios on usuarios.id_usuario = itemspedidos.id_usuario_creador
        LEFT JOIN sectores on sectores.id_sector = articulos.id_sector
        WHERE itemsPedidos.estado = 1";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            
            // Todos los sectores (para los socios)
            if ($idSector != 99) {
                $auxQuerySQL = $auxQuerySQL . " " . $filtroPorSector;
            }

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(":id_sector", $idSector);

            if ($querySQL->execute()) {
                if ($querySQL->rowCount() > 0) {
                    while ($row = $querySQL->fetch()) {
                        $itemPedido = new stdClass();
                        $itemPedido->idItem = $row["id_item_pedido"];
                        $itemPedido->articulo = $row["articulo"];
                        $itemPedido->cantidad = $row["cantidad"];
                        $itemPedido->Mozo = $row["mozo"];
                        $itemPedido->Sector = $row["sector"];
                        $itemPedido->estado = EstadosItemPedido::TraerEstadoPorId($row["estado"]);
                        
                        array_push($pedidosPendientes, $itemPedido);
                    }

                    $auxReturn = new Resultado(false, $pedidosPendientes, EstadosError::OK);
                } else {
                    $mensaje = "No hay pendientes de preparacion para el sector seleccionado";
                    $auxReturn = new Resultado(false, $mensaje , EstadosError::SIN_RESULTADOS);
                }

            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::ERROR_GENERAL);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;
    }

    public static function TomarPedido($idItemPedido, $idUsuarioActual, $tiempoEstimado) {

        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->TomarPedido";
        
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $tiempoEstimado = (self::ConvertirIntAMinutos($tiempoEstimado));
        $fechaInicio = date('d/m/y H:i');

        try {

            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "UPDATE itemsPedidos SET 
                                    estado = 2, 
                                    tiempo_estimado = :tiempo_estimado, 
                                    id_usuario_asignado = :id_usuario_asignado, 
                                    fecha_inicio = :fecha_inicio
                            WHERE id_item_pedido = :id_item_pedido AND estado = 1";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_item_pedido", $idItemPedido);
            $querySQL->bindValue(":tiempo_estimado", $tiempoEstimado);
            $querySQL->bindValue(":id_usuario_asignado", $idUsuarioActual);
            $querySQL->bindValue(":fecha_inicio", $fechaInicio);

            if (!$querySQL->execute()) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar modificar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_GENERAL);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $mensaje = "Se actualizo correctamente! El estado actual es: " . strtoupper(EstadosItemPedido::TraerEstadoPorId(2));
                    $auxReturn = new Resultado(false, $mensaje , EstadosError::OK);
                } else {
                    $auxReturn = new Resultados(true, "No existen datos con ese id", EstadosError::SIN_RESULTADOS);
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
    
    public static function VerificarEstado($idItemPedido) {
    
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

    public static function TraerUno($idItemPedido) {
    
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "ItemPedidoDAO->TraerUno";
        $rows = [];
    
        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT 
            itemspedidos.id_item_pedido,
            ItemsPedidos.id_pedido as id_pedido,
            cabeceraspedidos.codigo_amigable as codigo_amigable, 
            cabeceraspedidos.nombre_cliente as nombre_cliente,
            ItemsPedidos.id_pedido as id_pedido, 
            ItemsPedidos.fecha_inicio as fecha_inicio, 
            ItemsPedidos.fecha_fin as fecha_fin, 
            itemspedidos.id_articulo as id_articulo, 
            articulos.descripcion as descripcion_articulo, 
            articulos.id_sector as id_sector,
            sectores.descripcion as descripcion_sector,
            cantidad, 
            tiempo_estimado, 
            id_usuario_creador, 
            usuarios_creadores.nombre_usuario as usuario_creador,
            id_usuario_asignado,
            usuarios_asignados.nombre_usuario as usuario_asignado,
            itemspedidos.estado as estado

            FROM ItemsPedidos 
            LEFT JOIN articulos on articulos.id_articulo = itemspedidos.id_articulo
            LEFT JOIN usuarios as usuarios_asignados on usuarios_asignados.id_usuario = itemspedidos.id_usuario_asignado
            LEFT JOIN usuarios as usuarios_creadores on usuarios_creadores.id_usuario = itemspedidos.id_usuario_creador
            LEFT JOIN cabeceraspedidos on cabeceraspedidos.id_pedido = itemspedidos.id_pedido
            LEFT JOIN sectores on sectores.id_sector = articulos.id_sector
            WHERE itemspedidos.estado != 0 AND id_item_pedido = :id_item_pedido";
    
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
    
            $querySQL->bindValue(':id_item_pedido', $idItemPedido, PDO::PARAM_INT);
    
            $estadoQuery = $querySQL->execute();
    
            if ($estadoQuery == true) {
    
                if ($querySQL->rowCount() > 0) {
                    $rows = $querySQL->fetch();
                    $unItemPedido = new ItemPedido();

                    $unItemPedido->setIdItemPedido($rows["id_item_pedido"]);
                    $unItemPedido->setIdPedido($rows["id_pedido"]);
                    $unItemPedido->setCodigoAmigable($rows["codigo_amigable"]);
                    $unItemPedido->setFechaHoraInicio($rows["fecha_inicio"]);
                    $unItemPedido->setFechaHoraFin($rows["fecha_fin"]);
                    $unItemPedido->setIdArticulo($rows["id_articulo"]);
                    $unItemPedido->setCantidad($rows["cantidad"]);
                    $unItemPedido->setTiempoEstimado($rows["tiempo_estimado"]);
                    $unItemPedido->setIdUsuarioOwner($rows["id_usuario_creador"]);
                    $unItemPedido->setIdUsuarioAsignado($rows["id_usuario_asignado"]);
                    $unItemPedido->setEstado($rows["estado"]);

                    $unItemPedido->setDescripcionArticulo($rows["descripcion_articulo"]);
                    $unItemPedido->setUsuarioAsignado($rows["usuario_asignado"]);
                    $unItemPedido->setUsuarioCreador($rows["usuario_creador"]);
                    $unItemPedido->setDescripcionSector($rows["descripcion_sector"]);
                    $unItemPedido->setNombreCliente($rows["nombre_cliente"]);

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
                                id_item_pedido, id_pedido, fecha_inicio, fecha_fin, id_articulo, cantidad, tiempo_estimado, id_usuario_creador, id_usuario_asignado, estado
                            FROM ItemsPedidos WHERE estado != 0 AND id_articulo = :id_articulo";
    
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
    
            $querySQL->bindValue(':id_articulo', $idArticulo, PDO::PARAM_INT);
    
            $estadoQuery = $querySQL->execute();
    
            if ($estadoQuery == true) {
    
                if ($querySQL->rowCount() > 0) {
                    $rows = $querySQL->fetch();
                    $unItemPedido = new ItemPedido();

                    $unItemPedido->setIdItemPedido($rows["id_item_pedido"]);
                    $unItemPedido->setIdPedido($rows["id_pedido"]);
                    $unItemPedido->setFechaHoraInicio($rows["fecha_inicio"]);
                    $unItemPedido->setFechaHoraFin($rows["fecha_fin"]);
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

    public static function ConvertirIntAMinutos($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    #endregion 
}
