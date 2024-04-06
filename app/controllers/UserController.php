<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
  
  public function createUser(Request $request, Response $response)
  {
      $database = new Database;
      
      $userRepository = new UsersRepository($database);

      $data = $request->getParsedBody();
      $nome = $data['nome'];
      $data_nasc = $data['data_nasc'];
      $sexo = $data['sexo'];
      $nome_mat = $data['nome_mat'];
      $cpf = $data['cpf'];
      $email = $data['email'];
      $celular = $data['celular'];
      $telefone = $data['telefone'];
      $cep = $data['cep'];
      $estado = $data['estado'];
      $cidade = $data['cidade'];
      $rua = $data['rua'];
      $logradouro = $data['logradouro'];
      $login = $data['login'];
      $senha = $data['senha'];

      $result = $userRepository->createUser($nome, $data_nasc, $sexo, $nome_mat, $cpf, $email, $celular, $telefone, $cep, $estado, $cidade, $rua, $logradouro, $login, $senha);

      if ($result) {
        $response->getBody()->write(json_encode(['message' => 'User created successfully']));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
      }
      $response->getBody()->write(json_encode(['message' => 'Failed to create user']));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
  }
}