<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;

class UserController {
  
  public function createUser(Request $request, Response $response)
  {
      $database = new Database;
      
      $userRepository = new UsersRepository($database);

      $columns = $userRepository->getColumns();

      $data = $request->getParsedBody();

      for($i = 1; $i < count($columns); $i++){
        $data_columns[$i] = $data[$columns[$i]];
      }

      $result = $userRepository->createUser($data_columns);

      if ($result) {
        $response->getBody()->write(json_encode(['message' => 'User created successfully']));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
      }
      $response->getBody()->write(json_encode(['message' => 'Failed to create user']));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
  }

  public function loginUser(Request $request, Response $response) 
  {
    $database = new Database;
      
    $userRepository = new UsersRepository($database);

    $data = $request->getParsedBody();
    $login = $data['login'];
    $senha = $data['senha'];

    $user = $userRepository->getUser($login);

    if ($user && password_verify($senha, $user['password'])) {
      $tokenPayload = [
          'id' => $user['id'],
          'senha' => $user['senha'],
          'cpf' => $user['cpf']
      ];

      $secretKey = 'hai-oh-ri-tt-er' ; // Sua chave secreta para assinar o token

      $algorithm = 'HS256'; //algoritmo de codificação

      $token = JWT::encode($tokenPayload, $secretKey,$algorithm);

      $response->getBody()->write(json_encode(['token' => $token]));
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
  }
    $response->getBody()->write(json_encode(['message' => 'Failed to authenticate']));
    return $response->withStatus(401)->withHeader('Content-Type', 'application/json');

   
  }
}