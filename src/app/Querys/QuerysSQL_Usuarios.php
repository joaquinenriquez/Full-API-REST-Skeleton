<?php

class QuerysSQL_Usuarios 
{
    public const CambiarEstado = "UPDATE usuarios SET estado = :estado WHERE id_usuario = :id_usuario AND estado != 0";
}


?>