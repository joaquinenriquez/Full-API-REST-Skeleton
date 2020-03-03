<?php
// En este archivo guardamos todas las dependencias y variables globales
include_once './incluime.php';

$settings = require __DIR__ . './settings.php';

$app = new \Slim\App($settings);
$app = new \Slim\App();

require_once './src/routes/MesasRoutes.php';
require_once './src/routes/ItemPedidoRoutes.php';
require_once './src/routes/ArticulosRoutes.php';
require_once './src/routes/UsuariosRoutes.php';
require_once './src/routes/SectoresRoutes.php';
require_once './src/routes/ClientesRoutes.php';
require_once './src/routes/ReportesRoutes.php';

$app->group("/login", function () {

    $this->post('', \LoginApi::class . ':Login')->add(\LoginMiddleWare::class . ':VerificarParametrosLogin');
});

$app->run();


?>