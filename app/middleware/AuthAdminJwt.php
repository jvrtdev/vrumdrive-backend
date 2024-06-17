<?php 
namespace App\Middleware;

use App\Database;
use App\Repositories\UserRepository;
use Error;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;


class AuthAdminJwt
{
    protected string $secret_key;
    protected $userRepository;

    public function __construct()
    {
      $this->secret_key = "vrumvrumdrivetopdemais";
      
      $database = new Database();

      $this->userRepository = new UserRepository($database);
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
      $token = $request->getHeaderLine('Authorization');
      
      if ($token) {
        if (strpos($token, 'Bearer ') === 0) {
          // Remove o prefixo "Bearer " da string
          $token = substr($token, 7);
        }
      $token = trim($token);
      
        
      
      try {
          $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
          $user = $this->userRepository->getUserByIdWithoutJoin($decoded->id);

          if($user && $decoded->tipo == 'Admin' && $user['tipo'] == 'Admin') {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode($decoded));
            return $handler->handle($request);
            
          }
          $response = new \Slim\Psr7\Response();
          $response->getBody()->write(json_encode(['error' => 'Apenas o usuario admin pode ter acesso!']));
          return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            
               
        } catch (Exception $e) {
          $response = new \Slim\Psr7\Response();
          $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
          return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
      }
      
      $response = new \Slim\Psr7\Response();
      $response->getBody()->write(json_encode(['error' => 'Token not provided']));
      return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}