<?php

$app->group('/mesas', function () {

    $this->get('', \MesaApi::class . ':TraerTodos');

    $this->post('', \MesaApi::class . ':CargarUno')
    ->add(\MesaMiddleware::class . ':VerificarParametrosAltaMesa');

    $this->put('/{nroMesa}', \MesaApi::class . ':ModificarUno');

    $this->get('/{nroMesa}', \MesaApi::class . ':TraerUno');

    $this->delete('/{nroMesa}', \MesaApi::class . ':BorrarUno');

    $this->post('/{nroMesa}/abrir', \MesaApi::class . ':AbrirMesa')
       ->add(\MesaMiddleware::class . ":VerificarParametrosAbrirMesa")
       ->add(\AuthMiddleware::class . ':VerificarSiPuedeCrearPedidos');


})->add(\AuthMiddleware::class . ':VerificarToken');


?>