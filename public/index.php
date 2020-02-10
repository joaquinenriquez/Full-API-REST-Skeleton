
<?php



// En este archivo guardamos todas las dependencias y variables globales
include_once '../entorno.php';
require_once '../src/app/modelAPI/SectorApi.php';
require_once '../src/app/modelAPI/ArticuloApi.php';

//$settings = require __DIR__ . '/../src/settings.php';

$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);


$app->group('/sectores', function () {

    $this->get('', \SectorApi::class . ':TraerTodos');
    $this->get('/{id}', \SectorApi::class . ':TraerUno');
    $this->post('', \SectorApi::class . ':CargarUno');
    $this->delete('/{id}', \SectorApi::class . ':BorrarUno');
    $this->put('/{id}', \SectorApi::class . ':ModificarUno');

});

$app->group('/articulos', function () {

    $this->get('', \ArticuloApi::class . ':TraerTodos');
    $this->get('/{id}', \ArticuloApi::class . ':TraerUno');
    $this->post('', \ArticuloApi::class . ':CargarUno');
    $this->delete('/{id}', \ArticuloApi::class . ':BorrarUno');
    $this->put('/{id}', \ArticuloApi::class . ':ModificarUno');

});



$app->run();



?>