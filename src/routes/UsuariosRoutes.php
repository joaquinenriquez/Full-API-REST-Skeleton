<?php

$app->group("/usuarios", function () {

    $this->put('/{idUsuario}', \UsuarioApi::class . ':ModificarUno');

    $this->get('/actual', \UsuarioApi::class . ':TraerUsuarioActual');

    $this->patch('/{idUsuario}/suspender', \UsuarioApi::class . ':SuspenderUsuario');
    
    $this->patch('/{idUsuario}/activar', \UsuarioApi::class . ':ActivarUsuario');

    $this->get('/{idUsuario}', \UsuarioApi::class . ':TraerUno');

    $this->delete('/{idUsuario}', \UsuarioApi::class . ':BorrarUno');

    
    $this->post('', \UsuarioApi::class . ':CargarUno')
    ->add(\UsuarioMiddleware::class . ':VerificarParametrosAltaUsuario')
    ->add(\AuthMiddleware::class . ':VerificarSiEsAdmin');

    $this->get('', \UsuarioApi::class . ':TraerTodos');

})->add(\AuthMiddleware::class . ':VerificarToken');


?>