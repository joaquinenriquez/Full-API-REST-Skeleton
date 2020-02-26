<?php

$app->group("/usuarios", function () {


    $this->get('/actual', \UsuarioApi::class . ':TraerUsuarioActual');

    $this->get('/{idUsuario}', \UsuarioApi::class . ':TraerUno');



    
    $this->post('', \UsuarioApi::class . ':CargarUno')
    ->add(\SectorMiddleware::class . ':VerificarSiExisteSector')
    ->add(\UsuarioMiddleware::class . ':VerificarParametrosAltaUsuario')
    ->add(\AuthMiddleware::class . ':VerificarSiEsAdmin');


})->add(\AuthMiddleware::class . ':VerificarToken');


?>