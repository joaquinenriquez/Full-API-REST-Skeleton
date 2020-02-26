<?php

$app->group('/sectores', function () {

    $this->get('', \SectorApi::class . ':TraerTodos');
    $this->get('/{id}', \SectorApi::class . ':TraerUno');
    $this->post('', \SectorApi::class . ':CargarUno');
    $this->delete('/{id}', \SectorApi::class . ':BorrarUno');
    
    $this->post('/modificar/{id}', \SectorApi::class . ':ModificarUno')
    ->add(\SectorMiddleware::class . ':VerificarParametrosModificarSector');

})->add(\AuthMiddleware::class . ':VerificarToken');

?>