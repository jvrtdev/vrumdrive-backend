<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\UsersRepository;
use App\Validation\AuthUser;
use App\Validation\Validate;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController 
{
  protected $userRepository;

  protected $validate;

  protected $auth;

  public function __construct() 
  {
      $database = new Database;
      
      $this->userRepository = new UsersRepository($database);

      $this->validate = new Validate();
      
      $this->auth = new AuthUser('vrumvrumdrivetopdemais');
  }

  public function createUser(Request $request, Response $response)
  {
      $columns = $this->userRepository->getColumns();

      $data = get_object_vars(json_decode($request->getBody()));

      $erro = $this->validate->cpfValidator($data["cpf"]);
      if($erro)
      {
          $response->getBody()->write(json_encode(['message' => $erro]));
          return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
      }

      for($i = 1; $i < count($columns); $i++)
      {
        $data_columns[$columns[$i]] = $data[$columns[$i]];
      }

      $result = $this->userRepository->createUser($data_columns);

      if ($result) 
      {
        $response->getBody()->write(json_encode(['message' => 'User created successfully']));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
      }
      $response->getBody()->write(json_encode(['message' => 'Failed to create user']));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
  }

  public function loginUser(Request $request, Response $response) 
  {
    $body = $request->getBody();
    $data = json_decode($body);
    $login = $data->login;
    $senha = $data->senha;
    
    $user = $this->userRepository->getUser($login, $senha);
    
    if($user) {
      $userData = $user[0];//acessa a primeira posicao do array que contem um objeto
      
      $token = $this->auth->createToken($userData);
      
      // Retorna o token JWT no cabeçalho de autorização
      $response = $response->withHeader('Authorization', $token);
      
      // Retorne o token JWT na resposta
      $response->getBody()->write(json_encode(['token' => $token]));
       
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    };
    $response->getBody()->write(json_encode(['message' => 'Failed to authenticate']));
    return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
  }
  
  public function authentication(Request $request, Response $response)
  {
    $autorizationHeader = $request->getHeaderLine('Authorization');

    $authToken = $this->auth->authToken($autorizationHeader);

    if($authToken){
      $response->getBody()->write(json_encode("Usuario autorizado!"));
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(['message' => 'Failed to authenticate']));
    return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
  }
  
  public function getBookings(Request $request, Response $response)
  {
    
  }
}