
<?php



// En este archivo guardamos todas las dependencias y variables globales
include_once '../entorno.php';
require_once '../src/app/modelAPI/SectorApi.php';
require_once '../src/app/modelAPI/ArticuloApi.php';
require_once '../src/app/modelAPI/UsuarioApi.php';
require_once '../src/app/modelAPI/MesaApi.php';
require_once '../src/app/model/CabeceraPedido.php';
require_once '../src/app/modelAPI/CabeceraPedidoApi.php';
require_once '../src/app/middleware/UsuarioMiddleware.php';
require_once '../src/app/middleware/SectorMiddleware.php';

require_once '../src/app/modelAPI/ItemPedidoApi.php';

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

$app->group('/mesas', function () {

    $this->get('', \MesaApi::class . ':TraerTodos');
    $this->get('/{id}', \MesaApi::class . ':TraerUno');
    $this->post('', \MesaApi::class . ':CargarUno');
    $this->delete('/{id}', \MesaApi::class . ':BorrarUno');
    $this->put('/{id}', \MesaApi::class . ':ModificarUno');
    $this->get('/abrirmesa/{id}', \MesaApi::class . ':AbrirMesa');

});

$app->group('/itempedido', function () {
    $this->get('/{id}', \ItemPedidoApi::class . ':CargarUno' );
    $this->get('', \ItemPedidoApi::class . ':TraerTodosLosPendientes');
    $this->post('/enpreparacion', \ItemPedidoApi::class . ':CambiarEstadoAEnpreparacion');
    $this->post('/listoparaservir', \ItemPedidoApi::class . ':CambiarEstadoAListoParaServir');
});


$app->group("/usuarios", function () {
    $this->post('', \UsuarioApi::class . ':CargarUno')
    ->add(\SectorMiddleware::class . ':VerificarSiExisteSector')
    ->add(\UsuarioMiddleware::class . ':VerificarParametrosAltaUsuario');

});



$app->group('/pruebas', function () {

    //$this->get('', \CabeceraPedido::class . ':GenerarCodigo');
$this->get('/{id}', \CabeceraPedidoApi::class . ':TraerPedidoPorMesa');
});



$app->run();



?>