<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\LogController;
use App\Middleware\AuthJwt;

return function ($app) {
    $app->group('/api', function (RouteCollectorProxy $group) {
      $group->get('/log', LogController::class . ':getAllLogs');
      $group->get('/log/filter/{value}', LogController::class . ':getFilterLogs');
      $group->get('/log/{id}', LogController::class . ':getLogsById');
      $group->post('/log/create', LogController::class . ':createLog');
    })->add(new AuthJwt());
};