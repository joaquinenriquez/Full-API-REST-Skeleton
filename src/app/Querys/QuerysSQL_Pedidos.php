<?php

class QuerysSQL_Pedidos
{

    public const TraerTodosLosItemsPedidosActivos = "SELECT
        itemspedidos.id_item_pedido as id_item_pedido,
        itemsPedidos.id_pedido as id_pedido,
        itemspedidos.id_articulo as id_articulo,
        articulos.descripcion as descripcion_articulo,
        itemspedidos.cantidad as cantidad,
        articulos.id_sector as id_sector,
        sectores.descripcion as descripcion_sector,

        id_usuario_creador as id_usuario_creador,
        usuarios_creadores.nombre_usuario as usuario_creador,
        itemspedidos.id_usuario_asignado as id_usuario_asignado,
        usuarios_asignados.nombre_usuario as usuario_asignado,
        ItemsPedidos.fecha_inicio as fecha_hora_inicio,
        ItemsPedidos.fecha_fin as fecha_hora_fin,
        ItemsPedidos.tiempo_estimado as tiempo_estimado,
        itemspedidos.estado as estado,

        cabeceraspedidos.nombre_cliente as nombre_cliente,
        cabeceraspedidos.codigo_amigable as codigo_amigable,
        mesas.nro_mesa as nro_mesa,
        mesas.estado as estado_mesa

        FROM ItemsPedidos

        LEFT JOIN articulos on articulos.id_articulo = itemspedidos.id_articulo
        LEFT JOIN usuarios as usuarios_asignados on usuarios_asignados.id_usuario = itemspedidos.id_usuario_asignado
        LEFT JOIN usuarios as usuarios_creadores on usuarios_creadores.id_usuario = itemspedidos.id_usuario_creador
        LEFT JOIN cabeceraspedidos on cabeceraspedidos.id_pedido = itemspedidos.id_pedido
        LEFT JOIN sectores on sectores.id_sector = articulos.id_sector
        LEFT JOIN Mesas on Mesas.id_mesa = cabeceraspedidos.id_mesa
        WHERE itemspedidos.estado = 1 -- PENDIENTES";

    public const TraerPedidosTomadosPorUsuario = "SELECT
            itemspedidos.id_item_pedido,
            ItemsPedidos.id_pedido as id_pedido,
            itemspedidos.id_articulo as id_articulo,
            articulos.descripcion as descripcion_articulo,
            cantidad,
            articulos.id_sector as id_sector,
            sectores.descripcion as descripcion_sector,

            id_usuario_creador as id_usuario_creador,
            usuarios_creadores.nombre_usuario as usuario_creador,
            id_usuario_asignado,
            usuarios_asignados.nombre_usuario as usuario_asignado,
            ItemsPedidos.fecha_inicio as fecha_inicio,
            ItemsPedidos.fecha_fin as fecha_fin,
            ItemsPedidos.tiempo_estimado as tiempo_estimado
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

}
