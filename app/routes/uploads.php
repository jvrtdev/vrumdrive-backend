<?php

use App\Controllers\UploadController;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;
use App\Middleware\AuthJwt;

return function ($app) {
    // Rotas sem JWT
    
    // Rotas protegidas com JWT
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->post('/user/upload/{id}', UploadController::class . ':uploadProfileImg');//{id} => id do usuario 
        
    })->add(new AuthJwt());
};