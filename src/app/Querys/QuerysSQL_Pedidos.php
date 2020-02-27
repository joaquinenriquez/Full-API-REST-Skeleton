<?php

class QuerysSQL_Pedidos 
{

    public const TraerPedidosTomadosPorUsuario =  "SELECT
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
            WHERE itemspedidos.estado = 2 -- EN PREPARACION
            AND id_usuario_asignado = :id_usuario_asignado";



}


?>