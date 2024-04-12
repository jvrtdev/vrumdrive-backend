<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController 
{
  protected $userRepository;

  public function __construct() {
      $database = new Database;
      
      $this->userRepository = new UsersRepository($database);
  }

  public function createUser(Request $request, Response $response)
  {
      $columns = $this->userRepository->getColumns();

      $data = get_object_vars(json_decode($request->getBody()));

      //Verificação do cpf início --------------------------------------
      $cpf = preg_replace('/[^0-9]/', '', $data["cpf"]);

      if (strlen($cpf) != 11) 
      {
          $response->getBody()->write(json_encode(['message' => 'Failed to create user']));
          return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
      }

      if (preg_match('/(\d)\1{10}/', $cpf)) 
      {
          $response->getBody()->write(json_encode(['message' => 'Failed to create user']));
          return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
      }
      
      for ($i = 10, $soma = 0, $k = 0; $i > 1; $i--) 
      {
          $soma += $cpf[$k++] * $i;
      }
      
      $verificador1 = (($soma % 11) < 2) ? 0 : 11 - ($soma % 11);
      
      for ($i = 11, $soma = 0, $k = 0; $i > 1; $i--) 
      {
          $soma += $cpf[$k++] * $i;
      }
      
      $verificador2 = (($soma % 11) < 2) ? 0 : 11 - ($soma % 11);

      if ($verificador1 != $cpf[9] || $verificador2 != $cpf[10]) 
      {
          $response->getBody()->write(json_encode(['message' => 'Failed to create user']));
          return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
      }
      //Verificação do cpf fim --------------------------------------

      for($i = 1; $i < count($columns); $i++){
        $data_columns[$columns[$i]] = $data[$columns[$i]];
      }

      $result = $this->userRepository->createUser($data_columns);

      if ($result) {
        $response->getBody()->write(json_encode(['message' => 'User created successfully']));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
      }
      $response->getBody()->write(json_encode(['message' => 'Failed to create user']));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
  }
}