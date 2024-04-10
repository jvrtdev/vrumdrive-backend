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

$app->post('/api/create_user', UserController::class . ':createUser');

$app->post('/api/login', UserController::class . ':loginUser');

$app->run();