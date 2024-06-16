<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\LogController;
use App\Middleware\AuthAdminJwt;

return function ($app) {
    $app->group('/api', function (RouteCollectorProxy $group) {
      $group->get('/admin/log', LogController::class . ':getAllLogs');
      $group->get('/admin/log/filter/{value}', LogController::class . ':getFilterLogs');
      $group->get('/admin/log/{id}', LogController::class . ':getLogsById');
      $group->post('/admin/log/create', LogController::class . ':createLog');
    })->add(new AuthAdminJwt());
};