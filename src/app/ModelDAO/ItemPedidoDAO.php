<?php

require_once "../src/app/model/ItemPedido.php";
require_once "../src/app/enum/EstadosItemPedidos.php";


class ItemPedidoDAO extends ItemPedido
{
    public static function CargarUno(ItemPedido $nuevoPedido)
    {
        $auxReturn;
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

            if ($querySQL->execute()) {
                $auxReturn = new Resultado(false, "Se guardaron los datos correctamente", EstadosError::OK);
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

    public static function TraerTodosLosPendientes(int $idSector)
    {
        $auxReturn;
        $ubicacionParaMensaje = "ItemPedidoApi->TraerPendientes";
        $pedidosPendientes = [];
        $filtroPorSector = "AND sectores.id_sector = :id_sector";
        $querySQL;

        $auxQuerySQL = "SELECT id_item_pedido, articulos.descripcion as articulo, itemspedidos.cantidad as cantidad, usuarios.usuario as mozo, sectores.descripcion as sector, itemspedidos.estado FROM itemspedidos
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

            $statusQuery = $querySQL->execute();

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
                    $auxReturn = new Resultado(false, "No hay pendientes de preparacion", EstadosError::SIN_RESULTADOS);
                }

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



}
