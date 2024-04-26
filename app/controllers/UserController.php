<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\UsersRepository;
use App\Validation\Validate;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController 
{
  protected $userRepository;

  protected $validate;

  public function __construct() 
  {
      $database = new Database;
      
      $this->userRepository = new UsersRepository($database);

      $this->validate = new Validate();
  }

  public function createUser(Request $request, Response $response)
  {
      $data = get_object_vars(json_decode($request->getBody()));

      $erro = $this->validate->cpfValidator($data["cpf"]);
      if($erro != false)
      {
          $response->getBody()->write(json_encode(['message' => $erro]));
          return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
      }

      $result = $this->userRepository->createUser($data);

      if ($result) 
      {
        $response->getBody()->write(json_encode(['message' => 'User created successfully']));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
      }
      $response->getBody()->write(json_encode(['message' => 'Failed to create user']));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
  }
}