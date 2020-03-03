<?php

$app->group('/clientes', function () {

    $this->get('/mesas/{codigoAmigable}', \ItemPedidoApi::class . ':TraerPedidosParaCliente');
    $this->get('/pedidos/{identificadorPedido}', \CabeceraPedidoApi::class . ':TraerUnoPorIdOCodigoAmigable');
    $this->get('/encuestas', \CabeceraPedidoDAO::class . ':GuardarOpioniones');
    //->add(\ArticuloMiddleware::class . ':VerificarParametrosAltaArticulo')
});

?>