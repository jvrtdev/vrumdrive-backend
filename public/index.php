<?php
declare(strict_types=1);

use App\Controllers\UserController;
use App\Controllers\VehicleController;
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
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

//rotas da aplicacao
(require __DIR__ . "/../app/routes/users.php")($app);
(require __DIR__ . "/../app/routes/vehicles.php")($app);
(require __DIR__ . "/../app/routes/bookings.php")($app);
(require __DIR__ . "/../app/routes/uploads.php")($app);
(require __DIR__ . "/../app/routes/log.php")($app);
(require __DIR__ . "/../app/routes/admin.php")($app);

$app->run();