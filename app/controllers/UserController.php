<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\UserRepository;
use App\Validation\AuthUser;
use App\Validation\Validate;
use PDOException;
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
      
      $this->userRepository = new UserRepository($database);

      $this->validate = new Validate();
      
      $this->auth = new AuthUser('vrumvrumdrivetopdemais');
  }

  public function getUsers(Request $request, Response $response) 
  {
      try{
        $data = $this->userRepository->getAllUsers();

        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }
    catch(PDOException $e)
    {
        $response->getBody()->write(json_encode($e->getMessage()));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
  }

  public function getUserById(Request $request, Response $response, $args)
  {
    try{
      $data = $this->userRepository->getUserById($args['id']);
      
      $body = json_encode($data); 

      $response->getBody()->write($body);
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
    catch(PDOException $e)
    {
        $response->getBody()->write(json_encode($e->getMessage()));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
  }

  public function createAddress(Request $request, Response $response, $args)
  {
      $data = get_object_vars(json_decode($request->getBody()));

      try{
        $this->userRepository->createAddress($data, $args);
        $response->getBody()->write(json_encode(['message' => 'User created successfully']));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
      }
      catch(PDOException $e)
      {
        $response->getBody()->write(json_encode($e->getMessage()));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
      }
  }

  public function createUser(Request $request, Response $response)
  {
      $data = get_object_vars(json_decode($request->getBody()));
      
      $fields = ['celular'];
      $errors = [];

      foreach ($fields as $field)
      {
          $validator = $field . 'Validator';
          $error = $this->validate->$validator($data[$field]);

          if ($error)
          {
            $errors[$field] = $error;
          }
      }

      if (!empty($errors))
      {
        $response->getBody()->write(json_encode(['message' => $errors]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
      }

      try{
        $this->userRepository->createUser($data);
        $response->getBody()->write(json_encode(['message' => 'User created successfully']));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
      }
      catch(PDOException $e)
      {
        $response->getBody()->write(json_encode($e->getMessage()));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
      }
  }

  public function loginUser(Request $request, Response $response) 
  {
    $data = json_decode($request->getBody());

    try{
      $user = $this->userRepository->getUserLogin($data->login);

      $verify = password_verify($data->senha, $user["senha"]);
      
      if ($verify)
      {
        $token = $this->auth->createToken($user);
      
        // Retorna o token JWT no cabeçalho de autorização
        $response->withHeader('Authorization', $token);
        
        // Retorne o token JWT na resposta
        $response->getBody()->write(json_encode(['token' => $token]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
      }
    }
    catch(PDOException $e)
    {
      $response->getBody()->write(json_encode($e->getMessage()));
      return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
  }

  public function updateAddress(Request $request, Response $response, $args)
  {
    $data = get_object_vars(json_decode($request->getBody()));
    
    try{
      $this->userRepository->updateAddress($data, $args);

      $response->getBody()->write(json_encode(['message' => "sucesso"]));
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
    catch(PDOException $e)
    {
      $response->getBody()->write(json_encode($e->getMessage()));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
  }

  public function updateUser(Request $request, Response $response, $args)
  {
    $data = get_object_vars(json_decode($request->getBody()));

    $fields = ['cpf', 'celular', 'telefone'];
    $errors = [];

    foreach ($fields as $field)
    {
        $validator = $field . 'Validator';
        $error = $this->validate->$validator($data[$field]);

        if ($error)
        {
          $errors[$field] = $error;
        }
    }

    if (!empty($errors))
    {
      $response->getBody()->write(json_encode(['message' => $errors]));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
    
    try{
      $this->userRepository->updateUser($data, $args);

      $response->getBody()->write(json_encode(['message' => "sucesso"]));
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
    catch(PDOException $e)
    {
      $response->getBody()->write(json_encode($e->getMessage()));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
  }

  public function deleteUser(Request $request, Response $response, $args)
  {
    $data = $args['id'];
    
    try{
      $deletedUser = $this->userRepository->deleteUserById($data);
      $response->getBody()->write(json_encode($deletedUser));
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
    catch(PDOException $e)
    {
      $response->getBody()->write(json_encode($e->getMessage()));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
  }
}