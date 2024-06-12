<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\LogController;
use App\Middleware\AuthJwt;

return function ($app) {
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->get('/log', LogController::class . ':logUser');
      })->add(new AuthJwt());
};