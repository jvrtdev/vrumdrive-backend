<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;
use Middleware\JwtMiddleware;

return function ($app) {
    // Rotas sem JWT
    
    // Rotas protegidas com JWT
    $app->group('/api', function (RouteCollectorProxy $group) {
       
    })->add(new JwtMiddleware());
};