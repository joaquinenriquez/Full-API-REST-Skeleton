<?php


// $app->group('/reportes/pdf', function () {

//     $this->get('/logins', \ReporteLogins::class . ':ListarPDF');
//     $this->get('/logins2', \LogApi::class . ':TraerIniciosDeSesion');

// })->add(\AuthMiddleware::class . ':VerificarToken');

$app->group('/consultas/empleados', function () {

    $this->post('/logins', \LogApi::class . ':TraerIniciosDeSesion')
    ->add(LogMiddleware::class . ':VerificarParametrosFiltroFechas');

})->add(\AuthMiddleware::class . ':VerificarToken');


$app->group('/consultas/mesas', function () {

    $this->post('/masusadas', \MesaApi::class . ':TraerMesaMasUsada');
    $this->post('/menosusadas', \MesaApi::class . ':TraerMesaMenosUsada');
    $this->post('/masfacturo', \MesaApi::class . ':TraerMesaMasFacturo');
    $this->post('/menosfacturo', \MesaApi::class . ':TraerMesaMenosFacturo');
    $this->post('/mayorimporte', \MesaApi::class . ':TraerMesasMayorImporte');
    $this->post('/menorimporte', \MesaApi::class . ':TraerMesasMenorImporte');

})->add(\AuthMiddleware::class . ':VerificarToken');


?>