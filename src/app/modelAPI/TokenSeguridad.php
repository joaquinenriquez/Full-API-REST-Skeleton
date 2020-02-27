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
        $fechaVigenciaToken_claim = $fechaEmisionToken_claim; //+ 10; // nbf (Segundos)
        $fechaExpiracion_claim = $fechaEmisionToken_claim + 60000; // exp

        $token = array (
            "iss" => $server_claim,
            //"aud" => $audiencia_claim,
            "iat" => time(),
            //"nbf" => $fechaVigenciaToken_claim,
            "exp" => time() + 6000,
            "data" => $datos
            );

        $jwt = JWT::encode($token, TokenSeguridad::$keyToken, 'HS256');

        return $jwt;
    }

    public static function VerificarToken($token) {

        $auxReturn = false;
        $ubicacionParaMensaje = "";

        if (empty($token)) {
            $auxReturn = new Resultado(true, "El token esta vacio", EstadosError::ERROR_DE_AUTORIZACION);
        } else {
            try{
                $decoded = JWT::decode($token, TokenSeguridad::$keyToken, array('HS256'));

                

                $auxReturn = new Resultado(false, $decoded->data, EstadosError::OK);
            } catch (Exception $unError) 
            {
                $auxReturn = new Resultado(true, "No se pudo validar el token: " . $unError->getMessage(),EstadosError::ERROR_DE_AUTORIZACION);
            }
        }
        
        return $auxReturn;
    }

    public static function getIdUsuarioActual($token){
        $auxReturn = self::VerificarToken($token);
        if ($auxReturn->getIsError() == false ){
            $auxReturn = new Resultado(false, $auxReturn->getMessage()["id_usuario"], EstadosError::OK);
        }
    }

    public static function getRolUsuarioActual($token) {

    }



}


?>