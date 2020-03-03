<?php

class QuerysSQL_CabecerasPedidos 
{
    public const CargarUno = "INSERT INTO cabeceraspedidos (id_usuario, nombre_cliente, estado, codigo_amigable, id_mesa, foto, fecha_inicio, fecha_fin)
                                VALUES (:id_usuario, :nombre_cliente, :estado, :codigo_amigable, :id_mesa, :foto, :fecha_inicio, :fecha_fin)";

    public const TraerPedidoPorMesa =  "SELECT id_pedido FROM cabeceraspedidos WHERE (estado != 0 AND estado != 1) AND id_mesa = :id_mesa LIMIT 1";
    public const CerrarPedido = "UPDATE cabeceraspedidos SET estado = :estado, fecha_fin = :fecha_fin WHERE id_pedido = :id_pedido AND estado != 0";

    public const ActualizarImporte = "UPDATE cabeceraspedidos SET importe = :importe WHERE id_pedido = :id_pedido AND estado != 0";
    
}

?>