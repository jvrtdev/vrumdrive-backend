<?php

use App\Controllers\UploadController;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;
use App\Middleware\AuthAdminJwt;
use App\Middleware\AuthJwt;

return function ($app) {
    // Rotas sem JWT
    
    // Rotas protegidas com JWT
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->post('/upload/user/{id}', UploadController::class . ':uploadProfileImg');//{id} => id do usuario         
    })->add(new AuthJwt());

    //rotas do admin
    $app->group('/api/admin', function (RouteCollectorProxy $group) {
        $group->post('/upload/vehicle/{id}', UploadController::class . ':uploadVehicleImg');//{id} => id do usuario         
    })->add(new AuthAdminJwt());
};