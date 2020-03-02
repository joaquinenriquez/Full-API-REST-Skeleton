<?php

class QuerysSQL_Logs 
{
    public const CargarUna = "INSERT INTO logs (id_usuario, id_sector, accion, fecha_hora) VALUES (:id_usuario, :id_sector, :accion, :fecha_hora)";
}

?>