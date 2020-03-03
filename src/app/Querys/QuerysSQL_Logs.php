<?php

class QuerysSQL_Logs 
{
    public const CargarUna = "INSERT INTO logs (id_usuario, id_sector, id_accion, descripcion_accion, fecha_hora) VALUES (:id_usuario, :id_sector, :id_accion, :descripcion_accion, :fecha_hora)";
    
    
    
    public const TraerIniciosDeSesion = "SELECT 
                                            id_registro as id_registro, 
                                            DATE_FORMAT(date(fecha_hora),'%d/%m/%Y')  as fecha, 
                                            time(fecha_hora) as hora, 
                                            logs.id_usuario as id_usuario, 
                                            usuarios.nombre_usuario as nombre_usuario, 
                                            usuarios.id_rol as id_rol, 
                                            logs.id_accion as id_accion, 
                                            logs.descripcion_accion as descripcion_accion
                                        FROM logs
                                        LEFT JOIN usuarios on usuarios.id_usuario = logs.id_usuario
                                        WHERE id_accion = 1 -- Inicio de sesion correcto
                                        AND logs.fecha_hora BETWEEN :fecha_hora_desde AND :fecha_hora_hasta";

}

?>