<?php


$app->group('/mesas', function () {

    $this->get('', \MesaApi::class . ':TraerTodos');

    $this->get('/{id}', \MesaApi::class . ':TraerUno');

    $this->post('', \MesaApi::class . ':CargarUno')
    ->add(\MesaMiddleware::class . ':VerificarParametrosAltaMesa');


    $this->delete('/{nroMesa}', \MesaApi::class . ':BorrarUno');

    $this->put('/{id}', \MesaApi::class . ':ModificarUno');

    $this->post('/{nroMesa}/abrir', \MesaApi::class . ':AbrirMesa')
       ->add(\MesaMiddleware::class . ":VerificarParametrosAbrirMesa")
       ->add(\AuthMiddleware::class . ':VerificarSiPuedeCrearPedidos');


})->add(\AuthMiddleware::class . ':VerificarToken');


?>