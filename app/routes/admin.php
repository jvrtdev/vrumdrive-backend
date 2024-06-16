<?php

use App\Controllers\UploadController;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;
use App\Middleware\AuthAdminJwt;

return function ($app) {
    $app->group('/api/admin', function (RouteCollectorProxy $group) {
      // rotas do usuario
      $group->get('/users', UserController::class . ':getUsers');
      $group->delete('/user/delete/{id}', UserController::class . ':deleteUserById');
      $group->get('/user/{id}', UserController::class . ':getUserById');

      // rotas dos veículos
      $group->post('/upload/vehicle/{id}', UploadController::class . ':uploadVehicleImages');

    })->add(new AuthAdminJwt());
    
};