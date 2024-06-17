<?php

use App\Controllers\BookingController;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;
use App\Middleware\AuthJwt;

return function ($app) {
    // Rotas sem JWT
    $app->post("/api/booking/price", BookingController::class . ':getTotalPriceBooking');
    // Rotas protegidas com JWT
    $app->group('/api', function (RouteCollectorProxy $group) {
       $group->post("/booking/create", BookingController::class . ':createNewBooking');
    })->add(new AuthJwt());
};