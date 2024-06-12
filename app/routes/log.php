<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\LogController;
use App\Middleware\AuthJwt;

return function ($app) {
    $app->get('/api/log', LogController::class . ':logUser');
};