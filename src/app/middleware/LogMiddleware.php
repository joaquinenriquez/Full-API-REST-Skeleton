<?php

use Slim\Http\Request;
use Slim\Http\Response;

class LogMiddleware 
{
    public static function GuardarRegistro (Request $request, Response $response, $next)
    {
        var_dump("dsda");
        return $response;
    }
}


?>