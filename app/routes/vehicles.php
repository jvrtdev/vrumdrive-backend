<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\VehicleController;
use App\Middleware\AuthJwt;

return function ($app) {
    // Rotas sem JWT
    $app->get('/api/vehicles', VehicleController::class . ':getAllVehicles');
    $app->get('/api/vehicle/model/{modelo}', VehicleController::class . ':getVehicleByModel');
    $app->get('/api/vehicle/{id}', VehicleController::class . ':getVehicleById');
    $app->get('/api/images/vehicle/{modelo}', VehicleController::class . ':getImagesByModel');
};