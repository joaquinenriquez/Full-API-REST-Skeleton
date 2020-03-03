<?php
    
class Acciones  
{
    public const INICIO_SESION_CORRECTO = [1, "Inicio de sesion correcto"];
    public const ERROR_PASSWORD_INICIO_SESION = [2, "Inicio de sesion incorrecto: password incorrecto"];
    public const ERROR_INICIO_SESION_USUARIO_DESCONOCIDO = [3, "Inicio de sesion incorrecto: nombre de usuario desconocido"];
    public const ERROR_INICIO_SESION_USUARIO_SUSPENDIDO = [4, "Inicio de sesion incorrecto: el usuario esta suspendido"];
    public const ERROR_INICIO_SESION_USUARIO_DESHABILITADO = [5, "Inicio de sesion incorrecto: el usuario esta deshabilitado"];    
}

?>