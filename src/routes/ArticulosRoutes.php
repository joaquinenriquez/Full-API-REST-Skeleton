<?php

$app->group('/articulos', function () {

    $this->get('', \ArticuloApi::class . ':TraerTodos');
    $this->get('/{id}', \ArticuloApi::class . ':TraerUno');
    
    $this->post('', \ArticuloApi::class . ':CargarUno')
    ->add(\ArticuloMiddleware::class . ':VerificarParametrosAltaArticulo')
    ->add(\AuthMiddleware::class . ':VerificarSiEsAdmin');
    
    $this->delete('/{id}', \ArticuloApi::class . ':BorrarUno')
    ->add(\ArticuloMiddleware::class . ':VerificarParametrosBorrarArticulo')
    ->add(\AuthMiddleware::class . ':VerificarSiEsAdmin');
    
    $this->post('/modificar/{id}', \ArticuloApi::class . ':ModificarUno')
    ->add(\ArticuloMiddleware::class . ':VerificarParametrosModificarArticulo')
    ->add(\AuthMiddleware::class . ':VerificarSiEsAdmin');


})->add(\AuthMiddleware::class . ':VerificarToken');

?>