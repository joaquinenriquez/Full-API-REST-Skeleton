<?php

$app->group('/mesas', function () {

    $this->get('', \MesaApi::class . ':TraerTodos');

    $this->post('', \MesaApi::class . ':CargarUno');

    $this->put('/{identificadorMesa}', \MesaApi::class . ':ModificarUno');

    $this->get('/{identificadorMesa}', \MesaApi::class . ':TraerUno');

    $this->delete('/{identificadorMesa}', \MesaApi::class . ':BorrarUno');

    $this->post('/{identificadorMesa}/abrir', \MesaApi::class . ':AbrirMesa')
       ->add(\MesaMiddleware::class . ":VerificarParametrosAbrirMesa")
       ->add(\AuthMiddleware::class . ':VerificarSiPuedeCrearPedidos');

    $this->patch('/{identificadorMesa}/cerrar', \MesaApi::class . ':CerrarMesa');
    $this->patch('/{identificadorMesa}/pagar', \MesaApi::class . ':PagarMesa');
    

})->add(\AuthMiddleware::class . ':VerificarToken');


?>