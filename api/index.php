<?php
ob_start();

require __DIR__ . "/../vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;

/**
 * API ROUTES
 * index
 */
$route = new Router(url(), ":");
$route->namespace("Source\App\PoliApi");

//user
$route->group("/me");
$route->get("/", "Users:index");
$route->put("/", "Users:update");
$route->get("/agendamento/{status}", "Users:events");
$route->get("/historico", "Users:history");

//clients
$route->put("/forget", "Clients:forget");

$route->get("/filiais", "Filiais:index");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "errors" => [
            "type " => "endpoint_not_found",
            "message" => "Não foi possível processar a requisição"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

ob_end_flush();