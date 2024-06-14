<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;
use App\Middleware\AuthJwt;

return function ($app) {
    // Rotas sem JWT
    $app->post('/api/user/create', UserController::class . ':createUser');
    $app->post('/api/user/login', UserController::class . ':loginUser');
    $app->post('/api/user/2FA/{id}', UserController::class . ':twoFactor');
    $app->post('/teste', UserController::class . ':testToken');

    // Rotas protegidas com JWT
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->get('/user/{id}', UserController::class . ':getUserById');
        $group->get('/users', UserController::class . ':getUsers');
        $group->put('/user/update/{id}', UserController::class . ':updateUserById');
        $group->delete('/user/delete/{id}', UserController::class . ':deleteUserById');
    })->add(new AuthJwt());
};