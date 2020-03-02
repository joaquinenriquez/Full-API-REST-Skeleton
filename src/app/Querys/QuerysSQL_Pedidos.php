<?php

class QuerysSQL_Pedidos
{
        public const FinalizarPreparacionItemPedido = "UPDATE itemsPedidos 
                SET estado = 3, fecha_hora_fin_preparacion = :fecha_hora_fin_preparacion WHERE id_item_pedido = :id_item_pedido AND estado = 2";

        public const ServirItemPedido = "UPDATE itemsPedidos 
                SET estado = 4 WHERE id_item_pedido = :id_item_pedido AND estado = 3";
        
        public const TomarItemPedido = "UPDATE itemsPedidos 
                                                SET estado = 2, tiempo_estimado = :tiempo_estimado, id_usuario_asignado = :id_usuario_asignado, fecha_hora_inicio_preparacion = :fecha_hora_inicio_preparacion
                                                WHERE id_item_pedido = :id_item_pedido AND estado = 1";

        public const CancelarItemPedido = "UPDATE itemsPedidos SET estado = 10 WHERE id_item_pedido = :id_item_pedido AND estado != 0";

        public const TraerPedidos = "SELECT
                itemspedidos.id_item_pedido as id_item_pedido,
                itemspedidos.fecha_hora_creacion as fecha_hora_creacion,
                itemsPedidos.id_pedido as id_pedido,
                itemspedidos.id_articulo as id_articulo,
                articulos.descripcion as descripcion_articulo,
                itemspedidos.cantidad as cantidad,
                articulos.importe as importe,
                articulos.id_sector as id_sector,
                sectores.descripcion as descripcion_sector,

                itemspedidos.id_usuario_creador as id_usuario_creador,
                usuarios_creadores.nombre_usuario as usuario_creador,
                itemspedidos.id_usuario_asignado as id_usuario_asignado,
                usuarios_asignados.nombre_usuario as usuario_asignado,
                ItemsPedidos.fecha_hora_inicio_preparacion as fecha_hora_inicio,
                ItemsPedidos.fecha_hora_fin_preparacion as fecha_hora_fin,
                ItemsPedidos.tiempo_estimado as tiempo_estimado,
                itemspedidos.estado as estado,

                cabeceraspedidos.nombre_cliente as nombre_cliente,
                cabeceraspedidos.id_mesa as id_mesa,
                cabeceraspedidos.codigo_amigable as codigo_amigable,
                mesas.codigo_amigable as codigo_amigable_mesa,
                mesas.estado as estado_mesa

                FROM ItemsPedidos

                LEFT JOIN articulos on articulos.id_articulo = itemspedidos.id_articulo
                LEFT JOIN usuarios as usuarios_asignados on usuarios_asignados.id_usuario = itemspedidos.id_usuario_asignado
                LEFT JOIN usuarios as usuarios_creadores on usuarios_creadores.id_usuario = itemspedidos.id_usuario_creador
                LEFT JOIN cabeceraspedidos on cabeceraspedidos.id_pedido = itemspedidos.id_pedido
                LEFT JOIN sectores on sectores.id_sector = articulos.id_sector
                LEFT JOIN Mesas on Mesas.id_mesa = cabeceraspedidos.id_mesa
                
                WHERE itemspedidos.estado != 0 
                AND itemspedidos.estado = IFNULL(:estado, itemspedidos.estado)
                AND articulos.id_sector = IFNULL(:id_sector, articulos.id_sector)
                AND mesas.id_mesa = IFNULL(:id_mesa, mesas.id_mesa)";
                // AND itemspedidos.id_usuario_asignado = IFNULL(:id_usuario_asignado, IFNULL(itemspedidos.id_usuario_asignado, '-'))";
 

        public const TraerPedidosTomadosPorUsuario = "SELECT
            itemspedidos.id_item_pedido,
            ItemsPedidos.id_pedido as id_pedido,
            itemspedidos.id_articulo as id_articulo,
            articulos.descripcion as descripcion_articulo,
            itemspedidos.cantidad as cantidad,
            articulos.importe as importe,
            articulos.id_sector as id_sector,
            sectores.descripcion as descripcion_sector,

            id_usuario_creador as id_usuario_creador,
            usuarios_creadores.nombre_usuario as usuario_creador,
            id_usuario_asignado,
            usuarios_asignados.nombre_usuario as usuario_asignado,
            ItemsPedidos.fecha_hora_inicio_preparacion as fecha_hora_inicio_preparacion,
            ItemsPedidos.fecha_hora_fin_preparacion as fecha_hora_fin_preparacion,
            ItemsPedidos.tiempo_estimado as tiempo_estimado,
            itemspedidos.estado as estado

            cabeceraspedidos.nombre_cliente as nombre_cliente,
            cabeceraspedidos.codigo_amigable as codigo_amigable,

            FROM ItemsPedidos
            LEFT JOIN articulos on articulos.id_articulo = itemspedidos.id_articulo
            LEFT JOIN usuarios as usuarios_asignados on usuarios_asignados.id_usuario = itemspedidos.id_usuario_asignado
            LEFT JOIN usuarios as usuarios_creadores on usuarios_creadores.id_usuario = itemspedidos.id_usuario_creador
            LEFT JOIN cabeceraspedidos on cabeceraspedidos.id_pedido = itemspedidos.id_pedido
            LEFT JOIN sectores on sectores.id_sector = articulos.id_sector
            WHERE itemspedidos.estado = 2 -- EN PREPARACION
            AND id_usuario_asignado = :id_usuario_asignado";

        public const TraerUno = "SELECT
                        itemspedidos.id_item_pedido as id_item_pedido,
                        itemspedidos.fecha_hora_creacion as fecha_hora_creacion,
                        itemsPedidos.id_pedido as id_pedido,
                        itemspedidos.id_articulo as id_articulo,
                        articulos.descripcion as descripcion_articulo,
                        itemspedidos.cantidad as cantidad,
                        articulos.importe as importe,
                        articulos.id_sector as id_sector,
                        sectores.descripcion as descripcion_sector,

                        itemspedidos.id_usuario_creador as id_usuario_creador,
                        usuarios_creadores.nombre_usuario as usuario_creador,
                        IFNULL(itemspedidos.id_usuario_asignado, '-') as id_usuario_asignado,
                        usuarios_asignados.nombre_usuario as usuario_asignado,
                        ItemsPedidos.fecha_hora_inicio_preparacion as fecha_hora_inicio,
                        ItemsPedidos.fecha_hora_fin_preparacion as fecha_hora_fin,
                        ItemsPedidos.tiempo_estimado as tiempo_estimado,
                        itemspedidos.estado as estado,

                        cabeceraspedidos.nombre_cliente as nombre_cliente,
                        cabeceraspedidos.id_mesa as id_mesa,
                        cabeceraspedidos.codigo_amigable as codigo_amigable,
                        mesas.codigo_amigable as codigo_amigable_mesa,
                        mesas.estado as estado_mesa

                        FROM ItemsPedidos

                        LEFT JOIN articulos on articulos.id_articulo = itemspedidos.id_articulo
                        LEFT JOIN usuarios as usuarios_asignados on usuarios_asignados.id_usuario = itemspedidos.id_usuario_asignado
                        LEFT JOIN usuarios as usuarios_creadores on usuarios_creadores.id_usuario = itemspedidos.id_usuario_creador
                        LEFT JOIN cabeceraspedidos on cabeceraspedidos.id_pedido = itemspedidos.id_pedido
                        LEFT JOIN sectores on sectores.id_sector = articulos.id_sector
                        LEFT JOIN Mesas on Mesas.id_mesa = cabeceraspedidos.id_mesa
                        
                        WHERE itemspedidos.estado != 0 
                        AND itemspedidos.id_item_pedido = :id_item_pedido

                        LIMIT 1";

}
