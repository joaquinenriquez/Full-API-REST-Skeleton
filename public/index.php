
<?php

// En este archivo guardamos todas las dependencias y variables globales
include_once '../entorno.php';
require_once '../src/app/modelAPI/SectorApi.php';
require_once '../src/app/modelAPI/ArticuloApi.php';
require_once '../src/app/modelAPI/UsuarioApi.php';
require_once '../src/app/modelAPI/MesaApi.php';
require_once '../src/app/modelAPI/LoginApi.php';
require_once '../src/app/model/CabeceraPedido.php';
require_once '../src/app/modelAPI/CabeceraPedidoApi.php';
require_once '../src/app/middleware/UsuarioMiddleware.php';
require_once '../src/app/middleware/SectorMiddleware.php';
require_once '../src/app/middleware/LoginMiddleware.php';
require_once '../src/app/middleware/AuthMiddleware.php';
require_once '../src/app/middleware/ItemPedidoMiddleware.php';
require_once '../src/app/middleware/MesaMiddleware.php';
require_once '../src/app/middleware/ArticuloMiddleware.php';

require_once '../src/app/modelAPI/ItemPedidoApi.php';

$settings = require __DIR__ . '/../src/settings.php';

$app = new \Slim\App($settings);
require '../src/routes/MesasRoutes.php';
require '../src/routes/ItemPedidoRoutes.php';
require '../src/routes/ArticulosRoutes.php';
require '../src/routes/UsuariosRoutes.php';
require '../src/routes/SectoresRoutes.php';
require '../src/routes/ClientesRoutes.php';

$app->group("/login", function () {

    $this->post('', \LoginApi::class . ':Login')->add(\LoginMiddleWare::class . ':VerificarParametrosLogin');

});


$app->run();

?>