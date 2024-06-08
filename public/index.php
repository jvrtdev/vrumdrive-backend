<?php
declare(strict_types=1);

use App\Controllers\CarroController;
use App\Controllers\UserController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();

//autoriza a rota options 
$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

//funcao para habilitar o cors
$app->add(function ($request, $handler) {
  $response = $handler->handle($request);
  return $response
    ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/', CarroController::class . ':hello');

$app->get('/api/cars', CarroController::class . ':cars');

$app->get('/api/cars/{id}', CarroController::class . ':carsID');

$app->post('/api/vehicle/create', CarroController::class . ':createVehicle');

$app->delete('/api/vehicle/delete', CarroController::class . ':deleteVehicles');

$app->put('/api/vehicle/update/{id}', CarroController::class . ':updateVehicles');

$app->post('/api/user/create', UserController::class . ':createUser');

$app->post('/api/user/login', UserController::class . ':loginUser');

$app->post('/api/auth', UserController::class . ':authentication');

$app->delete('/api/user/delete', UserController::class . ':deleteUser');

$app->put('/api/user/update/{id}', UserController::class . ':updateUser');

$app->run();