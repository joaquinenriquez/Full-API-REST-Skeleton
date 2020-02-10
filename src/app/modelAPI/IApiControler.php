<?php 
use Slim\Http\Request;
use Slim\Http\Response;

interface IApiController{ 
   	//public function TraerUno($request, $response, $args); 
   	public function TraerTodos(Request $request, Response $response, $args); 
   	public function CargarUno(Request $request, Response $response, $args);
   	public function BorrarUno(Request $request, Response $response, $args);
   	public function ModificarUno(Request $request, Response $response, $args);

}