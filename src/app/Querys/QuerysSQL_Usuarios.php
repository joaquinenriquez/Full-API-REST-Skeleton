<?php

class QuerysSQL_Usuarios 
{

    public const CambiarEstado = "UPDATE usuarios SET estado = :estado WHERE id_usuario = :id_usuario AND estado != 0";
    public const TraerUsuarioPorNombreUsuario = "SELECT id_usuario, nombre_usuario, nombre, apellido, id_rol, estado FROM usuarios WHERE estado != 0 AND nombre_usuario = :nombre_usuario LIMIT 1";
    public const Login = "SELECT id_usuario, nombre_usuario, password, nombre, apellido, id_rol, estado FROM usuarios WHERE estado != 0 AND nombre_usuario = :nombre_usuario";
    public const ModificarUno = "UPDATE usuarios SET nombre_usuario=:nombre_usuario, password=:password, nombre=:nombre, apellido=:apellido, id_rol=:id_rol WHERE id_usuario = :id_usuario";
    
}


?>