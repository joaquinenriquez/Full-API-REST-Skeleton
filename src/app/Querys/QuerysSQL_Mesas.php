<?php

class QuerysSQL_Mesas 
{
    public const ActualizarEstado = "UPDATE mesas SET estado = :estado WHERE id_mesa = :id_mesa AND estado != 0";
    public const TraerTodas = "SELECT id_mesa, codigo_amigable, estado FROM mesas WHERE estado != 0";
    public const CargarUna = "INSERT INTO mesas (codigo_amigable, estado) VALUES (:codigo_amigable, :estado)";
    public const TraerUnaPorId = "SELECT id_mesa, codigo_amigable, estado FROM mesas WHERE estado != 0 AND id_mesa = :identificador_mesa LIMIT 1";
    public const TraerUnaPorCodigoAmigable = "SELECT id_mesa, codigo_amigable, estado FROM mesas WHERE estado != 0 AND codigo_amigable = :identificador_mesa LIMIT 1";
    
    public const TraerMesaMasUsada = "SELECT cabeceraspedidos.id_mesa, mesas.codigo_amigable, COUNT(cabeceraspedidos.id_mesa) AS cantidad_operaciones
                                        FROM cabeceraspedidos
                                        LEFT JOIN mesas on mesas.id_mesa = cabeceraspedidos.id_mesa
                                        WHERE cabeceraspedidos.fecha_inicio between :fecha_hora_desde and :fecha_hora_hasta
                                        GROUP BY cabeceraspedidos.id_mesa
                                        ORDER BY COUNT(cabeceraspedidos.id_mesa) DESC"; // -- LIMIT 1";

    public const TraerMesasMenosUsadas = "SELECT cabeceraspedidos.id_mesa, mesas.codigo_amigable, COUNT(cabeceraspedidos.id_mesa) AS cantidad_operaciones
                                            FROM cabeceraspedidos
                                            LEFT JOIN mesas on mesas.id_mesa = cabeceraspedidos.id_mesa
                                            WHERE cabeceraspedidos.fecha_inicio between :fecha_hora_desde and :fecha_hora_hasta
                                            GROUP BY cabeceraspedidos.id_mesa
                                            ORDER BY COUNT(cabeceraspedidos.id_mesa) ASC -- LIMIT 1";

    public const TraerMesasQueMasFacturaron = "SELECT cabeceraspedidos.id_mesa, mesas.codigo_amigable, SUM(cabeceraspedidos.importe) AS importe_total
                                                    FROM cabeceraspedidos
                                                    LEFT JOIN mesas on mesas.id_mesa = cabeceraspedidos.id_mesa
                                                    WHERE cabeceraspedidos.fecha_inicio between :fecha_hora_desde and :fecha_hora_hasta
                                                    GROUP BY cabeceraspedidos.id_mesa
                                                    ORDER BY SUM(cabeceraspedidos.importe) DESC -- LIMIT 1";

    public const TraerMesasQueMenosFacturaron = "SELECT cabeceraspedidos.id_mesa, mesas.codigo_amigable, SUM(cabeceraspedidos.importe) AS importe_total
                                                    FROM cabeceraspedidos
                                                    LEFT JOIN mesas on mesas.id_mesa = cabeceraspedidos.id_mesa
                                                    WHERE cabeceraspedidos.fecha_inicio between :fecha_hora_desde and :fecha_hora_hasta
                                                    GROUP BY cabeceraspedidos.id_mesa
                                                    ORDER BY SUM(cabeceraspedidos.importe) ASC -- LIMIT 1";

    public const TraerMesasConMayorImporte = "SELECT cabeceraspedidos.id_mesa, mesas.codigo_amigable, MAX(cabeceraspedidos.importe) AS importe
                                                    FROM cabeceraspedidos
                                                    LEFT JOIN mesas on mesas.id_mesa = cabeceraspedidos.id_mesa
                                                    WHERE cabeceraspedidos.fecha_inicio between :fecha_hora_desde and :fecha_hora_hasta
                                                    GROUP BY cabeceraspedidos.id_mesa
                                                    ORDER BY MAX(cabeceraspedidos.importe) DESC";

    public const TraerMesasConMenorImporte = "SELECT cabeceraspedidos.id_mesa, mesas.codigo_amigable, MIN(cabeceraspedidos.importe) AS importe
                                                    FROM cabeceraspedidos
                                                    LEFT JOIN mesas on mesas.id_mesa = cabeceraspedidos.id_mesa
                                                    WHERE cabeceraspedidos.fecha_inicio between :fecha_hora_desde and :fecha_hora_hasta
                                                    GROUP BY cabeceraspedidos.id_mesa
                                                    ORDER BY MIN(cabeceraspedidos.importe) ASC";


}


?>