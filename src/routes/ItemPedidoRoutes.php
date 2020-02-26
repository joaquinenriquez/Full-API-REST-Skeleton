<?php


$app->group('/itempedido', function () {

    $this->get('/pendientes', \ItemPedidoApi::class . ':TraerTodosLosPendientes');
    $this->get('/pendientesporsector/{idSector}', \ItemPedidoApi::class . ':TraerPendientesPorSector');
    $this->get('/pendientesusuarioactual', \ItemPedidoApi::class . ':TraerPedidosPendientesRolActual');
    
    $this->post('/cargaruno/{nroMesa}', \ItemPedidoApi::class . ':CargarUno' )
        ->add(\AuthMiddleware::class . ':VerificarSiPuedeCrearPedidos');

    $this->post('/cargarvarios/{nroMesa}', \ItemPedidoApi::class . ':CargarVarios')
        ->add(\ItemPedidoMiddleware::class . ':VerificarParametrosAltaVariosItemPedido')
        ->add(\AuthMiddleware::class . ':VerificarSiPuedeCrearPedidos');

    $this->post('/{idItemPedido}/tomar', \ItemPedidoApi::class . ':TomarItemPedido')
        ->add(\ItemPedidoMiddleware::class . ':VerificarParametrosTomarPedido');    

    $this->get('/{idItemPedido}', \ItemPedidoApi::class . ':TraerUno');

    //$this->post('/enpreparacion', \ItemPedidoApi::class . ':CambiarEstadoAEnpreparacion');
    //$this->post('/listoparaservir', \ItemPedidoApi::class . ':CambiarEstadoAListoParaServir');
})->add(\AuthMiddleware::class . ':VerificarToken');

?>