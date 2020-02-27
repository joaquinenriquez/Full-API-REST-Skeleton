<?php

$app->group("/usuarios", function () {


    $this->get('/actual', \UsuarioApi::class . ':TraerUsuarioActual');

    $this->patch('/{idUsuario}/suspender', \UsuarioApi::class . ':SuspenderUsuario');
    
    $this->patch('/{idUsuario}/activar', \UsuarioApi::class . ':ActivarUsuario');
    

    $this->get('/{idUsuario}', \UsuarioApi::class . ':TraerUno');

    $this->delete('/{idUsuario}', \UsuarioApi::class . ':BorrarUno');

    
    $this->post('', \UsuarioApi::class . ':CargarUno')
    ->add(\SectorMiddleware::class . ':VerificarSiExisteSector')
    ->add(\UsuarioMiddleware::class . ':VerificarParametrosAltaUsuario')
    ->add(\AuthMiddleware::class . ':VerificarSiEsAdmin');

    $this->get('', \UsuarioApi::class . ':TraerTodos');

})->add(\AuthMiddleware::class . ':VerificarToken');


?>