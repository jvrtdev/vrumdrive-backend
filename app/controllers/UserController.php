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

      $data = $request->getParsedBody();

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