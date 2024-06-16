<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\VehicleController;
use App\Middleware\AuthJwt;

return function ($app) {
    // Rotas sem JWT
    $app->get('/api/vehicles', VehicleController::class . ':getAllVehicles');
    $app->get('/api/vehicle/model/{modelo}', VehicleController::class . ':getVehicleByModel');
    $app->get('/api/vehicle/{id}', VehicleController::class . ':getVehicleById');
    
    // Rotas protegidas com JWT
    $app->group('/api', function (RouteCollectorProxy $group) {
      $group->post('/vehicle/create', VehicleController::class . ':createVehicle');
      $group->put('/vehicle/update/{id}', VehicleController::class . ':updateVehicleById');
      $group->delete('/vehicle/delete/{id}', VehicleController::class . ':deleteVehicleById');
    })->add(new AuthJwt());
};