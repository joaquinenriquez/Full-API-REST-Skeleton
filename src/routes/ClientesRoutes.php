<?php

$app->group('/clientes', function () {

    $this->get('/mesas/{codigoAmigable}', \ItemPedidoApi::class . ':TraerPedidosParaCliente');
    $this->get('/pedidos/{codigoAmigable}', \ItemPedidoApi::class . ':TraerPedidosParaCliente');
    $this->get('/encuestas', \ArticuloApi::class . ':TraerUno');
    //->add(\ArticuloMiddleware::class . ':VerificarParametrosAltaArticulo')
});

?>