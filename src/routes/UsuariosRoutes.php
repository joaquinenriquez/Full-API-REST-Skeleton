<?php

$app->group("/usuarios", function () {

    $this->post('/{idUsuario}/modificar', \UsuarioApi::class . ':ModificarUno')
        ->add(\UsuarioMiddleware::class . ':VerificarParametrosAltaUsuario')
        ->add(\AuthMiddleware::class . ':VerificarSiEsAdmin');

    $this->get('/actual', \UsuarioApi::class . ':TraerUsuarioActual');

    $this->patch('/{idUsuario}/suspender', \UsuarioApi::class . ':SuspenderUsuario');
    
    $this->patch('/{idUsuario}/activar', \UsuarioApi::class . ':ActivarUsuario');

    $this->get('/{idUsuario}', \UsuarioApi::class . ':TraerUno');

    $this->delete('/{idUsuario}', \UsuarioApi::class . ':BorrarUno');

    
    $this->post('', \UsuarioApi::class . ':CargarUno')
    ->add(\UsuarioMiddleware::class . ':VerificarParametrosAltaUsuario')
    ->add(\AuthMiddleware::class . ':VerificarSiEsAdmin');

    $this->get('', \UsuarioApi::class . ':TraerTodos');

})->add(LogMiddleWare::class . ':GuardarRegistro')
->add(\AuthMiddleware::class . ':VerificarToken');


?>