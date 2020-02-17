<?php

require_once '../src/app/modelAPI/AutentificadorJWT.php';

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware 
{
    public function VerificarToken(Request $request, Response $response, $next) {
        $token = $request->getHeader("Authorization");
        
        try {
            AutentificadorJWT::VerificarToken($token);
        } catch (Exception $error) {
            echo $error->getMessage();
        }

        return $next($request, $response);

    }
}


?>