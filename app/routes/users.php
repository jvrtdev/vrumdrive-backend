<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;
use App\Middleware\AuthJwt;

return function ($app) {
    // Rotas sem JWT
    $app->post('/api/user/create', UserController::class . ':createUser');
    $app->post('/api/user/login', UserController::class . ':loginUser');
    $app->post('/teste', UserController::class . ':testToken');

    // Rotas protegidas com JWT
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->get('/user/{id}', UserController::class . ':getUserById');
        $group->get('/users', UserController::class . ':getUsers');
        $group->post('/address/create/{id}', UserController::class . ':createAddress');
        $group->put('/address/update/{id}', UserController::class . ':updateAddress');
        $group->put('/user/update/{id}', UserController::class . ':updateUser');
        $group->delete('/user/delete/{id}', UserController::class . ':deleteUser');
    })->add(new AuthJwt());
};