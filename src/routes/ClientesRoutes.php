<?php

$app->group('/clientes', function () {

    $this->get('/mesas/{codigoAmigable}', \ItemPedidoApi::class . ':TraerPedidosParaCliente');
    $this->get('/pedidos/{identificadorPedido}', \CabeceraPedidoApi::class . ':TraerUnoPorIdOCodigoAmigable');
    $this->post('/encuestas/{identificadorPedido}', \CabeceraPedidoApi::class . ':Contestar');
    //->add(\ArticuloMiddleware::class . ':VerificarParametrosAltaArticulo')
});

?>