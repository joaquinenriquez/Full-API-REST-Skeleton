<?php

class QuerysSQL_Mesas 
{
    public const ActualizarEstado = "UPDATE mesas SET estado = :estado WHERE id_mesa = :id_mesa AND estado != 0";
    public const TraerTodas = "SELECT id_mesa, codigo_amigable, estado FROM mesas WHERE estado != 0";
    public const CargarUna = "INSERT INTO mesas (codigo_amigable, estado) VALUES (:codigo_amigable, :estado)";
    public const TraerUnaPorId = "SELECT id_mesa, codigo_amigable, estado FROM mesas WHERE estado != 0 AND id_mesa = :identificador_mesa LIMIT 1";
    public const TraerUnaPorCodigoAmigable = "SELECT id_mesa, codigo_amigable, estado FROM mesas WHERE estado != 0 AND codigo_amigable = :identificador_mesa LIMIT 1";

}


?>