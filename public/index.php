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

$app->get('/api/carros', CarroController::class . ':carros');

$app->get('/api/carros/{id}', CarroController::class . ':carros_id');

$app->post('/api/vehicle/create', CarroController::class . ':createVehicle');

$app->post('/api/user/create', UserController::class . ':createUser');

$app->post('/api/user/login', UserController::class . ':loginUser');

$app->post('/api/auth', UserController::class . ':authentication');

$app->delete('/api/user/delete', UserController::class . ':deleteUser');

$app->run();