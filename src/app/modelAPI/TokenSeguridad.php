<?php

use Firebase\JWT\JWT;

class TokenSeguridad 
{
    private static $keyToken = "12345";

    public static function CrearUno($datos)
    {
        $server_claim = "YO"; // iss
        $audiencia_claim = "AUDIENCIA"; // aud
        $fechaEmisionToken_claim = time(); // iat
        $fechaVigenciaToken_claim = $fechaEmisionToken_claim + 10; // nbf (Segundos)
        $fechaExpiracion_claim = $fechaEmisionToken_claim + 60; // exp

        $token = array (
            "iss" => $server_claim,
            "aud" => $audiencia_claim,
            "iat" => $fechaEmisionToken_claim,
            "nbf" => $fechaVigenciaToken_claim,
            "exp" => $fechaVigenciaToken_claim,
            "data" => $datos
            );

        $jwt = JWT::encode($token, TokenSeguridad::$keyToken);

        return $jwt;
    }
}


?>