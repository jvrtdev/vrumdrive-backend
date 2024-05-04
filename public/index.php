<?php
declare(strict_types=1);

use App\Controllers\CarroController;
use App\Controllers\UserController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();

$app->get('/', CarroController::class . ':hello');

$app->get('/api/cars', CarroController::class . ':cars');

$app->get('/api/cars/{id}', CarroController::class . ':carsID');

$app->post('/api/vehicle/create', CarroController::class . ':createVehicle');

$app->delete('/api/vehicle/delete', CarroController::class . ':deleteVehicles');

$app->put('/api/vehicle/update', CarroController::class . ':updateVehicles');

$app->post('/api/user/create', UserController::class . ':createUser');

$app->post('/api/user/login', UserController::class . ':loginUser');

$app->post('/api/auth', UserController::class . ':authentication');

$app->delete('/api/user/delete', UserController::class . ':deleteUser');

$app->put('/api/user/update', UserController::class . ':updateUser:');

$app->run();